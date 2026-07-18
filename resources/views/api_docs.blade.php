<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarkdownUI - API Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root { 
            --sidebar-width: 280px; 
            --primary: #2563eb; 
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
        }
        
        body { background: var(--light-bg); font-family: 'Inter', sans-serif; color: #334155; overflow-x: hidden; }
        
        /* --- SIDEBAR --- */
        .sidebar { position: fixed; top:0; bottom:0; left:0; width: var(--sidebar-width); background: #fff; border-right: 1px solid var(--border-color); z-index: 1000; display: flex; flex-direction: column; }
        
        .brand-box { padding: 25px; border-bottom: 1px solid var(--border-color); background: #fff; }
        .brand-title { font-weight: 800; letter-spacing: -0.5px; color: var(--dark-bg); font-size: 1.2rem; display: flex; align-items: center; gap: 10px; }
        
        .sidebar-scroll { overflow-y: auto; flex: 1; padding: 20px 0; }
        
        .nav-group-btn { 
            width: 100%; text-align: left; background: none; border: none; 
            padding: 10px 25px; font-size: 0.75rem; font-weight: 700; 
            text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;
            display: flex; justify-content: space-between; align-items: center;
            transition: 0.2s;
        }
        .nav-group-btn:hover { color: var(--primary); background: #f8fafc; }
        .nav-group-btn[aria-expanded="true"] .ri-arrow-down-s-line { transform: rotate(180deg); }
        .nav-group-btn .ri-arrow-down-s-line { transition: transform 0.2s; font-size: 1rem; }

        .nav-item-link { 
            display: flex; align-items: center; /* Flex untuk merapikan badge dan text */
            padding: 8px 25px 8px 30px; 
            color: #475569; text-decoration: none; font-size: 0.85rem; 
            border-left: 3px solid transparent; transition: all 0.15s;
            cursor: pointer;
        }
        .nav-item-link:hover { background: #f1f5f9; color: var(--dark-bg); }
        .nav-item-link.active { background: #eff6ff; color: var(--primary); border-left-color: var(--primary); font-weight: 600; }
        
        .nav-summary-text {
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis;
            flex: 1; /* Mengisi sisa ruang */
        }

        .badge-mini { 
            font-size: 0.65rem; font-weight: 700; 
            display: inline-block; width: 45px; 
            font-family: 'JetBrains Mono', monospace; 
            flex-shrink: 0; /* Badge tidak boleh mengecil */
            margin-right: 8px;
        }
        .c-POST { color: #10b981; } .c-GET { color: #3b82f6; } .c-PUT { color: #f59e0b; } .c-DELETE { color: #ef4444; } .c-PATCH { color: #8b5cf6; }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: var(--sidebar-width); padding: 50px; max-width: 1100px; padding-bottom: 200px; }
        
        .header-section { margin-bottom: 40px; border-bottom: 1px solid var(--border-color); padding-bottom: 30px; }
        .header-desc { font-size: 1rem; color: #64748b; line-height: 1.6; max-width: 800px; margin-top: 15px; }
        
        .base-url-box { 
            background: #1e293b; color: #94a3b8; 
            padding: 15px 20px; border-radius: 8px; 
            font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;
            display: inline-flex; align-items: center; margin-top: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .base-url-val { color: #fff; margin-left: 10px; }

        /* GROUP SECTION */
        .group-section { margin-bottom: 60px; scroll-margin-top: 20px; }
        .group-title { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; display: flex; align-items: center; }
        .group-title i { margin-right: 10px; color: var(--primary); opacity: 0.7; }

        /* ENDPOINT CARD */
        .ep-card { 
            background: #fff; border: 1px solid var(--border-color); border-radius: 12px; margin-bottom: 20px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; 
            scroll-margin-top: 20px; 
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); 
        }
        .ep-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        
        /* HIGHLIGHT ANIMATION */
        .ep-card.highlight-target { 
            border-color: var(--primary); 
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2); 
            transform: scale(1.01);
        }

        .ep-header { padding: 15px 25px; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: background 0.2s; }
        .ep-header:hover { background: #f8fafc; }
        .ep-header[aria-expanded="true"] { background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .ep-header .toggle-icon { font-size: 1.2rem; color: #94a3b8; transition: transform 0.2s; }
        .ep-header[aria-expanded="true"] .toggle-icon { transform: rotate(180deg); color: var(--primary); }

        .ep-method { font-size: 0.8rem; font-weight: 700; padding: 6px 12px; border-radius: 6px; color: #fff; margin-right: 15px; font-family: 'JetBrains Mono', monospace; min-width: 70px; text-align: center;}
        .bg-POST { background: #10b981; } .bg-GET { background: #3b82f6; } .bg-PUT { background: #f59e0b; } .bg-DELETE { background: #ef4444; } .bg-PATCH { background: #8b5cf6; }
        
        .ep-path { font-family: 'JetBrains Mono', monospace; font-size: 0.95rem; color: #1e293b; font-weight: 600; margin-right: 15px; }
        .ep-summary { font-size: 0.9rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px; }

        /* Card Body */
        .ep-body { background: #fff; }
        .nav-tabs { padding: 0 20px; border-bottom: 1px solid var(--border-color); background: #fafafa; }
        .nav-tabs .nav-link { border: none; color: #64748b; font-size: 0.85rem; padding: 12px 20px; font-weight: 600; border-bottom: 2px solid transparent; }
        .nav-tabs .nav-link:hover { color: #334155; }
        .nav-tabs .nav-link.active { color: var(--primary); background: transparent; border-bottom-color: var(--primary); }
        .tab-content { padding: 25px; }
        .ep-desc { color: #475569; font-size: 0.9rem; margin-bottom: 25px; line-height: 1.6; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #e2e8f0; }

        /* Tables & Inputs */
        .param-table { width: 100%; font-size: 0.85rem; margin-bottom: 20px; border-collapse: separate; border-spacing: 0; }
        .param-table th { text-align: left; color: #94a3b8; padding: 10px; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; border-bottom: 1px solid var(--border-color); }
        .param-table td { padding: 12px 10px; border-bottom: 1px solid #f8fafc; vertical-align: top; }
        .req-dot { height: 6px; width: 6px; background-color: #ef4444; border-radius: 50%; display: inline-block; margin-right: 5px; margin-bottom: 2px; }
        .type-badge { font-size: 0.7rem; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #64748b; border: 1px solid #e2e8f0; font-family: 'JetBrains Mono', monospace; }

        .try-box { border: 1px dashed #cbd5e1; background: #f8fafc; padding: 25px; border-radius: 8px; display: none; margin-top: 20px; }
        .try-box.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .console-label { font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 5px; display: block; }
        .console-input { font-size: 0.85rem; border-color: #cbd5e1; border-radius: 6px; padding: 8px 12px; }
        .console-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

        .res-container { margin-top: 10px; }
        .res-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 0.8rem; }
        .res-status-badge { font-weight: 700; padding: 4px 10px; border-radius: 6px; }
        .res-body { background: #1e293b; color: #e2e8f0; padding: 20px; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; max-height: 500px; overflow-y: auto; border: 1px solid #334155; }
        .code-block { background: #f1f5f9; padding: 10px; border-radius: 6px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: #334155; border: 1px solid var(--border-color); word-break: break-all; }

        .toast-container { z-index: 9999; }
        
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand-box">
            <div class="brand-title">
                <i class="ri-markdown-fill text-primary" style="font-size: 1.5rem;"></i>
                MarkdownUI
            </div>
            <div class="small text-muted mt-1" style="font-size: 0.7rem; font-weight: 500;">API Documentation Viewer</div>
        </div>

        <div class="sidebar-scroll" id="sidebar-scroll">
            @foreach($spec['groups'] as $index => $group)
                @php $groupId = 'nav-group-' . $index; @endphp
                <button class="nav-group-btn" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}" aria-expanded="true">
                    {{ $group['name'] }}
                    <i class="ri-arrow-down-s-line"></i>
                </button>

                <div id="{{ $groupId }}" class="collapse show">
                    <div class="pb-2">
                    @foreach($group['routes'] as $route)
                        @php $id = Str::slug($route['method'] . '-' . $route['uri']); @endphp
                        
                        <div class="nav-item-link" data-target="#{{ $id }}" title="{{ $route['summary'] }}">
                            <span class="badge-mini c-{{ $route['method'] }}">{{ $route['method'] }}</span>
                            <!-- GANTI URL MENJADI SUMMARY -->
                            <span class="nav-summary-text">{{ $route['summary'] }}</span>
                        </div>
                    @endforeach
                    </div>
                </div>
                <div style="border-bottom: 1px solid #f1f5f9; margin: 5px 0;"></div>
            @endforeach
        </div>
    </div>

    <div class="main-content">
        
        <div class="header-section">
            <h1 class="fw-bold text-dark mb-2">MarkdownUI Documentation</h1>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill px-3">v1.0</span>
                <span class="badge bg-success rounded-pill px-3">Android</span>
            </div>
            
            <p class="header-desc">
                Dokumentasi teknis ini dirancang khusus untuk tim pengembang <strong>Aplikasi Mobile (Android)</strong>. 
                Gunakan endpoint di bawah ini untuk sinkronisasi data Logbook, Alat, Posisi, Manajemen User secara real-time.
            </p>

            <div class="base-url-box">
    @php $baseUrl = $spec['base_url']; @endphp
    <span class="fw-bold text-secondary text-uppercase" style="font-size: 0.75rem;">
        Base URL
    </span>

    <span class="base-url-val">{{ $baseUrl }}</span>

    <button
        type="button"
        class="btn btn-link btn-sm p-0 ms-3 text-secondary"
        title="Copy URL"
        onclick="navigator.clipboard.writeText('{{ $baseUrl }}')">
        <i class="ri-file-copy-line"></i>
    </button>
</div>
        </div>

        @foreach($spec['groups'] as $group)
            
            <div class="group-section">
                <div class="group-title">
                    <i class="ri-folder-open-fill"></i>
                    {{ $group['name'] }}
                </div>

                @foreach($group['routes'] as $route)
                    @php $id = Str::slug($route['method'] . '-' . $route['uri']); @endphp
                    
                    <div class="ep-card" id="{{ $id }}" data-method="{{ $route['method'] }}" data-uri="{{ $route['uri'] }}" data-auth="{{ $route['auth'] ? 'true' : 'false' }}">
                        
                        <div class="ep-header collapsed" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $id }}" aria-expanded="false">
                            <div class="d-flex align-items-center flex-wrap" style="flex: 1;">
                                <span class="ep-method bg-{{ $route['method'] }}">{{ $route['method'] }}</span>
                                <span class="ep-path">{{ $route['uri'] }}</span>
                                <span class="ep-summary d-none d-md-block">{{ $route['summary'] }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                @if($route['auth'])
                                    <span class="badge bg-secondary text-white border border-secondary" style="font-size:0.7rem;"><i class="ri-lock-fill me-1"></i>Auth</span>
                                @else
                                    <span class="badge bg-success text-white border border-success" style="font-size:0.7rem;"><i class="ri-global-line me-1"></i>Public</span>
                                @endif
                                <i class="ri-arrow-down-s-line toggle-icon"></i>
                            </div>
                        </div>

                        <div id="collapse-{{ $id }}" class="collapse ep-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-param-{{ $id }}">
                                        <i class="ri-equalizer-line me-1"></i> Parameters
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-res-{{ $id }}">
                                        <i class="ri-code-s-slash-line me-1"></i> Responses
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
								<div class="tab-pane fade show active" id="tab-param-{{ $id }}">
									<div class="ep-desc">
										<strong><i class="ri-information-fill me-1 text-primary"></i> Description:</strong><br>
										{{ $route['description'] }}
									</div>

									@if(count($route['params']) > 0)
										<h6 class="small fw-bold text-uppercase text-muted mb-3 d-flex align-items-center">
											<i class="ri-input-method-line me-2"></i> Input Variables
										</h6>
										<div class="table-responsive">
											<table class="param-table">
												<thead><tr><th width="25%">Field</th><th width="15%">Type</th><th>Description</th></tr></thead>
												<tbody>
													@foreach($route['params'] as $param)
														<tr>
															<td>
																<span class="fw-bold text-dark font-monospace">{{ $param['name'] }}</span>
																@if($param['req']) 
																	<div class="d-flex align-items-center mt-1">
																		<span class="req-dot"></span><span class="text-danger small fw-bold" style="font-size:0.7rem;">Required</span>
																	</div>
																@endif
															</td>
															<td>
																<span class="type-badge">{{ $param['type'] === 'file' ? 'FILE UPLOAD' : $param['type'] }}</span>
															</td>
															<td class="text-muted small">
																{{ $param['desc'] }}
																@if($param['type'] === 'file')
																	<span class="badge bg-secondary text-white ms-1" style="font-size: 0.6rem;">Format: JPG, PNG, JPEG</span>
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@else 
										<div class="alert alert-light border text-muted small d-flex align-items-center">
											<i class="ri-information-line me-2 fs-6"></i> Endpoint ini tidak memerlukan parameter input.
										</div>
									@endif

									<div class="mt-4 border-top pt-4">
										<button class="btn btn-primary btn-sm px-4 fw-bold" onclick="toggleTryOut(this)">
											<i class="ri-play-circle-line me-1"></i> Try it out
										</button>
									</div>

									<div class="try-box">
										<div class="d-flex justify-content-between align-items-center mb-4">
											<h6 class="small fw-bold text-uppercase text-primary m-0">
												<i class="ri-terminal-box-line me-1"></i> Request Console
											</h6>
											<button class="btn btn-close btn-sm" onclick="toggleTryOut(this.closest('.tab-pane').querySelector('.btn-primary'))"></button>
										</div>
										
										<form onsubmit="event.preventDefault(); executeRequest(this, '{{ $id }}');">
											@foreach($route['params'] as $param)
												<div class="mb-3">
													<label class="console-label">
														{{ $param['name'] }} 
														@if($param['type'] == 'url') <span class="badge bg-warning text-dark border ms-1" style="font-size:0.6rem;">URL PARAM</span> @endif
													</label>
													<input
														type="{{ $param['type'] == 'date' ? 'date' : ($param['type'] == 'number' ? 'number' : ($param['type'] == 'password' ? 'password' : (str_contains(strtolower($param['type']), 'file') ? 'file' : 'text'))) }}" 
														
														name="{{ $param['name'] }}" 
														class="form-control console-input"
														data-param-type="{{ $param['type'] }}"
														
														@if(!str_contains(strtolower($param['type']), 'file'))
															placeholder="Enter {{ $param['name'] }}..."
														@endif
														
														{{ $param['req'] ? 'required' : '' }}

														@if(str_contains(strtolower($param['type']), 'file'))
@php
												$fileType = $param['type'];
												$acceptExtensions = null;

												if (preg_match('/\((.*)\)/', $fileType, $matches)) {
													$extString = $matches[1] ?? '';
													$acceptExtensions = $extString !== '' ? '.' . str_replace(',', ',.', strtolower($extString)) : null;
												}
											@endphp
															
															{{-- Terapkan accept attribute hanya jika ekstensi spesifik ditemukan --}}
															@if ($acceptExtensions !== null)
																accept="{{ $acceptExtensions }}"
															@endif
														@endif
													>
												</div>
											@endforeach
											
											<div class="text-end mt-4">
												<button type="submit" class="btn btn-dark btn-sm px-4 fw-bold shadow-sm">
													Execute Request <i class="ri-send-plane-fill ms-1"></i>
												</button>
											</div>
										</form>
									</div>
								</div>

								<div class="tab-pane fade" id="tab-res-{{ $id }}">
									<div class="empty-state text-center py-5 text-muted">
										<i class="ri-rocket-2-line" style="font-size: 3rem; opacity: 0.3;"></i>
										<p class="small fw-bold mt-3">Ready to launch request.</p>
										<p class="small" style="opacity: 0.7;">Klik tombol "Try it out" di tab Parameters untuk memulai.</p>
									</div>

									<div class="response-container d-none">
										<div class="mb-4">
											<label class="res-meta text-muted fw-bold">REQUEST URL</label>
											<div class="code-block res-url"></div>
										</div>

										<div>
											<div class="res-meta">
												<span class="text-muted fw-bold">SERVER RESPONSE</span>
												<div>
													<span class="badge bg-light text-dark border me-2">application/json</span>
													<span class="res-status-badge"></span>
													<span class="res-time badge bg-dark text-white ms-2"></span>
												</div>
											</div>
											
											<pre class="res-body shadow-sm">Waiting...</pre>
										</div>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="authToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="ri-error-warning-fill fs-5 me-2"></i>
                    <div>
                        <strong>Unauthenticated</strong><br>
                        Anda harus login terlebih dahulu.
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="ri-check-double-line fs-5 me-2"></i>
                    <div>
                        <strong>Success</strong><br>
                        Token berhasil disimpan!
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>

        <div id="logoutToast" class="toast align-items-center text-white bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="ri-logout-box-r-line fs-5 me-2"></i>
                    <div>
                        <strong>Logged Out</strong><br>
                        Token berhasil dihapus dari sesi.
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const baseUrl = "{{ $spec['base_url'] }}";
        let isManualScroll = false;

        // --- 1. HANDLE SIDEBAR CLICK ---
        $('.nav-item-link').on('click', function() {
            isManualScroll = true;

            $('.nav-item-link').removeClass('active');
            $(this).addClass('active');

            const targetId = $(this).data('target');
            const targetCard = $(targetId);

            if(targetCard.length) {
                $('.ep-body').collapse('hide');
                $('.ep-header').attr('aria-expanded', 'false').addClass('collapsed');
                
                const targetCollapse = targetCard.find('.ep-body');
                const targetHeader = targetCard.find('.ep-header');
                
                targetCollapse.collapse('show');
                targetHeader.attr('aria-expanded', 'true').removeClass('collapsed');

                $('.ep-card').removeClass('highlight-target');
                targetCard.addClass('highlight-target');
                setTimeout(() => targetCard.removeClass('highlight-target'), 2000);

                setTimeout(() => {
                    const offsetTop = targetCard.offset().top - 20; 
                    $('html, body').animate({ scrollTop: offsetTop }, 500, function() {
                        isManualScroll = false;
                    });
                }, 100);
            }
        });

        // --- 2. SCROLL SPY ---
        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            if (isManualScroll) return; 

            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id;
                    $('.nav-item-link').removeClass('active');
                    $(`.nav-item-link[data-target="#${id}"]`).addClass('active');
                    
                    const navItem = $(`.nav-item-link[data-target="#${id}"]`);
                    const parentGroup = navItem.closest('.collapse');
                    if(parentGroup.length && !parentGroup.hasClass('show')) {
                        parentGroup.collapse('show');
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.ep-card').forEach((section) => {
            observer.observe(section);
        });

        // --- 3. EXISTING FUNCTIONALITY ---

        function toggleTryOut(btn) {
            const container = $(btn).closest('.tab-pane').find('.try-box');
            if(container.is(':visible')) {
                container.slideUp();
                $(btn).removeClass('d-none');
            } else {
                container.slideDown().addClass('active');
                $(btn).addClass('d-none');
            }
        }

        function showToast(id) {
            const toastEl = document.getElementById(id);
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        function executeRequest(form, id) {
			const card = $(`#${id}`);
			const method = card.data('method');
			let uri = card.data('uri');
			const isAuth = card.data('auth');
			
			let bodyData = {};
			let isMultipart = false;
			let ajaxMethod = method; 

			$(form).find('input').each(function() {
				const input = $(this);
				const name = input.attr('name');
				const val = input.val();
				const type = input.data('param-type');

				if(input.attr('type') === 'file') {
					isMultipart = true;
				}

				if(type === 'url') {
					uri = uri.replace(`{${name}}`, val);
				} else if (input.attr('type') !== 'file') {
					if(val) bodyData[name] = val;
				}
			});
			
			uri = uri.replace(/\{.*?\}/g, '');
			let finalUrl = baseUrl + uri;
			
			if(method === 'GET' && Object.keys(bodyData).length > 0) {
				finalUrl += '?' + $.param(bodyData);
				bodyData = null; 
			} else if (method !== 'GET' && !isMultipart) {
				// Data non-GET, non-file akan tetap di bodyData
			}

			let headers = { "Accept": "application/json" };
			
			if(isAuth) {
				const token = localStorage.getItem('api_bearer_token');
				if(!token) {
					showToast('authToast');
					return; 
				}
				headers["Authorization"] = "Bearer " + token;
			}

			const tabRes = $(`#tab-res-${id}`);
			const bsTab = new bootstrap.Tab(document.querySelector(`[data-bs-target="#tab-res-${id}"]`));
			bsTab.show();

			const resContainer = tabRes.find('.response-container');
			const emptyState = tabRes.find('.empty-state');
			const output = resContainer.find('.res-body');
			const statusBadge = resContainer.find('.res-status-badge');
			const urlDisplay = resContainer.find('.res-url');
			
			emptyState.addClass('d-none');
			resContainer.removeClass('d-none');
			
			setTimeout(() => {
				const cardTop = card.offset().top - 20; 
				$('html, body').animate({ scrollTop: cardTop }, 300);
			}, 100);

			urlDisplay.text(`${method} ${finalUrl}`);
			output.text('Sending request...');
			statusBadge.text('Loading...').attr('class', 'res-status-badge badge bg-warning text-dark');
			
			const startTime = performance.now();

			let ajaxOptions = {
				url: finalUrl,
				method: ajaxMethod,
				headers: headers,
				dataType: 'json',
				success: function(res, textStatus, xhr) {
					const duration = (performance.now() - startTime).toFixed(0);
					statusBadge.text(`${xhr.status} ${xhr.statusText}`).attr('class', 'res-status-badge badge bg-success text-white');
					resContainer.find('.res-time').text(duration + ' ms');
					output.text(JSON.stringify(res, null, 2));

					if(uri.includes('/login') && res.data && res.data.token) {
						localStorage.setItem('api_bearer_token', res.data.token);
						showToast('successToast');
					}

					if(uri.includes('/logout')) {
						localStorage.removeItem('api_bearer_token');
						showToast('logoutToast');
					}
				},
				error: function(xhr) {
					const duration = (performance.now() - startTime).toFixed(0);
					statusBadge.text(`${xhr.status} Error`).attr('class', 'res-status-badge badge bg-danger text-white');
					resContainer.find('.res-time').text(duration + ' ms');
					
					if(xhr.status === 401) {
						showToast('authToast');
					}

					let msg = "";
					try {
						msg = JSON.stringify(JSON.parse(xhr.responseText), null, 2);
					} catch(e) {
						msg = xhr.responseText || xhr.statusText;
					}
					output.text(msg);
				}
			};
			
			if (method !== 'GET') {
				if (isMultipart) {
					ajaxOptions.data = new FormData(form);
					ajaxOptions.processData = false; 
					ajaxOptions.contentType = false;
				} else {
					ajaxOptions.data = bodyData;
				}
			}

			$.ajax(ajaxOptions);
		}
	</script>
</body>
</html>