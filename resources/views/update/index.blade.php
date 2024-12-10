@extends('template.app')
@section('title', 'Update Aplikasi')

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')

    <div class="card mb-4">
        <div class="card-body">
            <h2>Update Aplikasi</h2>
            <p>Unggah file update (format ZIP) untuk memperbarui aplikasi Anda.</p>

            <form action="{{ route('update.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="update_file" class="form-label">Pilih File Update (.zip):</label>
        <input type="file" name="update_file" id="update_file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-upload"></i> Unggah File
    </button>
</form>

<form action="{{ route('update.run') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success mt-3">
        <i class="bx bx-refresh"></i> Jalankan Update
    </button>
</form>

 <!-- Menampilkan Daftar File ZIP yang Ada di Folder 'updates' -->
 @if(count($zipFiles) > 0)
                <h4 class="mt-4">File Update yang Tersedia</h4>
                <ul>
                    @foreach($zipFiles as $file)
                        <li>{{ basename($file) }}</li> <!-- Menampilkan nama file ZIP -->
                    @endforeach
                </ul>
            @else
                <p>Tidak ada file update yang tersedia di folder 'updates'.</p>
            @endif


        </div>
    </div>
</div>
@endsection
