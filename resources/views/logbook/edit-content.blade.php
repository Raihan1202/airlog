@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-0 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Logbook /</span> Edit Content
        </h4>

        <div class="modal fade" id="edit-logbook-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="edit-logbook-form" method="POST" action="{{ route('logbook.update', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id]) }}">
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
                                        <input type="text" id="edit_nameWithTitle" name="nameWithTitle" class="form-control" value="{{ $logbook->judul }}" required>
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
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="1" id="edit_radio_pagi" {{ $logbook->shift == '1' ? 'checked' : '' }}>
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
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="2" id="edit_radio_siang" {{ $logbook->shift == '2' ? 'checked' : '' }}>
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
                                                        <input name="radio_shift" class="form-check-input" type="radio" value="3" id="edit_radio_malam" {{ $logbook->shift == '3' ? 'checked' : '' }}>
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
                                        <input type="date" id="edit_dateWithTitle" name="dateWithTitle" class="form-control" value="{{ $logbook->date }}" required>
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

        <div class="modal fade" id="add-content-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="add-content-form" method="POST" action="{{ route('logbook.item.store', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id]) }}">
                        @csrf
                        <input type="hidden" name="logbook_id" value="{{ $logbook->id }}">
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Content Logbook</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="catatan" class="form-label">Catatan Kegiatan <span class="text-danger">*</span></label>
                                    <textarea name="catatan" id="catatan" class="form-control" rows="4" placeholder="Masukkan detail kegiatan..." required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" class="form-control" value="{{ $logbook->date }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tools" class="form-label">Tools/Alat <span class="text-danger">*</span></label>
                                    <select name="tools" id="tools" class="form-select" required>
                                        <option value="">Pilih Alat</option>
                                        @foreach(\App\Models\Tool::all() as $tool)
                                            <option value="{{ $tool->name }}">{{ $tool->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="teknisi" class="form-label">Teknisi <span class="text-danger">*</span></label>
                                    <select name="teknisi" id="teknisi" class="form-select" required>
                                        <option value="">Pilih Teknisi</option>
                                        @foreach(\App\Models\User::where('technician', 1)->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="mulai" id="mulai" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="selesai" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="selesai" id="selesai" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Tambah Content</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-content-item-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="edit-content-item-form" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Content Item</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="edit_item_catatan" class="form-label">Catatan Kegiatan <span class="text-danger">*</span></label>
                                    <textarea name="catatan" id="edit_item_catatan" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_item_tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_kegiatan" id="edit_item_tanggal_kegiatan" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_item_tools" class="form-label">Tools/Alat <span class="text-danger">*</span></label>
                                    <select name="tools" id="edit_item_tools" class="form-select" required>
                                        <option value="">Pilih Alat</option>
                                        @foreach(\App\Models\Tool::all() as $tool)
                                            <option value="{{ $tool->name }}">{{ $tool->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="edit_item_teknisi" class="form-label">Teknisi <span class="text-danger">*</span></label>
                                    <select name="teknisi" id="edit_item_teknisi" class="form-select" required>
                                        <option value="">Pilih Teknisi</option>
                                        @foreach(\App\Models\User::where('technician', 1)->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_item_mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="mulai" id="edit_item_mulai" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_item_selesai" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="selesai" id="edit_item_selesai" class="form-control" required>
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
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Edit Logbook: {{ $logbook->judul }}</h5>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($logbook->date)->format('d F Y') }} - Shift {{ $logbook->shift == '1' ? 'Pagi' : ($logbook->shift == '2' ? 'Siang' : 'Malam') }}</small>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit-logbook-modal">
                        <i class="ri-edit-line me-1"></i>Edit Info Logbook
                    </button>
                    <a href="{{ route('logbook.index', $unit->id) }}" class="btn btn-secondary btn-sm">
                        <i class="ri-arrow-left-line me-1"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Content Items ({{ $logbookItems->count() }}/10)</h6>
                    @if($logbookItems->count() < 10)
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#add-content-modal">
                            <i class="ri-add-line me-1"></i>Add Content
                        </button>
                    @else
                        <span class="text-muted">Maksimal 10 content telah tercapai</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Catatan</th>
                                <th width="15%">Tools</th>
                                <th width="15%">Teknisi</th>
                                <th width="20%">Waktu</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logbookItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ Str::limit($item->catatan, 50) }}</td>
                                <td>{{ $item->tools }}</td>
                                <td>{{ $item->teknisi_user->name ?? 'N/A' }}</td>
                                <td>
                                    <small>
                                        <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($item->mulai)->format('d/m/Y H:i') }}<br>
                                        <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($item->selesai)->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-item-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit-content-item-modal"
                                            data-item-id="{{ $item->id }}"
                                            data-catatan="{{ $item->catatan }}"
                                            data-tanggal="{{ $item->tanggal_kegiatan }}"
                                            data-tools="{{ $item->tools }}"
                                            data-teknisi="{{ $item->teknisi }}"
                                            data-mulai="{{ $item->mulai }}"
                                            data-selesai="{{ $item->selesai }}"
                                            data-unit-id="{{ $unit->id }}"
                                            data-logbook-id="{{ $logbook->id }}">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-item-btn" 
                                            data-item-id="{{ $item->id }}"
                                            data-unit-id="{{ $unit->id }}"
                                            data-logbook-id="{{ $logbook->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada content item. Klik "Add Content" untuk menambah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#add-content-form').on('submit', function(e) {
        e.preventDefault();
        
        var mulai = $('#mulai').val();
        var selesai = $('#selesai').val();
        
        if (mulai && selesai) {
            var waktuMulai = new Date(mulai);
            var waktuSelesai = new Date(selesai);
            
            if (waktuSelesai <= waktuMulai) {
                sessionStorage.setItem('errorMessage', 'Waktu selesai harus lebih besar dari waktu mulai!');
                return false;
            }
        }
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).text('Menyimpan...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#add-content-modal').modal('hide');
                form[0].reset();
                sessionStorage.setItem('successMessage', "Content berhasil ditambahkan!");
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const errorList = Object.values(errors).flat();
                    errorMessage = errorList.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                sessionStorage.setItem('errorMessage', errorMessage);
                submitBtn.prop('disabled', false).text('Simpan');
            }
        });
        
        return false;
    });

    $(document).on('click', '.edit-item-btn', function() {
        const btn = $(this);
        
        $('#edit_item_catatan').val(btn.data('catatan'));
        $('#edit_item_tanggal_kegiatan').val(btn.data('tanggal'));
        $('#edit_item_tools').val(btn.data('tools'));
        $('#edit_item_teknisi').val(btn.data('teknisi'));
        $('#edit_item_mulai').val(btn.data('mulai'));
        $('#edit_item_selesai').val(btn.data('selesai'));
        
        const unitId = btn.data('unit-id');
        const logbookId = btn.data('logbook-id');
        const itemId = btn.data('item-id');
        const actionUrl = `/logbook/${unitId}/dashboard/${logbookId}/item/${itemId}`;
        $('#edit-content-item-form').attr('action', actionUrl);
    });
    
    $('#edit-content-item-form').on('submit', function(e) {
        e.preventDefault();
        
        var mulai = $('#edit_item_mulai').val();
        var selesai = $('#edit_item_selesai').val();
        
        if (mulai && selesai) {
            var waktuMulai = new Date(mulai);
            var waktuSelesai = new Date(selesai);
            
            if (waktuSelesai <= waktuMulai) {
                sessionStorage.setItem('errorMessage', 'Waktu selesai harus lebih besar dari waktu mulai!');
                return false;
            }
        }
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).text('Menyimpan...');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#edit-content-item-modal').modal('hide');
                sessionStorage.setItem('successMessage', 'Content berhasil diupdate!');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan saat mengupdate data.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    const errorList = Object.values(errors).flat();
                    errorMessage = errorList.join('\n');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                sessionStorage.setItem('errorMessage', errorMessage);
                submitBtn.prop('disabled', false).text('Save Changes');
            }
        });
        
        return false;
    });
    
    $(document).on('click', '.delete-item-btn', function() {
        const btn = $(this);
        const itemId = btn.data('item-id');
        const unitId = btn.data('unit-id');
        const logbookId = btn.data('logbook-id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Item logbook ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                const originalHtml = btn.html();
                btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i>');

                $.ajax({
                    url: `/logbook/${unitId}/dashboard/${logbookId}/item/${itemId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('input[name="_token"]').first().val()
                    },
                    success: function(response) {
                        if (response.success) {
                            const msg = response.message || 'Item logbook berhasil dihapus!';
                            sessionStorage.setItem('successMessage', msg);
                            location.reload();
                        } else {
                            const msg = response.message || 'Terjadi kesalahan saat menghapus data.';
                            sessionStorage.setItem('errorMessage', msg);
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete Error:', error);
                        
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Item tidak ditemukan.';
                        } else if (xhr.status === 403) {
                            errorMessage = 'Anda tidak memiliki izin untuk menghapus item ini.';
                        }
                        
                        sessionStorage.setItem('errorMessage', errorMessage);
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            }
        });
    });
});
</script>
@endpush