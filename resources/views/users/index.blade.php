@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* --- FIX UTAMA: AGAR SWEETALERT MUNCUL DI ATAS MODAL --- */
    .swal2-container { z-index: 9999 !important; }

    /* FIX DATATABLES MODAL */
    .dtr-bs-modal .modal-body { padding: 0 !important; }
    .dtr-details { width: 100%; border-collapse: collapse; }
    .dtr-details tr { border-bottom: 1px solid #eceef1; }
    .dtr-details tr:last-child { border-bottom: none; }
    .dtr-details td { padding: 12px 24px; vertical-align: middle; }
    .dtr-details .dtr-title { font-weight: 600; color: #566a7f; width: 35%; padding-right: 16px; font-size: 0.9rem; background-color: #fcfdfd; }
    .dtr-details .dtr-data { color: #697a8d; font-weight: 400; }
    .dtr-details .dtr-data img { max-width: 80px; height: auto; border: 1px solid #d9dee3; padding: 4px; border-radius: 6px; background: #fff; }
    .dtr-details .dtr-data .badge { font-size: 0.75rem; }

    /* SIGNATURE PAD */
    .signature-container { display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #fdfdfd; padding: 10px; border-radius: 8px; }
    canvas#signature-pad, canvas#edit-signature-pad { border: 2px dashed #d9dee3; border-radius: 8px; background-color: #fff; cursor: crosshair; box-shadow: 0 2px 6px rgba(0,0,0,0.05); touch-action: none; }
    canvas#signature-pad:hover, canvas#edit-signature-pad:hover { border-color: #696cff; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-datatable table-responsive text-nowrap">
            <table id="users_list" class="datatables-users table table-hover w-100">
                <thead>
                    <tr>
                        <th style="width: 10px;"></th>
                        <th style="width: 30px;">ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Position</th>
                        <th>Signature</th>
                        <th style="width: 80px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td></td>
                        <td>{{ $user->id }}</td>
                        <td><span class="fw-medium">{{ $user->name }}</span></td>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        <td title="{{ $user->address }}">{{ Str::limit($user->address, 20) }}</td>
                        <td>
                            @if($user->access_level == 2) <span class="badge rounded-pill bg-label-success">Admin</span>
                            @elseif($user->access_level == 1) <span class="badge rounded-pill bg-label-warning">Staff</span>
                            @else <span class="badge rounded-pill bg-label-secondary">User</span>
                            @endif
                        </td>
                        <td>{{ $user->position_relation->name ?? 'N/A' }}</td> <td>
                            @if($user->signature)
                                @php
                                    $sigSrc = str_starts_with($user->signature, 'data:image') ? $user->signature : asset($user->signature);
                                @endphp
                                <img src="{{ $sigSrc }}" alt="Sig" style="width: 60px; height: 30px; object-fit: contain; border: 1px solid #eee; background: #fff; padding: 2px; border-radius: 4px;" />
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="/profile/{{ $user->name }}" class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect" title="View Profile">
                                    <i class="ri-eye-line ri-20px"></i>
                                </a>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item waves-effect edit-record" href="javascript:;" data-id="{{ $user->id }}">
                                            <i class="ri-edit-box-line me-2"></i><span>Edit</span>
                                        </a>
                                        <a class="dropdown-item waves-effect delete-record text-danger" href="javascript:;" data-id="{{ $user->id }}">
                                            <i class="ri-delete-bin-7-line me-2"></i><span>Delete</span>
                                        </a>
                                    </div>
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

<div class="modal fade" id="addNewUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="address-title mb-2">Add New User</h4>
                    <p class="address-subtitle">Add new user for new account</p>
                </div>
                <form id="addNewUserForm" class="row g-5">
                    @csrf
                    <div class="col-12">
                        <div class="row g-5">
                            <div class="col-md mb-md-0 mb-5">
                                <div class="form-check custom-option custom-option-icon checked">
                                    <label class="form-check-label custom-option-content" for="customRadioIcon1">
                                        <span class="custom-option-body"><i class="ri-user-line"></i><span class="custom-option-title mb-2">User</span><small> Create & edit own logbook. </small></span>
                                        <input name="customRadioIcon-01" class="form-check-input" type="radio" value="0" id="customRadioIcon1" checked>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-5">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioIcon2">
                                        <span class="custom-option-body"><i class="ri-customer-service-2-line"></i><span class="custom-option-title mb-2"> Staff </span><small> Create, edit & approve. </small></span>
                                        <input name="customRadioIcon-01" class="form-check-input" type="radio" value="1" id="customRadioIcon2">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioIcon3">
                                        <span class="custom-option-body"><i class="ri-admin-line"></i><span class="custom-option-title mb-2"> Admin </span><small> Full Access. </small></span>
                                        <input name="customRadioIcon-01" class="form-check-input" type="radio" value="2" id="customRadioIcon3">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressFirstName" name="modalAddressFirstName" class="form-control" placeholder="John" required /><label>First Name</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressLastName" name="modalAddressLastName" class="form-control" placeholder="Doe" required /><label>Last Name</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalUsername" name="modalUsername" class="form-control" placeholder="LunarEcho" required /><label>Username</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalGelar" name="modalGelar" class="form-control" placeholder="S.Kom." /><label>Title</label></div></div>
                    
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <select id="modalPosition" name="position" class="select2 form-select" required>
                                <option value="" selected disabled>Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->no }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                            <label>Position</label>
                        </div>
                    </div>

                    <div class="col-12"><div class="form-floating form-floating-outline"><select id="modalAddressCountry" name="modalAddressCountry" class="select2 form-select"><option value="Indonesia" selected>Indonesia</option><option value="United States">United States</option></select><label>Country</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="email" id="modalAddressEmail" name="modalAddressEmail" class="form-control" placeholder="user@example.com" required /><label>Email</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalPhoneNumber" name="modalPhoneNumber" class="form-control" placeholder="+62" required /><label>Phone Number</label></div></div>
                    <div class="col-12"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressAddress1" name="modalAddressAddress1" class="form-control" placeholder="Street Address" required /><label>Address Line 1</label></div></div>
                    <div class="col-12"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressAddress2" name="modalAddressAddress2" class="form-control" placeholder="Apartment" /><label>Address Line 2</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressCity" name="modalAddressCity" class="form-control" placeholder="Jakarta" required /><label>City</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressState" name="modalAddressState" class="form-control" placeholder="DKI Jakarta" required /><label>State</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="modalAddressZipCode" name="modalAddressZipCode" class="form-control" placeholder="10110" required /><label>Zip Code</label></div></div>
                    <div class="col-12 mt-4"><div class="form-check form-switch"><input type="checkbox" class="form-check-input" id="technician" name="technician" /><label>Is this user a technician?</label></div></div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium mb-2">Signature Option</label>
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="sigOption" id="sigDraw" autocomplete="off" checked onchange="toggleSigMethod('draw', 'add')">
                            <label class="btn btn-outline-primary" for="sigDraw"><i class="ri-pencil-line me-1"></i> Draw</label>

                            <input type="radio" class="btn-check" name="sigOption" id="sigUpload" autocomplete="off" onchange="toggleSigMethod('upload', 'add')">
                            <label class="btn btn-outline-primary" for="sigUpload"><i class="ri-upload-cloud-line me-1"></i> Upload Image</label>
                        </div>

                        <div id="section-draw-add" class="signature-container text-center">
                            <canvas id="signature-pad" width="400" height="200"></canvas>
                            <div class="mt-2">
                                <button id="clear-button" class="btn btn-sm btn-outline-danger waves-effect"><i class="ri-eraser-line me-1"></i> Clear</button>
                            </div>
                        </div>

                        <div id="section-upload-add" class="d-none">
                            <div class="form-floating form-floating-outline">
                                <input type="file" id="signatureFile" class="form-control" accept="image/png, image/jpeg, image/jpg" />
                                <label for="signatureFile">Choose Image File</label>
                            </div>
                            <small class="text-muted">Max size: 2MB. Format: PNG, JPG.</small>
                        </div>
                    </div>
                    <input type="hidden" id="signature" name="signature">
                    
                    <div class="col-12 mt-6 d-flex flex-wrap justify-content-center gap-4">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0">
                <div class="text-center mb-6">
                    <h4 class="address-title mb-2">Edit User</h4>
                    <p class="address-subtitle">Edit user details</p>
                </div>
                <form id="editUserForm" class="row g-5">
                    @method('PUT')
                    @csrf
                    <div class="col-12">
                        <div class="row g-5">
                            <div class="col-md mb-md-0 mb-5"><div class="form-check custom-option custom-option-icon"><label class="form-check-label custom-option-content" for="editRadioIcon1"><span class="custom-option-body"><i class="ri-user-line"></i><span class="custom-option-title mb-2">User</span><small> Create & edit own logbook. </small></span><input name="editRadioIcon-01" class="form-check-input" type="radio" value="0" id="editRadioIcon1"></label></div></div>
                            <div class="col-md mb-md-0 mb-5"><div class="form-check custom-option custom-option-icon"><label class="form-check-label custom-option-content" for="editRadioIcon2"><span class="custom-option-body"><i class="ri-customer-service-2-line"></i><span class="custom-option-title mb-2"> Staff </span><small> Create, edit, approve. </small></span><input name="editRadioIcon-01" class="form-check-input" type="radio" value="1" id="editRadioIcon2"></label></div></div>
                            <div class="col-md"><div class="form-check custom-option custom-option-icon"><label class="form-check-label custom-option-content" for="editRadioIcon3"><span class="custom-option-body"><i class="ri-admin-line"></i><span class="custom-option-title mb-2"> Admin </span><small> Full Access. </small></span><input name="editRadioIcon-01" class="form-check-input" type="radio" value="2" id="editRadioIcon3"></label></div></div>
                        </div>
                    </div>
                    <input type="hidden" id="editUserId" name="user_id">
                    
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editFirstName" name="editFirstName" class="form-control" required /><label>First Name</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editLastName" name="editLastName" class="form-control" required /><label>Last Name</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editUsername" name="editUsername" class="form-control" required /><label>Username</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editGelar" name="editGelar" class="form-control" /><label>Title</label></div></div>
                    
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <select id="editPosition" name="position" class="select2 form-select" required>
                                <option value="" disabled>Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->no }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                            <label>Position</label>
                        </div>
                    </div>

                    <div class="col-12"><div class="form-floating form-floating-outline"><select id="editCountry" name="editCountry" class="select2 form-select"><option value="Indonesia">Indonesia</option><option value="United States">United States</option></select><label>Country</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="email" id="editEmail" name="editEmail" class="form-control" required /><label>Email</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editPhoneNumber" name="editPhoneNumber" class="form-control" required /><label>Phone Number</label></div></div>
                    <div class="col-12"><div class="form-floating form-floating-outline"><input type="text" id="editAddress1" name="editAddress1" class="form-control" required /><label>Address Line 1</label></div></div>
                    <div class="col-12"><div class="form-floating form-floating-outline"><input type="text" id="editAddress2" name="editAddress2" class="form-control" /><label>Address Line 2</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editCity" name="editCity" class="form-control" required /><label>City</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editState" name="editState" class="form-control" required /><label>State</label></div></div>
                    <div class="col-12 col-md-6"><div class="form-floating form-floating-outline"><input type="text" id="editZipCode" name="editZipCode" class="form-control" required /><label>Zip Code</label></div></div>
                    <div class="col-12 mt-4"><div class="form-check form-switch"><input type="checkbox" class="form-check-input" id="editTechnician" name="editTechnician" /><label>Is this user a technician?</label></div></div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium mb-2">Signature Option</label>
                        <div class="btn-group w-100 mb-3" role="group">
                            <input type="radio" class="btn-check" name="editSigOption" id="editSigDraw" autocomplete="off" checked onchange="toggleSigMethod('draw', 'edit')">
                            <label class="btn btn-outline-primary" for="editSigDraw"><i class="ri-pencil-line me-1"></i> Draw</label>

                            <input type="radio" class="btn-check" name="editSigOption" id="editSigUpload" autocomplete="off" onchange="toggleSigMethod('upload', 'edit')">
                            <label class="btn btn-outline-primary" for="editSigUpload"><i class="ri-upload-cloud-line me-1"></i> Upload Image</label>
                        </div>

                        <div id="section-draw-edit" class="signature-container text-center">
                            <canvas id="edit-signature-pad" width="400" height="200"></canvas>
                            <div class="mt-2"><button id="edit-clear-button" class="btn btn-sm btn-outline-danger waves-effect"><i class="ri-eraser-line me-1"></i> Clear</button></div>
                        </div>

                        <div id="section-upload-edit" class="d-none">
                            <div class="form-floating form-floating-outline">
                                <input type="file" id="editSignatureFile" class="form-control" accept="image/png, image/jpeg, image/jpg" />
                                <label for="editSignatureFile">Choose New Signature File</label>
                            </div>
                            <small class="text-muted">Max size: 2MB. Format: PNG, JPG.</small>
                        </div>
                    </div>
                    <input type="hidden" id="editSignature" name="editSignature">
                    
                    <div class="col-12 mt-6 d-flex flex-wrap justify-content-center gap-4">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi Toggle Signature yang Diperbarui
function toggleSigMethod(method, context) {
    var drawSection = context === 'add' ? '#section-draw-add' : '#section-draw-edit';
    var uploadSection = context === 'add' ? '#section-upload-add' : '#section-upload-edit';
    
    if(method === 'draw') { 
        $(drawSection).removeClass('d-none'); 
        $(uploadSection).addClass('d-none'); 
    } else { 
        $(drawSection).addClass('d-none'); 
        $(uploadSection).removeClass('d-none'); 
    }
}

$(document).ready(function() {
    function initSignaturePad(canvasId, clearBtnId) {
        const canvas = document.getElementById(canvasId);
        const clearBtn = document.getElementById(clearBtnId);
        if (!canvas) return;
        const ctx = canvas.getContext("2d");
        let drawing = false, prevX = 0, prevY = 0;
        ctx.strokeStyle = "#000"; ctx.lineWidth = 2; ctx.lineJoin = "round";
        function getPos(e) { const rect = canvas.getBoundingClientRect(); return { x: e.clientX - rect.left, y: e.clientY - rect.top }; }
        $(canvas).on('mousedown', function(e) { drawing = true; const pos = getPos(e); prevX = pos.x; prevY = pos.y; });
        $(canvas).on('mousemove', function(e) { if (!drawing) return; const pos = getPos(e); ctx.beginPath(); ctx.moveTo(prevX, prevY); ctx.lineTo(pos.x, pos.y); ctx.closePath(); ctx.stroke(); prevX = pos.x; prevY = pos.y; });
        $(canvas).on('mouseup mouseleave', function() { drawing = false; });
        if (clearBtn) { $(clearBtn).on('click', function(e) { e.preventDefault(); ctx.clearRect(0, 0, canvas.width, canvas.height); }); }
    }
    initSignaturePad('signature-pad', 'clear-button');
    initSignaturePad('edit-signature-pad', 'edit-clear-button');

    function isCanvasBlank(canvas) {
        const blank = document.createElement('canvas'); blank.width = canvas.width; blank.height = canvas.height;
        return canvas.toDataURL() === blank.toDataURL();
    }

    function readFileAsBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
            reader.readAsDataURL(file);
        });
    }

    // --- DataTable Initialization (Sama seperti sebelumnya) ---
    var dt_user_table = $('#users_list').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (row) { return '<i class="ri-user-search-line me-2"></i> Detail Pengguna: ' + row.data()[3]; }
                }),
                type: 'column',
                renderer: function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col, i) {
                        if (col.columnIndex === 0 || col.columnIndex === 9) return ''; 
                        return col.hidden ? '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '"><td class="dtr-title">' + col.title + ':' + '</td> <td class="dtr-data">' + col.data + '</td></tr>' : '';
                    }).join('');
                    return data ? $('<table class="table dtr-details text-nowrap"/>').append(data) : false;
                }
            }
        },
        language: { sLengthMenu: 'Show _MENU_', search: '', searchPlaceholder: 'Search Users' },
        columnDefs: [{ className: 'control', orderable: false, targets: 0, responsivePriority: 1 }, { targets: 2, responsivePriority: 1 }, { targets: -1, responsivePriority: 2 }, { targets: [4, 5, 8, 9], responsivePriority: 10 }],
        dom: '<"card-header dt-header border-bottom"<"d-flex align-items-center" B><"d-flex align-items-center gap-2" f <"add-new"> >>t<"row mx-2 mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [{ extend: 'collection', className: 'btn btn-outline-secondary dropdown-toggle waves-effect waves-light', text: '<i class="ri-upload-2-line ri-16px me-2"></i> Export', buttons: ['print', 'csv', 'excel', 'pdf', 'copy'] }]
    });

    $('.add-new').html("<button class='btn btn-primary waves-effect waves-light text-nowrap' data-bs-toggle='modal' data-bs-target='#addNewUser'><i class='ri-add-line me-1'></i> Add New</button>");
    $('#modalUsername, #editUsername').on('input', function() { $(this).val($(this).val().toLowerCase().replace(/[^a-z0-9]/g, '')); });

    // --- ADD USER SUBMIT LOGIC ---
    var isSubmitting = false;
    $('#addNewUserForm').on('submit', async function (e) {
        e.preventDefault();
        if (isSubmitting) return;

        let finalSignature = '';
        if ($('#sigDraw').is(':checked')) {
            var canvas = document.getElementById('signature-pad');
            if (isCanvasBlank(canvas)) { Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Harap berikan tanda tangan.' }); return false; }
            finalSignature = canvas.toDataURL('image/png');
        } else {
            const fileInput = document.getElementById('signatureFile');
            if (fileInput.files.length === 0) { Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Harap upload file tanda tangan.' }); return false; }
            try { finalSignature = await readFileAsBase64(fileInput.files[0]); } 
            catch (err) { Swal.fire({ icon: 'error', text: 'Gagal membaca file.' }); return false; }
        }
        $('#signature').val(finalSignature);

        isSubmitting = true;
        $.ajax({
            url: '{{ route("users.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#addNewUser').modal('hide');
                    sessionStorage.setItem('successMessage', data.message);
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, showConfirmButton: false, timer: 1500 }).then(() => { location.reload(); });
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan jaringan.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Gagal!', text: msg });
            },
            complete: function() { isSubmitting = false; }
        });
    });

    // --- EDIT BUTTON CLICK ---
    $('#users_list').on('click', '.edit-record', function () {
        var userId = $(this).data('id');
        var editCanvas = document.getElementById('edit-signature-pad');
        if (editCanvas) { var ctx = editCanvas.getContext('2d'); ctx.clearRect(0, 0, editCanvas.width, editCanvas.height); }
        
        // Reset radio buttons to 'Draw' by default
        $('#editSigDraw').prop('checked', true);
        toggleSigMethod('draw', 'edit');
        $('#editSignatureFile').val(''); // Reset file input

        $.ajax({
            url: '{{ route("users.edit", ":id") }}'.replace(':id', userId),
            type: 'GET',
            success: function(data) {
                if (data.success) {
                    var user = data.user;
                    $('#editUserId').val(user.id);
                    $('#editFirstName').val(user.first_name);
                    $('#editLastName').val(user.last_name);
                    $('#editGelar').val(user.gelar);
                    $('#editUsername').val(user.username);
                    $('#editEmail').val(user.email);
                    $('#editCountry').val(user.country).trigger('change');
                    $('#editAddress1').val(user.address1);
                    $('#editAddress2').val(user.address2);
                    $('#editPhoneNumber').val(user.phone_number);
                    $('#editCity').val(user.city);
                    $('#editState').val(user.state);
                    $('#editZipCode').val(user.zip_code);
                    $('#editTechnician').prop('checked', user.technician == 1);
                    
                    // Set Position Dropdown
                    $('#editPosition').val(user.position).trigger('change');

                    $('.form-check.custom-option').removeClass('checked');
                    var radioId = user.access_level == 2 ? '#editRadioIcon3' : (user.access_level == 1 ? '#editRadioIcon2' : '#editRadioIcon1');
                    $(radioId).prop('checked', true).closest('.form-check.custom-option').addClass('checked');

                    if (user.signature && editCanvas) {
                        var img = new Image();
                        img.src = user.signature.startsWith('data:') ? user.signature : '{{ asset("") }}' + user.signature;
                        img.onload = function() { editCanvas.getContext('2d').drawImage(img, 0, 0, editCanvas.width, editCanvas.height); };
                    }
                    $('#editUserModal').modal('show');
                }
            }
        });
    });

    // --- EDIT USER SUBMIT LOGIC ---
    $('#editUserForm').on('submit', async function (e) {
        e.preventDefault();
        
        let finalSignature = '';
        var canvas = document.getElementById('edit-signature-pad');
        
        // Cek mode mana yang aktif
        if ($('#editSigDraw').is(':checked')) {
            // Jika canvas tidak kosong, gunakan itu. Jika kosong, biarkan kosong (controller mungkin akan mempertahankan yang lama)
            if (canvas && !isCanvasBlank(canvas)) { 
                finalSignature = canvas.toDataURL('image/png'); 
            }
        } else {
            const fileInput = document.getElementById('editSignatureFile');
            if (fileInput.files.length > 0) { 
                try { finalSignature = await readFileAsBase64(fileInput.files[0]); } 
                catch (err) { Swal.fire({ icon: 'error', text: 'Gagal membaca file.' }); return false; }
            }
        }
        
        // Set nilai ke hidden input
        $('#editSignature').val(finalSignature);

        var userId = $('#editUserId').val();
        $.ajax({
            url: '{{ route("users.update", ":id") }}'.replace(':id', userId),
            type: 'PUT',
            data: $(this).serialize(),
            success: function(data) {
                if (data.success) {
                    $('#editUserModal').modal('hide');
                    sessionStorage.setItem('successMessage', data.message);
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, showConfirmButton: false, timer: 1500 }).then(() => { location.reload(); });
                }
            },
            error: function(xhr) {
                let msg = 'Gagal update user.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Error!', text: msg });
            }
        });
    });

    // --- DELETE RECORD LOGIC (Sama seperti sebelumnya) ---
    $('#users_list').on('click', '.delete-record', function () {
        var id = $(this).data('id');
        Swal.fire({ title: 'Hapus User?', text: "Data tidak bisa dikembalikan!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus!', customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-secondary' }, buttonsStyling: false }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("users.destroy", ":id") }}'.replace(':id', id),
                    type: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function(data) {
                        if (data.success) { sessionStorage.setItem('successMessage', data.message); location.reload(); }
                        else { Swal.fire({ icon: 'error', text: data.message }); }
                    },
                    error: function() { Swal.fire({ icon: 'error', text: 'Gagal menghapus user.' }); }
                });
            }
        });
    });
});
</script>
@endsection