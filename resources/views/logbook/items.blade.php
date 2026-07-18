@extends('layouts.app')

@section('title', 'Logbook Items')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-0 container-p-y">
        <!-- Header Info -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('logbook.index', $unit_id) }}">Logbook Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $logbook->judul }} - ({{ \Carbon\Carbon::parse($logbook->date)->format('d M Y') }})</li>
                    </ol>
                </nav>
                <h4 class="fw-bold">{{ $logbook->judul }} - ({{ \Carbon\Carbon::parse($logbook->date)->format('d M Y') }})</h4>
            </div>
        </div>

        <!-- Modal Add Logbook Item -->
        <div class="modal fade" id="add-item-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('logbook.item.store', ['unit_id' => $unit_id, 'logbook_id' => $logbook->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Isi Logbook</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" class="form-control" value="{{ $logbook->date }}" required>
                                        <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="peralatan" name="peralatan" class="form-select" required>
                                            <option value="">Pilih Peralatan</option>
                                            <option value="VCS 3020X">VCS 3020X</option>
                                            <option value="Generator">Generator</option>
                                            <option value="UPS">UPS</option>
                                            <option value="AC">AC</option>
                                        </select>
                                        <label for="peralatan">Peralatan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="teknisi" name="teknisi" class="form-select" required>
                                            <option value="">Pilih Teknisi</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <label for="teknisi">Teknisi</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea id="uraian" name="uraian" class="form-control" style="height: 100px" placeholder="Isi uraian" required></textarea>
                                        <label for="uraian">Catatan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="mulai" name="mulai" class="form-control" required>
                                        <label for="mulai">Mulai</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="selesai" name="selesai" class="form-control" required>
                                        <label for="selesai">Selesai</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Logbook Items Table -->
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Logbook Contents</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-item-modal">
                    <i class="ri-add-line me-1"></i>Add Logbook Contents
                </button>
            </div>
            <div class="card-datatable table-responsive">
                <table id="logbook_items_table" class="datatables-users table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TANGGAL KEGIATAN</th>
                            <th>PERALATAN</th>
                            <th>CATATAN</th>
                            <th>TEKNISI</th>
                            <th>MULAI</th>
                            <th>SELESAI</th>
                            <th>DURASI</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($logbookItems as $item)
                        <tr>
                            <td><span class="fw-medium">{{ $loop->iteration }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d/m/Y') }}</td>
                            <td>{{ $item->tools }}</td>
                            <td>{{ $item->catatan }}</td>
                            <td>{{ $item->teknisiUser->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->mulai)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->selesai)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $mulai = \Carbon\Carbon::parse($item->mulai);
                                    $selesai = \Carbon\Carbon::parse($item->selesai);
                                    $durasi = $mulai->diffInDays($selesai) . ' hari';
                                @endphp
                                {{ $durasi }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#edit-item-modal"
                                        data-item-id="{{ $item->id }}">
                                    <i class="ri-edit-box-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-text-danger rounded-pill btn-icon delete-item" 
                                        data-item-id="{{ $item->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#logbook_items_table').DataTable({
            responsive: true,
            language: {
                sLengthMenu: 'Show _MENU_',
                search: '',
                searchPlaceholder: 'Search contents',
                emptyTable: 'No contents available'
            }
        });

        // Handle delete item
        $('.delete-item').on('click', function() {
            var itemId = $(this).data('item-id');
            var $row = $(this).closest('tr');
            
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                var url = "{{ route('logbook.item.destroy', ['unit_id' => $unit_id, 'logbook_id' => $logbook->id, 'item_id' => ':item_id']) }}";
                url = url.replace(':item_id', itemId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $row.remove();
                            toastr.success('Item berhasil dihapus');
                        }
                    },
                    error: function() {
                        toastr.error('Gagal menghapus item');
                    }
                });
            }
        });
    });
</script>
@endpush
