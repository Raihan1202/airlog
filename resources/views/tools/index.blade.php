@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Manage Tools</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table id="tools_list" class="datatables-users table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tools</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 0; @endphp
                    @foreach($tools as $tool)
                        @php $no++; @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td id="tool_name_{{ $tool->id }}">{{ $tool->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item waves-effect edit-tool" href="javascript:;" data-id="{{ $tool->id }}"><i class="ri-pencil-line"></i> Edit</a>
                                        <a class="dropdown-item waves-effect delete-tool" href="javascript:;" data-id="{{ $tool->id }}"><i class="ri-delete-bin-7-line me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="new-tool" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="tool_form">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title" id="modalCenterTitle">Tambah Peralatan Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="tools_name" name="tools_name" placeholder="Nama Peralatan" aria-label="Nama Peralatan" aria-describedby="basic-icon-default-toolsname">
                                <label for="tools_name">Nama Peralatan</label>
                                <input type="hidden" name="peralatan_id" id="peralatan_id" value="0">
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
    </div>
</div>

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    var dt_user_table = $('#tools_list').DataTable({
        responsive: true,
        language: {
            sLengthMenu: 'Show _MENU_',
            search: '',
            searchPlaceholder: 'Search Tools'
        },
        columnDefs: [
            { targets: 0, orderable: false, searchable: true }
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
                text: '<span class="d-flex align-items-center"><i class="ri-upload-2-line ri-16px me-2"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="ri-printer-line me-1"></i>Print',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .css('color', '#000')
                                .css('border-color', '#aaa')
                                .css('background-color', '#fff');
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('color', 'inherit')
                                .css('border-color', 'inherit')
                                .css('background-color', 'inherit');
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="ri-file-text-line me-1"></i>Csv',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            }
        ]
    });

    $('.add-new').html(
        "<button class='btn btn-primary waves-effect waves-light add-new-tool' data-bs-toggle='modal' data-bs-target='#new-tool'><i class='ri-add-line me-0 me-sm-1 d-inline-block d-sm-none'></i><span class= 'd-none d-sm-inline-block'> Add New Tools </span ></button>"
    );

    $('#tools_list tbody').on('click', '.edit-tool', function () {
        var id = $(this).data('id');
        var toolName = $('#tool_name_' + id).text();
        $('#tools_name').val(toolName);
        $('#peralatan_id').val(id);
        $('#modalCenterTitle').text('Edit Peralatan');
        $('#new-tool').modal('show');
    });

    $('.add-new-tool').on('click', function () {
        $('#tools_name').val("");
        $('#peralatan_id').val(0);
        $('#modalCenterTitle').text('Tambah Peralatan Baru');
        $('#new-tool').modal('show');
    });

    var isSubmitting = false;
    $('#tool_form').on('submit', function (e) {
        e.preventDefault();
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;

        var formData = $(this).serialize();
        $.ajax({
            url: '{{ url("/manage/tools/update") }}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    location.reload();
                }
            },
            error: function () {
                new Notyf().error('Terjadi kesalahan, coba lagi.');
                isSubmitting = false;
            }
        });
    });

    $('#tools_list').on('click', '.delete-tool', function () {
        var toolId = $(this).data('id');
        var $row = $(this).closest('tr');
        var table = $('#tools_list').DataTable();
        var currentPage = table.page();
        var recordsOnPage = table.rows({ page: 'current' }).count();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data peralatan ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/manage/tools/delete',
                    type: 'POST',
                    data: {
                        peralatan_id: toolId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                            
                            $('tr[data-id="' + toolId + '"]').remove();
                            
                            table.row($row).remove().draw(false);
                            
                            table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                                this.cell(rowIdx, 0).data(rowIdx + 1);
                            }).draw(false);

                            if (recordsOnPage === 1 && currentPage > 0) {
                                table.page(currentPage - 1).draw('page');
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message,
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem.',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            }
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection