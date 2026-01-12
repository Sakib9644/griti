@extends('backend.app', ['title' => 'Upload Music'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Music</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.music.index', $workoutVideoId ?? 0) }}">Music</a></li>
                    <li class="breadcrumb-item active">Upload</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body border-0">
                            <form method="POST" action="{{ route('admin.music.store') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Music Title -->
                                <div class="form-group mb-3">
                                    <label for="title">Music Title (Optional):</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div class="form-group mb-3">
                                    <label for="duration">Duration (Optional, e.g., 2:30):</label>
                                    <input type="text" name="duration" class="form-control" value="{{ old('duration') }}">
                                    @error('duration')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Music File Upload -->
                                <div class="form-group mb-3">
                                    <label for="music_file">Upload Music File:</label>
                                    <input type="file" name="music_file" class="dropify form-control" accept="audio/mpeg">
                                    @error('music_file')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                    <a href="{{ route('admin.music.index', $workoutVideoId ?? 0) }}" class="btn btn-secondary">Cancel</a>
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
