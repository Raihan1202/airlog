@extends('layouts.app')
@section('title', 'Manage Positions')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card">
  <div class="card-header border-bottom">
    <h5 class="card-title mb-0">Manage Positions</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table id="position_list" class="datatables-users table">
      <thead>
        <tr>
          <th>No</th>
          <th>Positions</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($positions as $index => $row)
          <tr>
            <td>{{ $index+1 }}</td>
            <td id="position_name_{{ $row->no }}">{{ $row->name }}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="ri-more-2-line"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item edit-position" href="#" data-id="{{ $row->no }}">
                    <i class="ri-pencil-line"></i> Edit
                  </a>
                  <a class="dropdown-item delete-position" href="#" data-id="{{ $row->no }}">
                    <i class="ri-delete-bin-7-line me-1"></i> Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="new-position" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="position_form">
        @csrf
        <div class="modal-header">
          <h4 class="modal-title" id="modalCenterTitle">Tambah Jabatan Baru</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="input-group input-group-merge mb-6">
            <span class="input-group-text"><i class="ri-tools-line"></i></span>
            <div class="form-floating form-floating-outline">
              <input type="text" class="form-control" id="position_name" name="position_name" placeholder="Nama Jabatan">
              <label for="position_name">Nama Jabatan</label>
              <input type="hidden" name="position_id" id="position_id" value="0">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
  var dt_user_table = $('#position_list').DataTable({
    responsive: true,
    language: {
      sLengthMenu: 'Show _MENU_',
      search: '',
      searchPlaceholder: 'Search Positions'
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
        text: '<span class="d-flex align-items-center"><i class="ri-upload-2-line ri-16px me-2"></i> <span class="d-none d-sm-inline-block">Export</span></span> ',
        buttons: [
          { extend: 'print', text: '<i class="ri-printer-line me-1"></i>Print', className: 'dropdown-item' },
          { extend: 'csv', text: '<i class="ri-file-text-line me-1"></i>Csv', className: 'dropdown-item' },
          { extend: 'excel', text: '<i class="ri-file-excel-line me-1"></i>Excel', className: 'dropdown-item' },
          { extend: 'pdf', text: '<i class="ri-file-pdf-line me-1"></i>Pdf', className: 'dropdown-item' },
          { extend: 'copy', text: '<i class="ri-file-copy-line me-1"></i>Copy', className: 'dropdown-item' }
        ]
      }
    ]
  });

  $('.add-new').html(
    "<button class='btn btn-primary add-new-position' data-bs-toggle='modal' data-bs-target='#new-position'><i class='ri-add-line me-0 me-sm-1 d-inline-block d-sm-none'></i><span class='d-none d-sm-inline-block'>Add New Positions</span></button>"
  );

  $('#position_list').on('click', '.edit-position', function () {
    var id = $(this).data('id');
    var positionName = $('#position_name_' + id).text();
    $('#position_name').val(positionName);
    $('#position_id').val(id);
    $('#modalCenterTitle').text('Edit Jabatan');
    $('#new-position').modal('show');
  });

  $(document).on('click', '.add-new-position', function () {
    $('#position_name').val("");
    $('#position_id').val(0);
    $('#modalCenterTitle').text('Tambah Jabatan Baru');
  });

  var isSubmitting = false;
  $('#position_form').on('submit', function (e) {
    e.preventDefault();
    if(isSubmitting) return;
    isSubmitting = true;

    var formData = $(this).serialize();
    $.ajax({
      url: "{{ route('positions.update') }}",
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function(data){
        if(data.success){
          location.reload();
        } else {
          new Notyf().error(data.message);
        }
      },
      error: function() {
        new Notyf().error('Terjadi kesalahan sistem.');
      },
      complete: function() {
        isSubmitting = false;
      }
    });
  });

  $('#position_list').on('click', '.delete-position', function () {
    var id = $(this).data('id');
    var $row = $(this).closest('tr');
    var table = $('#position_list').DataTable();

    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data jabatan ini akan dihapus permanen!",
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
          url: "{{ route('positions.delete') }}",
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            position_id: id
          },
          dataType: 'json',
          success: function(data) {
            if(data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: 'Jabatan berhasil dihapus.',
                customClass: { confirmButton: 'btn btn-success' }
              });
              
              table.row($row).remove().draw(false);
              
              table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                  this.cell(rowIdx, 0).data(rowIdx + 1);
              }).draw(false);
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message,
                customClass: { confirmButton: 'btn btn-danger' }
              });
            }
          },
          error: function() {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Gagal menghapus posisi.',
              customClass: { confirmButton: 'btn btn-danger' }
            });
          }
        });
      }
    });
  });
});
</script>
@endpush