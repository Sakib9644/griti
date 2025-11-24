@extends('backend.app', ['title' => 'Videos'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <h1 class="page-title">Videos</h1>
                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary float-end">Add Video</a>
            </div>

            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered table-striped">
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($videos as $key => $video)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $video->title }}</td>
                                    <td>{{ $video->category ? $video->category->name : '-' }}</td>
                                    <td>{{ $video->theme ? $video->theme->name : '-' }}</td>
                                    <td>{{ $video->type ?? '-' }}</td>
                                    <td>{{ $video->calories ?? '-' }}</td>
                                    <td>{{ $video->minutes ?? '-' }}</td>
                                    <td>
                                        @if($video->image)
                                            <img src="{{ asset($video->image) }}" width="60">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this Work-Out?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No videos found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
