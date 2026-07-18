@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Logbook for Unit {{ strtoupper($unit->nama) }}</h1>

    <form action="{{ route('logbook.update', ['unit_id' => $unit->id, 'logbook_id' => $logbook->id]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label for="nameWithTitle">Judul</label>
            <input type="text" name="nameWithTitle" id="nameWithTitle" class="form-control" value="{{ old('nameWithTitle', $logbook->judul) }}" required>
            @error('nameWithTitle')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="radio_shift">Shift</label>
            <select name="radio_shift" id="radio_shift" class="form-control" required>
                <option value="1" {{ old('radio_shift', $logbook->shift) == '1' ? 'selected' : '' }}>Pagi</option>
                <option value="2" {{ old('radio_shift', $logbook->shift) == '2' ? 'selected' : '' }}>Siang</option>
                <option value="3" {{ old('radio_shift', $logbook->shift) == '3' ? 'selected' : '' }}>Malam</option>
            </select>
            @error('radio_shift')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="dateWithTitle">Tanggal Kegiatan</label>
            <input type="date" name="dateWithTitle" id="dateWithTitle" class="form-control" value="{{ old('dateWithTitle', $logbook->date) }}" required>
            @error('dateWithTitle')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Logbook</button>
            <a href="{{ route('logbook.index', $unit->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
