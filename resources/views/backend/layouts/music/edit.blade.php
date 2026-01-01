@extends('backend.app', ['title' => 'Edit Music'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Edit Music</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.music.index', $music->workout_videos_id) }}">Music</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body border-0">
                            <form method="POST" action="{{ route('admin.music.update', $music->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Workout Video Relation (readonly or dropdown if needed) -->


                                <!-- Music Title -->
                                <div class="form-group mb-3">
                                    <label for="title">Music Title (Optional):</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $music->title) }}">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div class="form-group mb-3">
                                    <label for="duration">Duration (Optional, e.g., 2:30):</label>
                                    <input type="text" name="duration" class="form-control" value="{{ old('duration', $music->duration) }}">
                                    @error('duration')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Current Music Player -->
                                @if($music->music_file && file_exists(public_path($music->music_file)))
                                <div class="form-group mb-3">
                                    <label>Current Music:</label><br>
                                    <audio controls style="width: 100%;">
                                        <source src="{{ asset($music->music_file) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                                @endif

                                <!-- Upload New Music File -->
                                <div class="form-group mb-3">
                                    <label for="music_file">Replace Music File (Optional):</label>
                                    <input type="file" name="music_file" class="dropify form-control">
                                    @error('music_file')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('admin.music.index', $music->workout_videos_id) }}" class="btn btn-secondary">Cancel</a>
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
