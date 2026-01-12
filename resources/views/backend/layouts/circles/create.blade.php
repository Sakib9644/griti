@extends('backend.app', ['title' => 'Create Circle'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Create Circle</h1>
                <a href="{{ route('admin.circles.index') }}" class="btn btn-secondary float-end">Back</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.circles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Video Selection -->
                        <div class="form-group mb-3">
                            <label for="video_id">Video:</label>
                            <select name="video_id" id="video_id" class="form-control @error('video_id') is-invalid @enderror">
                                <option value="">Select Video</option>
                                @foreach($videos as $video)
                                    <option value="{{ $video->id }}" {{ old('video_id') == $video->id ? 'selected' : '' }}>
                                        {{ $video->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('video_id')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Name -->

                        <!-- Title -->
                        <div class="form-group mb-3">
                            <label for="title">Title:</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-3">
                            <label for="description">Description:</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                   
                        <!-- Image -->
                        <div class="form-group mb-3">
                            <label for="image">Thumbnail Image:</label>
                            <input type="file" name="image" class="dropify form-control">
                            @error('image')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('admin.circles.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
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
