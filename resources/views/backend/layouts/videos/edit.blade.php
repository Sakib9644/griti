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
                                <form method="POST" action="{{ route('admin.videos.update', $video->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Theme -->
                                    <!-- Theme -->
                                    <div class="form-group">
                                        <label for="theme_id">Theme:</label>
                                        <select name="theme_id"
                                            class="form-control @error('theme_id') is-invalid @enderror">
                                            @foreach ($themes as $theme)
                                                <option value="{{ $theme->id }}"
                                                    {{ old('theme_id', $video->theme_id) == $theme->id ? 'selected' : '' }}>
                                                    {{ $theme->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('theme_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Category -->
                                    <div class="form-group">
                                        <label for="category_id">Category:</label>
                                        <select name="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Training Level (Type) -->
                                    <div class="form-group">
                                        <label for="type">Training Level:</label>
                                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                                            <option value="">Select Level</option>
                                            <option value="beginner"
                                                {{ old('type', $video->type) == 'beginner' ? 'selected' : '' }}>Beginner
                                            </option>
                                            <option value="intermediate"
                                                {{ old('type', $video->type) == 'intermediate' ? 'selected' : '' }}>
                                                Intermediate</option>
                                            <option value="advance"
                                                {{ old('type', $video->type) == 'advance' ? 'selected' : '' }}>Advance
                                            </option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <!-- Video Title -->
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title', $video->title) }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Calories -->
                                    <div class="form-group">
                                        <label for="calories">Calories:</label>
                                        <input type="number" name="calories" class="form-control"
                                            value="{{ old('calories', $video->calories) }}">
                                        @error('calories')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Minutes -->
                                    <div class="form-group">
                                        <label for="minutes">Minutes:</label>
                                        <input type="number" name="minutes" class="form-control"
                                            value="{{ old('minutes', $video->minutes) }}">
                                        @error('minutes')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Video File -->


                                    <!-- Thumbnail -->
                                    <div class="form-group">
                                        <label for="image">Thumbnail:</label>
                                        <input type="file" name="image" class="dropify form-control"
                                            data-default-file="{{ $video->image ? asset($video->image) : '' }}">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Description -->

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
    <script>
        $('.dropify').dropify();
    </script>
@endpush
