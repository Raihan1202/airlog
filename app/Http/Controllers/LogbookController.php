<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Unit;
use App\Models\LogbookItem;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LogbookController extends Controller
{
    public function index($unit_id)
    {
        try {
            $unit = Unit::find($unit_id);
            if (!$unit) {
                return redirect()->route('dashboard')->with('errorMessage', 'Unit tidak ditemukan.');
            }

            $logbooks = Logbook::where('unit_id', $unit_id)->latest()->get();
            return view('logbook.index', compact('unit', 'logbooks'));
        } catch (\Exception $e) {
            return back()->with('errorMessage', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function apiIndex(Request $request)
    {
        try {
            $query = Logbook::query()->with('unit', 'createdBy');

            if ($request->filled('id')) {
                $query->where('id', $request->id);
            }

            if ($request->filled('unit_id')) {
                $query->where('unit_id', $request->unit_id);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            } elseif ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }

            if ($request->filled('shift')) {
                $query->where('shift', $request->shift);
            }

            if ($request->filled('is_approved')) {
                $query->where('is_approved', $request->is_approved);
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSorts = ['id', 'date', 'created_at', 'judul', 'shift'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }
            
            $perPage = $request->get('per_page', 15);
            $logbooks = $query->paginate($perPage);

            $currentUser = auth()->user();
            $isAdmin = $currentUser && $currentUser->access_level == 2;
            $hiddenFields = [
                'password', 
                'two_factor_secret', 
                'two_factor_recovery_codes', 
                'two_factor_confirmed_at', 
                'remember_token',
                'email_verified_at',
                'access_level'
            ];

            $data = collect($logbooks->items())->map(function ($logbook) use ($isAdmin, $hiddenFields) {
                if ($logbook->createdBy && !$isAdmin) {
                    $logbook->createdBy->makeHidden($hiddenFields);
                }
                return $logbook;
            })->all();

            return response()->json([
                'success' => true,
                'message' => 'Data Logbook berhasil diambil',
                'meta' => [
                    'current_page' => $logbooks->currentPage(),
                    'total' => $logbooks->total(),
                    'per_page' => $logbooks->perPage(),
                    'last_page' => $logbooks->lastPage()
                ],
                'data' => $logbooks->items()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $unit_id, $logbook_id)
    {
        try {
            $logbook = Logbook::with(['items.teknisi_user', 'user', 'unit'])->find($logbook_id);

            if (!$logbook) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Logbook not found'], 404);
                return back()->with('errorMessage', 'Logbook tidak ditemukan');
            }

            $logbookItems = $logbook->items;

            if ($request->wantsJson()) {
                $currentUser = auth()->user();
                $isAdmin = $currentUser && $currentUser->access_level == 2;
                $hiddenFields = [
                    'password', 'two_factor_secret', 'two_factor_recovery_codes', 
                    'two_factor_confirmed_at', 'remember_token', 'access_level', 'email_verified_at'
                ];

                if (!$isAdmin) {
                    if ($logbook->user) {
                        $logbook->user->makeHidden($hiddenFields);
                    }

                    foreach ($logbookItems as $item) {
                        if ($item->teknisi_user) {
                            $item->teknisi_user->makeHidden($hiddenFields);
                        }
                    }
                }
            }

            $viewName = 'logbook.view'; 

            if ($request->wantsJson()) {
                $htmlContent = view($viewName, compact('logbook', 'unit_id', 'logbookItems'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'View retrieved successfully',
                    'data' => [
                        'logbook_id' => $logbook->id,
                        'judul' => $logbook->judul,
                        'html' => $htmlContent,
                        'items' => $logbookItems
                    ]
                ]);
            }

            return view($viewName, compact('logbook', 'unit_id', 'logbookItems'));

        } catch (\Exception $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Error: ' . $e->getMessage());
        }
    }
	
	
	private function sendNotification($logbook, $unit_id)
    {
        try {
            $targetUrl = route('logbook.view', ['unit_id' => $unit_id, 'logbook_id' => $logbook->id]);

            $notification = Notification::create([
                'author_id' => auth()->id(),
                'title' => 'New Logbook Added',
                'body' => auth()->user()->name . ' added a new logbook: ' . $logbook->judul,
                'link' => $targetUrl
            ]);

            $users = User::all();
            $pivotData = [];
            foreach ($users as $user) {
                $pivotData[] = [
                    'user_id' => $user->id,
                    'notification_id' => $notification->id,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('user_notifications')->insert($pivotData);

        } catch (\Exception $e) {
            Log::error('Notif Error: ' . $e->getMessage()); 
        }
    }

    public function store(Request $request, $unit_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nameWithTitle' => 'required|string|min:5|max:64',
                'dateWithTitle' => 'required|date',
                'radio_shift'   => 'required|in:1,2,3',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors'  => $validator->errors()
                    ], 422);
                }
                return back()->with('errorMessage', $validator->errors()->first())->withInput();
            }
            $unit = Unit::find($unit_id);
            if (!$unit) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan'], 404);
                return back()->with('errorMessage', 'Unit ID tidak valid');
            }

            $logbook = new Logbook();
            $logbook->unit_id = $unit_id;
            $logbook->judul = $request->nameWithTitle;
            $logbook->date = $request->dateWithTitle; 
            $logbook->shift = $request->radio_shift;
            $logbook->created_by = auth()->id(); 
            $logbook->is_approved = 0; 
            $logbook->save();

            $this->sendNotification($logbook, $unit_id);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logbook berhasil ditambahkan',
                    'data' => $logbook
                ], 201);
            }

            return redirect()->route('logbook.index', $unit_id)->with('successMessage', 'Logbook berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Error creating logbook:', ['error' => $e->getMessage()]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat logbook: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->with('errorMessage', 'Gagal membuat logbook: ' . $e->getMessage());
        }
    }

    public function approve($unit_id, $logbook_id)
    {
        try {
            $logbook = Logbook::find($logbook_id);

            if (!$logbook) {
                if (request()->wantsJson()) return response()->json(['success' => false, 'message' => 'Logbook tidak ditemukan'], 404);
                return back()->with('errorMessage', 'Logbook tidak ditemukan');
            }

            if (auth()->user()->access_level >= 1) {
                $logbook->update([
                    'is_approved' => 1, 
                    'approved_by' => auth()->user()->id,
                    'signed_by' => auth()->user()->id,
                    'signed_at' => now(),
                ]);

                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Logbook berhasil disetujui',
                        'data' => $logbook
                    ]);
                }

                return redirect()->route('logbook.index', $unit_id)->with('successMessage', 'Status logbook: Disetujui');
            } else {
                if (request()->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak akses'], 403);
                }
                return redirect()->route('logbook.index', $unit_id)->with('errorMessage', 'Anda tidak memiliki hak akses');
            }
        } catch (\Exception $e) {
            if (request()->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Terjadi kesalahan saat approve.');
        }
    }

    public function destroy($unit_id, $logbook_id)
    {
        try {
            $logbook = Logbook::find($logbook_id);

            if (!$logbook) {
                if (request()->wantsJson()) return response()->json(['success' => false, 'message' => 'Logbook tidak ditemukan'], 404);
                return back()->with('errorMessage', 'Logbook tidak ditemukan');
            }

            if ($logbook->created_by != auth()->id() && auth()->user()->access_level != 2) {
                if (request()->wantsJson()) return response()->json(['success' => false, 'message' => 'Anda tidak berhak menghapus logbook ini.'], 403);
                return back()->with('errorMessage', 'Akses ditolak.');
            }
            
            $logbook->delete();

            if (request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Logbook berhasil dihapus']);
            }

            return redirect()->route('logbook.index', $unit_id)->with('successMessage', 'Logbook berhasil dihapus');

        } catch (\Exception $e) {
            if (request()->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Gagal menghapus logbook.');
        }
    }

    public function update(Request $request, $unit_id, $logbook_id) 
    {
        try {
            $logbook = Logbook::find($logbook_id);
            
            if (!$logbook) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Logbook tidak ditemukan'], 404);
                return back()->with('errorMessage', 'Logbook tidak ditemukan');
            }

            if ($logbook->created_by != auth()->id() && auth()->user()->access_level != 2) {
                if ($request->wantsJson()) return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak akses untuk mengedit logbook ini.'], 403);
                return back()->with('errorMessage', 'Akses ditolak.');
            }
            
            $logbook->update([
                'judul' => $request->nameWithTitle,
                'date' => $request->dateWithTitle,
                'shift' => $request->radio_shift
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Logbook updated', 'data' => $logbook]);
            }
            
            return redirect()->route('logbook.index', $unit_id)->with('successMessage', 'Logbook updated');

        } catch(\Exception $e) {
            if ($request->wantsJson()) return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            return back()->with('errorMessage', 'Update failed');
        }
    }
	
    public function create($unit_id) {
        $unit = Unit::find($unit_id);
        if(!$unit) abort(404);
        return view('logbook.create', compact('unit'));
    }

    public function edit($unit_id, $logbook_id) {
        $unit = Unit::find($unit_id);
        $logbook = Logbook::find($logbook_id);
        if(!$unit || !$logbook) abort(404);
        return view('logbook.edit', compact('unit', 'logbook'));
    }

    public function editContent($unit_id, $logbook_id) {
        $unit = Unit::find($unit_id);
        $logbook = Logbook::find($logbook_id);
        if(!$unit || !$logbook) abort(404);
        
        $logbookItems = LogbookItem::with('teknisi_user')
                                   ->where('logbook_id', $logbook_id)
                                   ->orderBy('created_at', 'asc')
                                   ->get();
        return view('logbook.edit-content', compact('unit', 'logbook', 'logbookItems', 'unit_id'));
    }

    public function statistics(Request $request) {
        try {
            $totalLogbooks = Logbook::count();
            $approvedLogbooks = Logbook::where('is_approved', 1)->count();
            $pendingLogbooks = Logbook::where('is_approved', 0)->count();

            $unitsData = Unit::withCount('logbooks')->get();

            $perUnitStats = $unitsData->map(function($unit) {
                return [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->nama, 
                    'total_logbooks' => $unit->logbooks_count,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'global_summary' => [
                        'total_all' => $totalLogbooks,
                        'total_approved' => $approvedLogbooks,
                        'total_pending' => $pendingLogbooks
                    ],
                    'breakdown_per_unit' => $perUnitStats
                ]
            ]);
        } catch(\Exception $e) {
             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}