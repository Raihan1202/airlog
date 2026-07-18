@extends('layouts.app')

@section('title', 'Manage Units')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Manage /</span> Units</h4>
            <div class="text-muted small">Kelola daftar unit/departemen.</div>
        </div>
        <button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addUnitModal">
            <i class="ri-add-line me-1"></i> Add Unit
        </button>
    </div>

    @if(session('successMessage'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('errorMessage'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('errorMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="unitsTable">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Nama Unit</th>
                        <th width="100" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td>{{ $unit->id }}</td>
                        <td>
                            <span class="fw-medium text-heading">{{ $unit->nama }}</span>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ri-more-2-line"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit-btn" href="javascript:void(0);" 
                                       data-id="{{ $unit->id }}" 
                                       data-nama="{{ $unit->nama }}">
                                        <i class="ri-pencil-line me-1"></i> Edit
                                    </a>
                                    
                                    <form id="delete-form-{{ $unit->id }}" action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    
                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $unit->id }}, '{{ $unit->nama }}')">
                                        <i class="ri-delete-bin-line me-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <i class="ri-inbox-line fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada data unit.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="namaUnit" name="nama" class="form-control" placeholder="Contoh: IT Support" required />
                                <label for="namaUnit">Nama Unit</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUnitForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="editNamaUnit" name="nama" class="form-control" placeholder="Nama Unit" required />
                                <label for="editNamaUnit">Nama Unit</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Edit Modal Logic
        const editModalEl = document.getElementById('editUnitModal');
        const editModal = new bootstrap.Modal(editModalEl);
        const editForm = document.getElementById('editUnitForm');
        const editInput = document.getElementById('editNamaUnit');

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                
                editInput.value = nama;
                editForm.action = `{{ url('/manage/units') }}/${id}`;
                editModal.show();
            });
        });
    });

    // Delete Confirmation Logic
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Unit?',
            text: `Anda akan menghapus unit "${nama}". Data tidak bisa dikembalikan!`,
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
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
</script>
@endsection