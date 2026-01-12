@extends('backend.app', ['title' => 'Create Theme'])

@section('content')

<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Themes</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Themes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body border-0">
                            <form class="form form-horizontal" method="POST" action="{{ route('admin.theme.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4">

                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name" class="form-label">Theme Name:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" placeholder="Theme Name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Category -->
                               

                                    <!-- Type -->


                                    <!-- Image -->
                                    <div class="form-group">
                                        <label for="image" class="form-label">Image:</label>
                                        <input type="file" class="dropify form-control @error('image') is-invalid @enderror" name="image" id="image">
                                        <p class="text-muted">Image Size < 5MB and type must be jpeg, jpg, png.</p>
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Submit -->
                                    <div class="form-group mt-3">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <a href="{{ route('admin.theme.index') }}" class="btn btn-secondary">Cancel</a>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#category_id').select2({ placeholder: "Select a category", allowClear: true });
        $('#type').select2({ placeholder: "Select type", allowClear: true });
    });
</script>
@endpush
