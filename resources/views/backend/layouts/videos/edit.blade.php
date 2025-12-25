@extends('backend.app', ['title' => 'Update Video'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="page-title">Update Work-out Video</h1>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Back to List</a>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body border-0">
                            <form method="POST"
                                  action="{{ route('admin.videos.update', $video->id) }}"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Theme -->
                                <div class="form-group mb-3">
                                    <label>Theme</label>
                                    <select name="theme_id" class="form-control">
                                        <option value="">Select Theme</option>
                                        @foreach ($themes as $theme)
                                            <option value="{{ $theme->id }}"
                                                {{ old('theme_id', $video->theme_id) == $theme->id ? 'selected' : '' }}>
                                                {{ $theme->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="form-group mb-3">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $video->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Training Level -->
                                <div class="form-group mb-3">
                                    <label>Training Level</label>
                                    <select name="type" class="form-control">
                                        <option value="">Select Level</option>
                                        <option value="beginner" {{ old('type', $video->type) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('type', $video->type) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advance" {{ old('type', $video->type) == 'advance' ? 'selected' : '' }}>Advance</option>
                                    </select>
                                </div>

                                <!-- Title -->
                                <div class="form-group mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control"
                                           value="{{ old('title', $video->title) }}">
                                </div>

                                <!-- Calories -->
                                <div class="form-group mb-3">
                                    <label>Calories</label>
                                    <input type="number" name="calories" class="form-control"
                                           value="{{ old('calories', $video->calories) }}">
                                </div>

                                <!-- Minutes -->
                                <div class="form-group mb-3">
                                    <label>Minutes</label>
                                    <input type="number" name="minutes" class="form-control"
                                           value="{{ old('minutes', $video->minutes) }}">
                                </div>

                                <!-- Workout Videos (ORDER PRESERVED) -->
                                <div class="form-group mb-3">
                                    <label>Workout Videos</label>
                                    <select
                                        name="work_out_video_id[]"
                                        id="work_out_video_id"
                                        class="form-control select2"
                                        multiple>

                                        @php
                                            $selectedVideos = old(
                                                'work_out_video_id',
                                                $video->library->pluck('work_out_video_id')->toArray()
                                            );
                                        @endphp

                                        @foreach (App\Models\WorkoutVideos::all() as $workout)
                                            <option value="{{ $workout->id }}"
                                                {{ in_array($workout->id, $selectedVideos) ? 'selected' : '' }}>
                                                {{ $workout->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        Videos will be saved in the exact order you select.
                                    </small>
                                </div>

                                <!-- Thumbnail -->
                                <div class="form-group mb-3">
                                    <label>Thumbnail</label>
                                    <input type="file"
                                           name="image"
                                           class="dropify"
                                           data-default-file="{{ $video->image ? asset($video->image) : '' }}">
                                </div>

                                <!-- Submit -->
                                <div class="form-group mt-4">
                                    <button class="btn btn-primary">Update</button>
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
<!-- Dropify -->
<link href="https://cdn.jsdelivr.net/npm/dropify/dist/css/dropify.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {

        // Dropify
        $('.dropify').dropify();

        // Select2
        const $select = $('#work_out_video_id');

        $select.select2({
            closeOnSelect: false
        });

        // 🔒 Preserve exact selection order
        $select.on('select2:select', function (e) {
            const element = e.params.data.element;
            const $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger('change.select2');
        });

    });
</script>
@endpush
