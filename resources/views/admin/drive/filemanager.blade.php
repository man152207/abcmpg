@extends('admin.layout.layout')

@section('content')
    <h2>📂 Google Drive Files</h2>
    <ul>
        @forelse($files as $file)
            <li>{{ $file }}</li>
        @empty
            <li>No files found.</li>
        @endforelse
    </ul>
@endsection
