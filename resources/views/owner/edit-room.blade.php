@extends('layouts.owner.index-owner')

@section('content')
<div class="container py-4">
    <h4>Edit Room</h4>
    <form>
        {{-- Form edit kamar di sini --}}
        <div class="mb-3">
            <label for="roomName" class="form-label">Room Name</label>
            <input type="text" class="form-control" id="roomName" name="room_name" value="{{ $room->name ?? '' }}">
        </div>
        <!-- Tambah form lain sesuai kebutuhan -->
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
