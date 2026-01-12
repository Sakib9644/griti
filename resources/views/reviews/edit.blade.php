@extends('backend.app', ['title' => 'Edit Review'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Edit Review</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $review->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required>{{ $review->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label>Rating (1-5)</label>
                            <input type="number" name="rating" class="form-control" value="{{ $review->rating }}" min="1" max="5" required>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                            @if($review->image)
                                <img src="{{ asset($review->image) }}" width="100" class="mt-2">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
