@extends('backend.app', ['title' => 'Create Video'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Workout List</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Add Workout-list</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body border-0">
                            <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">
                                    <!-- Theme -->
                                    <div class="form-group">
                                        <label for="theme_id" class="form-label">Theme:</label>
                                        <select name="theme_id" id="theme_id"
                                            class="form-control @error('theme_id') is-invalid @enderror">
                                            <option value="">Select Theme</option>
                                            @foreach ($themes as $theme)
                                                <option value="{{ $theme->id }}"
                                                    {{ old('theme_id') == $theme->id ? 'selected' : '' }}>
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
                                        <label for="category_id" class="form-label">Category:</label>
                                        <select name="category_id" id="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Training Level -->
                                    <div class="form-group">
                                        <label for="type" class="form-label">Training Level:</label>
                                        <select name="type" id="type"
                                            class="form-control @error('type') is-invalid @enderror">
                                            <option value="">Select Level</option>
                                            <option value="beginner" {{ old('type') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="intermediate" {{ old('type') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="advance" {{ old('type') == 'advance' ? 'selected' : '' }}>Advance</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                          <div class="form-group">
                                        <label for="work_out_video_id">Select Videos:</label>
                                        <select name="work_out_video_id[]" id="work_out_video_id" multiple
                                            class="form-control select3 @error('work_out_video_id') is-invalid @enderror">
                                            @foreach(App\Models\WorkoutVideos::all() as $workout)
                                                <option value="{{ $workout->id }}"
                                                   >
                                                    {{ $workout->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('work_out_video_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple videos.</small>
                                    </div>

                                    <!-- Video Title -->
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Calories -->
                                    <div class="form-group">
                                        <label for="calories">Calories:</label>
                                        <input type="number" name="calories" class="form-control" value="{{ old('calories') }}">
                                        @error('calories')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Minutes -->
                                    <div class="form-group">
                                        <label for="minutes">Minutes:</label>
                                        <input type="number" name="minutes" class="form-control" value="{{ old('minutes') }}">
                                        @error('minutes')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Workout List Videos (Multi-select) -->


                                    <!-- Thumbnail -->
                                    <div class="form-group">
                                        <label for="image">Thumbnail:</label>
                                        <input type="file" name="image" class="dropify form-control">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Submit -->
                                    <div class="form-group mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
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
<script>
    $(document).ready(function () {
        const $select = $('#work_out_video_id');

        $select.select2({
            closeOnSelect: true
        });

        // ✅ KEEP EXACT SELECTION ORDER
        $select.on('select2:select', function (e) {
            const element = e.params.data.element;
            const $element = $(element);

            // move selected option to the end
            $element.detach();
            $(this).append($element);

            // refresh Select2
            $(this).trigger('change.select2');
        });
    });
</script>
@endpush

