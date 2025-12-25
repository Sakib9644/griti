@extends('backend.app', ['title' => 'Create Workout Video'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Workout Videos</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.workout_videos.index') }}">Workout Videos</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body border-0">
                                <form method="POST" action="{{ route('admin.workout_videos.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <!-- Video Relation -->
                                

                                    <!-- Workout Video Title -->
                                    <div class="form-group mb-3">
                                        <label for="title">Workout Video Title:</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title') }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="form-group mb-3">
                                        <label for="thumbnail">Thumbnail:</label>
                                        <input type="file" name="thumbnail" class="dropify form-control">
                                        @error('thumbnail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="descriptions">Description:</label>
                                        <textarea name="descriptions" class="summernote form-control" rows="4">{{ old('descriptions') }}</textarea>
                                        @error('descriptions')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <!-- Workout Video File -->
                                    <div class="form-group mb-3">
                                        <label for="videos">Workout Video File:</label>
                                        <input type="file" name="videos" class="form-control">
                                        @error('videos')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Duration in Seconds -->
                                    <div class="form-group mb-3">
                                        <label for="seconds">Duration (Seconds):</label>
                                        <input type="number" name="seconds" class="form-control"
                                            value="{{ old('seconds') }}">
                                        @error('seconds')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Description -->


                                    <!-- Submit -->
                                    <div class="form-group mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
