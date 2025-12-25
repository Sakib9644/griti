@extends('backend.app', ['title' => 'Edit Workout Video'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Edit Workout Video</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.workout_videos.index') }}">Workout Videos</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body border-0">
                                <form method="POST" action="{{ route('admin.workout_videos.update', $workoutVideo->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Video Relation -->
                                
                                    <!-- Workout Video Title -->
                                    <div class="form-group mb-3">
                                        <label for="title">Workout Video Title:</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', $workoutVideo->title) }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="form-group mb-3">
                                        <label for="thumbnail">Thumbnail:</label>
                                        <input type="file" name="thumbnail" class="dropify form-control"
                                            data-default-file="{{ $workoutVideo->thumbnail ? asset($workoutVideo->thumbnail) : '' }}">
                                        @error('thumbnail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                  <textarea name="descriptions" class="summernote form-control" rows="15">{{ old('descriptions', $workoutVideo->descriptions) }}</textarea>

                                    <!-- Workout Video File -->
                                    <div class="form-group mb-3">
                                        <label for="videos">Workout Video File:</label>
                                        @if ($workoutVideo->videos)
                                            <div class="mb-2">
                                                <video width="320" height="240" controls>
                                                    <source src="{{ asset($workoutVideo->videos) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        @endif
                                        <input type="file" name="videos" class="form-control">
                                        @error('videos')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Duration in Seconds -->
                                    <div class="form-group mb-3">
                                        <label for="seconds">Duration (Seconds):</label>
                                        <input type="number" name="seconds" class="form-control"
                                            value="{{ old('seconds', $workoutVideo->seconds) }}">
                                        @error('seconds')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Description -->


                                    <!-- Submit -->
                                    <div class="form-group mt-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('admin.workout_videos.index') }}"
                                            class="btn btn-secondary">Cancel</a>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/dropify/dist/css/dropify.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
    <script>
        $('.dropify').dropify();
    </script>
@endpush
