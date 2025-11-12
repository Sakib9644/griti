@extends('backend.app', ['title' => 'Update Video'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Videos</h1>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body border-0">
                            <form method="POST" action="{{ route('admin.videos.update', $video->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Theme -->
                                <div class="form-group">
                                    <label for="theme_id">Theme:</label>
                                    <select name="theme_id" class="form-control">
                                        @foreach($themes as $theme)
                                            <option value="{{ $theme->id }}" {{ old('theme_id', $video->theme_id) == $theme->id ? 'selected' : '' }}>
                                                {{ $theme->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('theme_id')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Video Title -->
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $video->title) }}">
                                    @error('title')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Calories -->
                                <div class="form-group">
                                    <label for="calories">Calories:</label>
                                    <input type="number" name="calories" class="form-control" value="{{ old('calories', $video->calories) }}">
                                    @error('calories')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Minutes -->
                                <div class="form-group">
                                    <label for="minutes">Minutes:</label>
                                    <input type="number" name="minutes" class="form-control" value="{{ old('minutes', $video->minutes) }}">
                                    @error('minutes')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Video File -->
                                <div class="form-group">
                                    <label for="video">Video File:</label>
                                    @if($video->video)
                                        <video width="250" controls>
                                            <source src="{{ asset($video->video) }}" type="video/mp4">
                                        </video>
                                    @endif
                                    <input type="file" name="video" class="form-control">
                                    @error('video')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Thumbnail -->
                                <div class="form-group">
                                    <label for="image">Thumbnail:</label>
                                    <input type="file" name="image" class="dropify form-control"
                                           data-default-file="{{ $video->image ? asset($video->image) : '' }}">
                                    @error('image')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea name="description" class="form-control">{{ old('description', $video->description) }}</textarea>
                                    @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <!-- Submit -->
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancel</a>
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
<script>$('.dropify').dropify();</script>
@endpush
