@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-0 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Logbook /</span> Edit Content
        </h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Konten Logbook</h5>
                <small class="text-muted">Logbook: {{ $logbook->judul }} | {{ \Carbon\Carbon::parse($logbook->date)->format('d F Y') }}</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('logbook.item.update', ['unit_id' => $unit_id, 'logbook_id' => $logbook->id, 'item_id' => $logbookItem->id]) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="catatan" class="form-label">Catatan Kegiatan <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="4" placeholder="Masukkan detail kegiatan..." required>{{ old('catatan', $logbookItem->catatan) }}</textarea>
                            @error('catatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" class="form-control" value="{{ old('tanggal_kegiatan', $logbookItem->tanggal_kegiatan) }}" required>
                            @error('tanggal_kegiatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tools" class="form-label">Tools/Alat <span class="text-danger">*</span></label>
                            <select name="tools" id="tools" class="form-select" required>
                                <option value="">Pilih Alat</option>
                                @foreach(\App\Models\Tool::all() as $tool)
                                    <option value="{{ $tool->name }}" {{ old('tools', $logbookItem->tools) == $tool->name ? 'selected' : '' }}>
                                        {{ $tool->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tools')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="teknisi" class="form-label">Teknisi <span class="text-danger">*</span></label>
                            <select name="teknisi" id="teknisi" class="form-select" required>
                                <option value="">Pilih Teknisi</option>
                                @foreach(\App\Models\User::where('technician', 1)->get() as $user)
                                    <option value="{{ $user->id }}" {{ old('teknisi', $logbookItem->teknisi) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teknisi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="mulai" id="mulai" class="form-control" value="{{ old('mulai', \Carbon\Carbon::parse($logbookItem->mulai)->format('Y-m-d\TH:i')) }}" required>
                            @error('mulai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="selesai" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="selesai" id="selesai" class="form-control" value="{{ old('selesai', \Carbon\Carbon::parse($logbookItem->selesai)->format('Y-m-d\TH:i')) }}" required>
                            @error('selesai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Update Item
                        </button>
                        <a href="{{ route('logbook.view', ['unit_id' => $unit_id, 'logbook_id' => $logbook->id]) }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
