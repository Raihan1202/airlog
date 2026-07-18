@extends('layouts.app')

@section('title', 'API Tester')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="ri-shield-keyhole-line me-2"></i>Auth Config</h5>
                </div>
                <div class="card-body mt-3">
                    <div class="form-floating form-floating-outline mb-3">
                        <input type="text" id="apiToken" class="form-control" placeholder="Paste Bearer Token">
                        <label for="apiToken">Bearer Token (Sanctum)</label>
                    </div>
                    <button class="btn btn-primary w-100" id="saveTokenBtn" onclick="saveToken()">
                        <i class="ri-save-line me-1"></i> Save Token
                    </button>
                    <div class="mt-2 small text-muted">
                        Token otomatis tersimpan saat Login berhasil.
                    </div>
                </div>
            </div>

            <div class="card" style="height: 600px;">
                <div class="card-header border-bottom bg-light">
                    <h5 class="mb-0">Endpoints</h5>
                    <input type="text" id="searchEndpoint" class="form-control form-control-sm mt-2" placeholder="Cari endpoint...">
                </div>
                <div class="list-group list-group-flush" id="endpointList" style="overflow-y: auto; height: 100%;">
                    </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                    <div>
                        <span class="badge bg-warning me-2" id="selectedMethod">METHOD</span>
                        <span class="fw-bold" id="selectedEndpointTitle">Pilih Endpoint</span>
                    </div>
                    <small class="text-white-50 font-monospace" id="selectedUrl">/api/v1/...</small>
                </div>
                
                <div class="card-body mt-3" style="overflow-y: auto;">
                    <form id="apiTestForm">
                        <div id="dynamicInputs">
                            <div class="text-center mt-5 text-muted">
                                <i class="ri-terminal-window-line ri-3x"></i>
                                <p class="mt-2">Pilih endpoint dari menu kiri untuk memulai tes.</p>
                            </div>
                        </div>
                        
                        <div id="actionButtons" class="d-none mt-4">
                            <div class="alert alert-info py-2 small">
                                <i class="ri-information-line me-1"></i>
                                Headers dikirim: <code>Accept: application/json</code>, <code>Authorization: Bearer ...</code>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearConsole()">Clear Console</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="ri-send-plane-fill me-2"></i>Kirim Request
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Response: <span id="responseStatus" class="badge bg-secondary">-</span></h6>
                            <div>
                                <small class="text-muted me-2" id="responseTime">0 ms</small>
                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary" onclick="copyResponse()" title="Copy JSON">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="bg-label-secondary p-3 rounded position-relative">
                            <pre id="jsonResponse" style="max-height: 500px; overflow: auto; font-size: 12px; color: #2e2e2e; font-family: 'Consolas', monospace;">Menunggu request...</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const endpoints = [
        // ================= AUTH =================
        { 
            group: '1. Auth', method: 'POST', url: '/api/v1/login', name: 'Login', 
            params: [
                { name: 'login', placeholder: 'Email/Username', required: true },
                { name: 'password', type: 'password', value: 'password', required: true },
                { name: 'device_name', value: 'Web Tester' }
            ]
        },
        { group: '1. Auth', method: 'POST', url: '/api/v1/logout', name: 'Logout', auth: true },
        { group: '1. Auth', method: 'GET', url: '/api/v1/profile', name: 'Get Profile', auth: true },
        { 
            group: '1. Auth', method: 'PUT', url: '/api/v1/profile', name: 'Update Profile', auth: true,
            params: [
                { name: 'name', placeholder: 'Full Name' },
                { name: 'phone_number', placeholder: 'Phone Number' },
                { name: 'address', placeholder: 'Address' }
            ]
        },
        // [BARU] Change Password
        { 
            group: '1. Auth', method: 'POST', url: '/api/v1/change-password', name: 'Change Password', auth: true,
            params: [
                { name: 'current_password', type: 'password', placeholder: 'Password Lama', required: true },
                { name: 'password', type: 'password', placeholder: 'Password Baru', required: true },
                { name: 'password_confirmation', type: 'password', placeholder: 'Ulangi Password Baru', required: true }
            ]
        },

        // ================= TOOLS (PERALATAN) =================
        { group: '2. Tools', method: 'GET', url: '/api/v1/tools', name: 'Get All Tools', auth: true },
        { 
            group: '2. Tools', method: 'POST', url: '/api/v1/tools/save', name: 'Create/Update Tool', auth: true,
            params: [
                { name: 'tools_name', placeholder: 'Nama Alat', required: true },
                { name: 'peralatan_id', placeholder: '0 = Baru, >0 = Update', value: '0', required: true }
            ]
        },
        { 
            group: '2. Tools', method: 'POST', url: '/api/v1/tools/delete', name: 'Delete Tool', auth: true,
            params: [{ name: 'peralatan_id', placeholder: 'ID Alat', required: true }]
        },

        // ================= POSITIONS (JABATAN) =================
        { group: '3. Positions', method: 'GET', url: '/api/v1/positions', name: 'Get Positions', auth: true },
        { 
            group: '3. Positions', method: 'POST', url: '/api/v1/positions/save', name: 'Create/Update Position', auth: true,
            params: [
                { name: 'position_name', placeholder: 'Nama Jabatan', required: true },
                { name: 'position_id', placeholder: '0 = Baru, >0 = Update', value: '0', required: true }
            ]
        },
        { 
            group: '3. Positions', method: 'POST', url: '/api/v1/positions/delete', name: 'Delete Position', auth: true,
            params: [{ name: 'position_id', placeholder: 'ID Jabatan', required: true }]
        },

        // ================= UNITS =================
        { group: '4. Units', method: 'GET', url: '/api/v1/units', name: 'Get All Units', auth: true },
        { 
            group: '4. Units', method: 'POST', url: '/api/v1/units', name: 'Create Unit', auth: true,
            params: [{ name: 'nama', placeholder: 'Nama Unit', required: true }]
        },
        { 
            group: '4. Units', method: 'PUT', url: '/api/v1/units/{id}', name: 'Update Unit', auth: true,
            params: [
                { name: 'id', isUrl: true, required: true },
                { name: 'nama', placeholder: 'Nama Unit Baru' }
            ]
        },
        // [BARU] Delete Unit
        { 
            group: '4. Units', method: 'DELETE', url: '/api/v1/units/{id}', name: 'Delete Unit', auth: true,
            params: [{ name: 'id', placeholder: 'ID Unit (URL)', isUrl: true, required: true }]
        },

        // ================= USERS =================
        { group: '5. Users', method: 'GET', url: '/api/v1/users', name: 'Get User List', auth: true },
        { group: '5. Users', method: 'GET', url: '/api/v1/technicians', name: 'Get Technicians', auth: true },
        { 
            group: '5. Users', method: 'POST', url: '/api/v1/users', name: 'Create User', auth: true,
            params: [
                { name: 'modalUsername', placeholder: 'Username', required: true },
                { name: 'modalAddressEmail', placeholder: 'Email', required: true },
                { name: 'modalAddressFirstName', placeholder: 'First Name' },
                { name: 'modalAddressLastName', placeholder: 'Last Name' },
                { name: 'customRadioIcon-01', placeholder: 'Access Level (0,1,2)', value: '0' },
                { name: 'technician', placeholder: '1 if technician', value: '0' }
            ]
        },

        // ================= LOGBOOKS =================
        { 
            group: '6. Logbooks', method: 'GET', url: '/api/v1/logbooks', name: 'Get Logbooks (Filter)', auth: true,
            params: [
                { name: 'unit_id', placeholder: 'Unit ID' },
                { name: 'start_date', type: 'date' },
                { name: 'end_date', type: 'date' },
                { name: 'shift', placeholder: '1, 2, 3' },
                { name: 'is_approved', placeholder: '0/1' }
            ]
        },
        { group: '6. Logbooks', method: 'GET', url: '/api/v1/logbooks-statistics', name: 'Get Statistics', auth: true },
        { 
            group: '6. Logbooks', method: 'POST', url: '/api/v1/units/{unit_id}/logbooks', name: 'Create Logbook', auth: true,
            params: [
                { name: 'unit_id', isUrl: true, required: true },
                { name: 'nameWithTitle', placeholder: 'Judul' },
                { name: 'dateWithTitle', type: 'date' },
                { name: 'radio_shift', placeholder: '1,2,3' }
            ]
        },
        { 
            group: '6. Logbooks', method: 'POST', url: '/api/v1/units/{unit_id}/logbooks/{logbook_id}/approve', name: 'Approve Logbook', auth: true,
            params: [
                { name: 'unit_id', isUrl: true }, { name: 'logbook_id', isUrl: true }
            ]
        },

        // ================= LOGBOOK ITEMS =================
        { 
            group: '7. Logbook Items', method: 'GET', url: '/api/v1/units/{unit_id}/logbooks/{logbook_id}/items', name: 'Get Items', auth: true,
            params: [{ name: 'unit_id', isUrl: true }, { name: 'logbook_id', isUrl: true }]
        },
        { 
            group: '7. Logbook Items', method: 'POST', url: '/api/v1/units/{unit_id}/logbooks/{logbook_id}/items', name: 'Create Item', auth: true,
            params: [
                { name: 'unit_id', isUrl: true }, { name: 'logbook_id', isUrl: true },
                { name: 'catatan', placeholder: 'Detail...' },
                { name: 'tools', placeholder: 'Alat...' },
                { name: 'teknisi', placeholder: 'ID Teknisi' },
                { name: 'tanggal_kegiatan', type: 'date' },
                { name: 'mulai', type: 'time' },
                { name: 'selesai', type: 'time' }
            ]
        },
        { group: '7. Logbook Items', method: 'GET', url: '/api/v1/logbook-items/by-teknisi', name: 'My Tasks (Mobile)', auth: true },

        // ================= NOTIFICATIONS =================
        { group: '8. Notifications', method: 'GET', url: '/api/v1/notifications', name: 'Get All Notifications', auth: true },
        { group: '8. Notifications', method: 'GET', url: '/api/v1/notifications/unread-count', name: 'Get Unread Count', auth: true },
        { 
            group: '8. Notifications', method: 'POST', url: '/api/v1/notifications', name: 'Create Notification', auth: true,
            params: [
                { name: 'title', placeholder: 'Judul Notif', required: true },
                { name: 'body', placeholder: 'Isi Pesan', required: true },
                { name: 'user_ids[]', placeholder: 'User ID (e.g. 1)', required: true },
                { name: 'link', placeholder: 'Optional Link' }
            ]
        },
        { 
            group: '8. Notifications', method: 'PATCH', url: '/api/v1/notifications/{id}/read', name: 'Mark One Read', auth: true,
            params: [{ name: 'id', placeholder: 'Notification ID', isUrl: true, required: true }]
        },
        { group: '8. Notifications', method: 'PATCH', url: '/api/v1/notifications/read-all', name: 'Mark All Read', auth: true },
        { 
            group: '8. Notifications', method: 'DELETE', url: '/api/v1/notifications/{id}', name: 'Delete Notification', auth: true,
            params: [{ name: 'id', placeholder: 'Notification ID', isUrl: true, required: true }]
        }
    ];

    let currentEndpoint = null;

    $(document).ready(function() {
        const savedToken = localStorage.getItem('api_bearer_token');
        if(savedToken) $('#apiToken').val(savedToken);

        renderEndpointList();

        $('#searchEndpoint').on('keyup', function() {
            renderEndpointList($(this).val().toLowerCase());
        });

        $('#apiTestForm').on('submit', function(e) {
            e.preventDefault();
            sendRequest();
        });
    });

    function saveToken(tokenToSave = null) {
        const token = tokenToSave || $('#apiToken').val();
        if(token) {
            localStorage.setItem('api_bearer_token', token);
            $('#apiToken').val(token);
            alert('Token saved to LocalStorage');
        }
    }

    function renderEndpointList(search = '') {
        const list = $('#endpointList');
        list.empty();
        let currentGroup = '';

        endpoints.forEach((ep, index) => {
            if(search && !ep.name.toLowerCase().includes(search) && !ep.group.toLowerCase().includes(search)) return;

            if(ep.group !== currentGroup) {
                list.append(`<div class="list-group-item bg-light fw-bold text-uppercase small mt-2 py-1">${ep.group}</div>`);
                currentGroup = ep.group;
            }

            let badgeClass = 'bg-secondary';
            if(ep.method === 'GET') badgeClass = 'bg-info';
            if(ep.method === 'POST') badgeClass = 'bg-success';
            if(ep.method === 'PUT' || ep.method === 'PATCH') badgeClass = 'bg-warning text-dark';
            if(ep.method === 'DELETE') badgeClass = 'bg-danger';

            const item = `
                <a href="javascript:;" class="list-group-item list-group-item-action py-2 px-3 border-start-0 border-end-0" onclick="selectEndpoint(${index})">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <small class="mb-0 fw-medium text-truncate" style="max-width: 180px;">${ep.name}</small>
                        <span class="badge ${badgeClass}" style="font-size: 9px; min-width: 40px;">${ep.method}</span>
                    </div>
                </a>
            `;
            list.append(item);
        });
    }

    function selectEndpoint(index) {
        currentEndpoint = endpoints[index];
        $('#selectedEndpointTitle').text(currentEndpoint.name);
        $('#selectedMethod').text(currentEndpoint.method);
        $('#selectedUrl').text(currentEndpoint.url);
        
        const methodBadge = $('#selectedMethod');
        methodBadge.removeClass('bg-info bg-success bg-warning bg-danger bg-secondary text-dark');
        if(currentEndpoint.method === 'GET') methodBadge.addClass('bg-info');
        else if(currentEndpoint.method === 'POST') methodBadge.addClass('bg-success');
        else if(currentEndpoint.method === 'PUT' || currentEndpoint.method === 'PATCH') methodBadge.addClass('bg-warning text-dark');
        else if(currentEndpoint.method === 'DELETE') methodBadge.addClass('bg-danger');

        $('#actionButtons').removeClass('d-none');
        
        const container = $('#dynamicInputs');
        container.empty();
        container.append('<h6 class="border-bottom pb-2 mb-3">Request Parameters</h6>');

        if(currentEndpoint.params && currentEndpoint.params.length > 0) {
            currentEndpoint.params.forEach(param => {
                const type = param.type || 'text';
                const required = param.required ? 'required' : '';
                const label = param.name + (param.isUrl ? ' <span class="badge bg-label-primary ms-1">URL</span>' : '');
                const value = param.value || '';
                
                const inputHtml = `
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">${label}</label>
                        <input type="${type}" id="param_${param.name}" name="${param.name}" 
                               class="form-control form-control-sm" placeholder="${param.placeholder || ''}" 
                               value="${value}" ${required} data-is-url="${param.isUrl || false}">
                    </div>
                `;
                container.append(inputHtml);
            });
        } else {
            container.append('<div class="alert alert-secondary mb-0 small">No parameters required.</div>');
        }
    }

    function sendRequest() {
        if(!currentEndpoint) return;
        $('#jsonResponse').text('Loading...');
        $('#responseStatus').text('Sending...');
        
        const startTime = new Date().getTime();
        const token = $('#apiToken').val();
        let finalUrl = currentEndpoint.url;
        let data = {};

        $('#dynamicInputs input').each(function() {
            let name = $(this).attr('name');
            const val = $(this).val();
            const isUrl = $(this).data('is-url');
            if(isUrl) finalUrl = finalUrl.replace(`{${name}}`, val);
            else if(val !== '') data[name] = val;
        });

        const ajaxConfig = {
            url: finalUrl,
            type: currentEndpoint.method,
            dataType: 'json',
            headers: { 'Accept': 'application/json' },
            success: function(response, status, xhr) {
                const duration = new Date().getTime() - startTime;
                $('#responseTime').text(duration + ' ms');
                $('#responseStatus').text(xhr.status + ' OK').removeClass().addClass('badge bg-success');
                $('#jsonResponse').text(JSON.stringify(response, null, 4));
                if (currentEndpoint.url.includes('/login') && response.data && response.data.token) {
                    saveToken(response.data.token);
                }
            },
            error: function(xhr) {
                const duration = new Date().getTime() - startTime;
                $('#responseTime').text(duration + ' ms');
                $('#responseStatus').text(xhr.status + ' ' + xhr.statusText).removeClass().addClass('badge bg-danger');
                try {
                    $('#jsonResponse').text(JSON.stringify(JSON.parse(xhr.responseText), null, 4));
                } catch(e) {
                    $('#jsonResponse').text(xhr.responseText);
                }
            }
        };

        if(currentEndpoint.auth && token) ajaxConfig.headers['Authorization'] = 'Bearer ' + token;
        if(currentEndpoint.method !== 'GET') ajaxConfig.data = data;
        else if(Object.keys(data).length > 0) ajaxConfig.url = finalUrl + (finalUrl.includes('?') ? '&' : '?') + $.param(data);

        $.ajax(ajaxConfig);
    }

    function clearConsole() {
        $('#jsonResponse').text('Ready...');
        $('#responseStatus').text('-');
        $('#responseTime').text('0 ms');
    }

    function copyResponse() {
        navigator.clipboard.writeText($('#jsonResponse').text());
        alert('Copied!');
    }
</script>
@endsection