@extends('backend.app', ['title' => 'Workout Videos'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center mb-3">
                    <h1 class="page-title">Workout Videos</h1>
                    <a href="{{ route('admin.workout_videos.create') }}" class="btn btn-primary">Add Workout Video</a>
                </div>

                <!-- Workout Videos Table -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Workout Title</th>
                                    <th>Video Title</th>
                                    <th>Thumbnail</th>
                                    <th>Workout Video</th>
                                    <th>Duration (s)</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workoutVideos as $key => $workout)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $workout->video ? $workout->video->title : '-' }}</td>
                                        <td>{{ $workout->title }}</td>
                                        <td>
                                            @if ($workout->thumbnail && file_exists(public_path($workout->thumbnail)))
                                                <img src="{{ asset($workout->thumbnail) }}" width="60" class="rounded">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($workout->videos && file_exists(public_path($workout->videos)))
                                                <video width="200" controls>
                                                    <source src="{{ asset($workout->videos) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <span class="text-muted">No Video</span>
                                            @endif
                                        </td>

                                        <td>{{ $workout->seconds ?? '-' }}</td>
                                        <td>{!! $workout->descriptions !!} </td>
                                        <td>
                                            <a href="{{ route('admin.workout_videos.edit', $workout->id) }}"
                                                class="btn btn-sm btn-info mb-1">Edit</a>

                                            <form action="{{ route('admin.workout_videos.destroy', $workout->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this workout video?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No workout videos found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination (if using) -->
                        @if (method_exists($workoutVideos, 'links'))
                            <div class="mt-3">
                                {{ $workoutVideos->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
