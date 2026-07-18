@extends('layouts.app')

@section('title', 'Logbook Dashboard')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-0 container-p-y">
        
        <div class="modal fade" id="new-user" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ route('logbook.store', ['unit_id' => $unit->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="modalCenterTitle">Tambah Logbook Judul</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-6 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="nameWithTitle" name="nameWithTitle" class="form-control" placeholder="Enter Judul" required>
                                        <label for="nameWithTitle">Judul</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col mb-6">
                                    <div class="form-floating form-floating-outline">
                                        <div class="row">
                                            <div class="col-md mb-md-0 mb-5">
                                                <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label custom-option-content" for="radio_pagi">
                                                        <span class="custom-option-body">
                                                            <i class="ri-sun-line"></i>
                                                            <span class="custom-option-title mb-2">Pagi</span>
                                                            <small>Saya bekerja saat pagi hari</small>
                                                        </span>
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="1" id="radio_pagi" checked>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md mb-md-0 mb-5">
                                                <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label custom-option-content" for="radio_siang">
                                                        <span class="custom-option-body">
                                                            <i class="ri-sun-cloudy-line"></i>
                                                            <span class="custom-option-title mb-2">Siang</span>
                                                            <small>Saya bekerja saat siang hari</small>
                                                        </span>
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="2" id="radio_siang">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label custom-option-content" for="radio_malam">
                                                        <span class="custom-option-body">
                                                            <i class="ri-moon-line"></i>
                                                            <span class="custom-option-title mb-2">Malam</span>
                                                            <small>Saya bekerja saat malam hari</small>
                                                        </span>
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="3" id="radio_malam">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="dateWithTitle" name="dateWithTitle" value="{{ now()->toDateString() }}" class="form-control" required>
                                        <label for="dateWithTitle">Tanggal Kegiatan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-logbook-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="edit-logbook-form" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Isi Logbook</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-6 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="edit_nameWithTitle" name="nameWithTitle" class="form-control" placeholder="Enter Judul" required>
                                        <label for="edit_nameWithTitle">Judul</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col mb-6">
                                    <div class="form-floating form-floating-outline">
                                        <div class="row">
                                            <div class="col-md mb-md-0 mb-5">
                                                <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label custom-option-content" for="edit_radio_pagi">
                                                        <span class="custom-option-body">
                                                            <i class="ri-sun-line"></i>
                                                            <span class="custom-option-title mb-2">Pagi</span>
                                                            <small>Saya bekerja saat pagi hari</small>
                                                        </span>
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="1" id="edit_radio_pagi">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md mb-md-0 mb-5">
                                                <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label custom-option-content" for="edit_radio_siang">
                                                        <span class="custom-option-body">
                                                            <i class="ri-sun-cloudy-line"></i>
                                                            <span class="custom-option-title mb-2">Siang</span>
                                                            <small>Saya bekerja saat siang hari</small>
                                                        </span>
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="2" id="edit_radio_siang">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label custom-option-content" for="edit_radio_malam">
                                                        <span class="custom-option-body">
                                                            <i class="ri-moon-line"></i>
                                                            <span class="custom-option-title mb-2">Malam</span>
                                                            <small>Saya bekerja saat malam hari</small>
                                                        </span>
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="3" id="edit_radio_malam">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="edit_dateWithTitle" name="dateWithTitle" class="form-control" required>
                                        <label for="edit_dateWithTitle">Tanggal Kegiatan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">LOGBOOK ({{ strtoupper($unit->nama) }})</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table id="logbook_list" class="datatables-users table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Shift</th>
                            <th>Judul</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($logbooks as $logbook)
                        <tr>
                            <td><span class="fw-medium">{{ $loop->iteration }}</span></td>
                            <td><span class="fw-medium">{{ $logbook->id }}</span></td>
                            <td>{{ $logbook->date }}</td>
                            <td>
                                @if ($logbook->shift == '1') Pagi
                                @elseif ($logbook->shift == '2') Siang
                                @elseif ($logbook->shift == '3') Malam
                                @else Terdapat error.
                                @endif
                            </td>
                            <td>{{ $logbook->judul }}</td>
                            <td>{{ $logbook->createdBy->name ?? 'Deleted User' }}</td>
                            <td>
                                @if ($logbook->is_approved == 0)
                                    <span class="badge rounded-pill bg-label-warning">Pending</span>
                                @else
                                    <span class="badge rounded-pill bg-label-success">Approved</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- EDIT BUTTON: Muncul untuk Admin (2) ATAU Author --}}
                                    @if(auth()->user()->access_level == 2 || auth()->id() == $logbook->created_by)
                                        <a href="javascript:;" 
                                           class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit"
                                           data-bs-toggle="modal" 
                                           data-bs-target="#edit-logbook-modal"
                                           data-logbook-id="{{ $logbook->id }}"
                                           data-judul="{{ $logbook->judul }}"
                                           data-shift="{{ $logbook->shift }}"
                                           data-date="{{ $logbook->date }}">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                    @endif

                                    <div class="dropdown d-inline-block ms-2">
                                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-line"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end m-0">
                                            
                                            {{-- APPROVE: Muncul untuk Admin/Staff (Level >= 1) jika belum diapprove --}}
                                            @if(auth()->user()->access_level >= 1)
                                                @if ($logbook->is_approved == 0)
                                                    <li>
                                                        <a href="{{ route('logbook.approve', ['unit_id' => $logbook->unit_id, 'logbook_id' => $logbook->id]) }}" 
                                                           class="dropdown-item"
                                                           onclick="event.preventDefault(); document.getElementById('approve-form-{{ $logbook->id }}').submit();">
                                                           Approve
                                                        </a>
                                                        <form id="approve-form-{{ $logbook->id }}" action="{{ route('logbook.approve', ['unit_id' => $logbook->unit_id, 'logbook_id' => $logbook->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('PUT')
                                                        </form>
                                                    </li>
                                                @endif
                                            @endif
                                            
                                            {{-- EDIT CONTENT: Muncul untuk Admin (2) ATAU Author --}}
                                            @if(auth()->user()->access_level == 2 || auth()->id() == $logbook->created_by)
                                                <li><a href="{{ route('logbook.edit.content', ['unit_id' => $logbook->unit_id, 'logbook_id' => $logbook->id]) }}" class="dropdown-item">Edit Content</a></li>
                                            @endif

                                            <li><a href="{{ route('logbook.view', ['unit_id' => $logbook->unit_id, 'logbook_id' => $logbook->id]) }}" target="_blank" class="dropdown-item">View</a></li>
                                            
                                            {{-- DELETE: Muncul untuk Admin (2) ATAU Author --}}
                                            @if(auth()->user()->access_level == 2 || auth()->id() == $logbook->created_by)
                                                <div class="dropdown-divider"></div>
                                                <li>
                                                    <a href="javascript:;" 
                                                       data-id="{{ $logbook->id }}" 
                                                       data-unit-id="{{ $unit->id }}" 
                                                       class="dropdown-item text-danger delete-record">
                                                       Delete
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        var dt_user_table = $('#logbook_list').DataTable({
            responsive: true,
            language: {
                sLengthMenu: 'Show _MENU_',
                search: '',
                searchPlaceholder: 'Search Title',
                emptyTable: 'No data available in logbook'
            },
            columnDefs: [
                { targets: 0, orderable: false, searchable: false },
                { targets: -1, orderable: false, searchable: false }
            ],
            dom:
                '<"row"' +
                '<"col-md-2 d-flex align-items-center justify-content-md-start justify-content-center"<"dt-action-buttons mt-5 mt-md-0"B>>' +
                '<"col-md-10"<"d-flex align-items-center justify-content-md-end justify-content-center"<"me-4"f><"add-new">>>' +
                '>t' +
                '<"row"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle waves-effect waves-light',
                    text: '<span class="d-flex align-items-center"><i class="ri-upload-2-line ri-16px me-2"></i> <span class="d-none d-sm-inline-block">Export</span></span> ',
                    buttons: [
                        { extend: 'print', className: 'dropdown-item', exportOptions: { columns: ':visible' } },
                        { extend: 'csv', className: 'dropdown-item', exportOptions: { columns: ':visible' } },
                        { extend: 'excel', className: 'dropdown-item', exportOptions: { columns: ':visible' } },
                        { extend: 'pdf', className: 'dropdown-item', exportOptions: { columns: ':visible' } },
                        { extend: 'copy', className: 'dropdown-item', exportOptions: { columns: ':visible' } }
                    ]
                }
            ]
        });

        $('.add-new').html(
            "<button class='btn btn-primary waves-effect waves-light' data-bs-toggle='modal' data-bs-target='#new-user'><i class='ri-add-line me-0 me-sm-1 d-inline-block d-sm-none'></i><span class='d-none d-sm-inline-block'> Add New Title </span></button>"
        );

        // --- SWEETALERT DELETE LOGIC ---
        $('#logbook_list').on('click', '.delete-record', function () {
            var $row = $(this).closest('tr');
            var logbook_id = $(this).data('id');
            var unit_id = $(this).data('unit-id');
            var table = $('#logbook_list').DataTable();
            
            // Konfirmasi SweetAlert
            Swal.fire({
                title: 'Hapus Logbook?',
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-outline-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat URL Delete yang benar
                    var url = "{{ route('logbook.destroy', ['unit_id' => ':unit_id', 'logbook_id' => ':logbook_id']) }}";
                    url = url.replace(':unit_id', unit_id);
                    url = url.replace(':logbook_id', logbook_id);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                // Hapus baris dari tabel
                                table.row($row).remove().draw(false);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Logbook berhasil dihapus.',
                                    customClass: { confirmButton: 'btn btn-success' }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal menghapus data.',
                                    customClass: { confirmButton: 'btn btn-primary' }
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            var msg = 'Terjadi kesalahan server.';
                            if(jqXHR.responseJSON && jqXHR.responseJSON.message) {
                                msg = jqXHR.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: msg,
                                customClass: { confirmButton: 'btn btn-primary' }
                            });
                        }
                    });
                }
            });
        });

        // Handle Edit Modal (Data Passing)
        $('#edit-logbook-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var logbookId = button.data('logbook-id');
            var judul = button.data('judul');
            var shift = button.data('shift');
            var date = button.data('date');
            var unitId = {{ $unit->id }};
            
            var modal = $(this);
            modal.find('#edit_nameWithTitle').val(judul);
            modal.find('#edit_dateWithTitle').val(date);
            modal.find('input[name="radio_shift"][value="' + shift + '"]').prop('checked', true);
            
            // Update form action URL secara dinamis
            var actionUrl = "{{ route('logbook.update', ['unit_id' => ':unit_id', 'logbook_id' => ':logbook_id']) }}";
            actionUrl = actionUrl.replace(':unit_id', unitId);
            actionUrl = actionUrl.replace(':logbook_id', logbookId);
            
            modal.find('#edit-logbook-form').attr('action', actionUrl);
        });
    });
</script>
@endpush