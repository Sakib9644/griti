@extends('backend.app', ['title' => 'Add Workout List'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="page-title">Workout List</h1>
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">Add Workout List</a>
                </div>

                <!-- Videos Table -->
                <div class="card mt-3">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Theme</th>
                                    <th>Training Level</th>
                                    <th>Calories</th>
                                    <th>Minutes</th>
                                    <th>Thumbnail</th>
                                    <th>Added Videos</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($videos as $key => $video)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $video->title }}</td>
                                        <td>{{ $video->category?->name ?? '-' }}</td>
                                        <td>{{ $video->theme?->name ?? '-' }}</td>
                                        <td>{{ $video->type ?? '-' }}</td>
                                        <td>{{ $video->calories ?? '-' }}</td>
                                        <td>{{ $video->minutes ?? '-' }}</td>
                                        <td>
                                            @if ($video->image)
                                                <img src="{{ asset($video->image) }}" width="60" alt="Thumbnail">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($video->library && $video->library->count())
                                                <a href="{{ route('admin.videos.assigned', $video->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    Show Assigned Videos
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.videos.edit', $video->id) }}"
                                                class="btn btn-sm btn-info mb-1">Edit</a>
                                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this Workout?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No videos found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $videos->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
