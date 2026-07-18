@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Logbook for Unit {{ strtoupper($unit->nama) }}</h1>

    <form action="{{ route('logbook.store', $unit->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nameWithTitle">Judul</label>
            <input type="text" name="nameWithTitle" id="nameWithTitle" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="radio_shift">Shift</label>
            <select name="radio_shift" id="radio_shift" class="form-control" required>
                <option value="1">Pagi</option>
                <option value="2">Siang</option>
                <option value="3">Malam</option>
            </select>
        </div>

        <div class="form-group">
            <label for="dateWithTitle">Tanggal Kegiatan</label>
            <input type="date" name="dateWithTitle" id="dateWithTitle" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
@endsection