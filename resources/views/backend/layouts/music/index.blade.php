@extends('backend.app', ['title' => 'Music List'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Music List</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.workout_videos.index') }}">Workout Videos</a>
                        </li>
                        <li class="breadcrumb-item active">Music List</li>
                    </ol>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-options">
                                    <a href="{{ route('admin.music.create') }}" class="btn btn-primary btn-sm">Add New
                                        Music</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Video Title</th>
                                                <th>Title</th>
                                                <th>Duration</th>
                                                <th>Music File</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($music as $index => $m)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $m->workoutlist->title ?? 'N/A' }}</td>
                                                    <td>{{ $m->title ?? 'N/A' }}</td>
                                                    <td>{{ $m->duration ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($m->music_file && file_exists(public_path($m->music_file)))
                                                            <audio controls style="width: 200px;">
                                                                <source src="{{ asset($m->music_file) }}" type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        @else
                                                            <span class="text-muted">No File</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.music.edit', $m->id) }}"
                                                            class="btn btn-sm btn-warning">Edit</a>
                                                        <form action="{{ route('admin.music.destroy', $m->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Are you sure you want to delete this music?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No music found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination links -->
                                    <div class="d-flex justify-content-center">
                                        {{ $music->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
