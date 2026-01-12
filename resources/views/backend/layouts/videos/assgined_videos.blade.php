@extends('backend.app', ['title' => 'Assigned Videos'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="page-title">Assigned Videos for:</h1>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Back to List</a>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    @if($video && $video->count())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Video Title</th>
                                    <th>Thumbnail</th>
                                    <th>Video</th>
                                    <th>Seconds</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($video as $key => $workout)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $workout->workoutVideo->title }}</td>
                                        <td>
                                            @if ($workout->workoutVideo->thumbnail)
                                                <img src="{{ asset($workout->workoutVideo->thumbnail) }}" width="60" alt="Thumbnail">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($workout->workoutVideo->videos)
                                                <video width="200" controls>
                                                    <source src="{{ asset($workout->workoutVideo->videos) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <span class="text-muted">No Video</span>
                                            @endif
                                        </td>
                                        <td>{{ $workout->workoutVideo->seconds ?? '-' }}</td>
                                        <td>{!! $workout->workoutVideo->descriptions ?? '-' !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center">No assigned videos found.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
