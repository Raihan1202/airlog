<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        $totals = [];

        foreach ($units as $unit) {
            $totals[$unit->id] = Logbook::where('unit_id', $unit->id)->count();
        }

        $totalAll = array_sum($totals);

        return view('dashboard', [
            'units' => $units,
            'totals' => $totals,
            'totalAll' => $totalAll,
        ]);
    }
}
