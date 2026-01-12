@extends('backend.app', ['title' => 'Add Review'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Add Review</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Rating (1-5)</label>
                            <input type="number" name="rating" class="form-control" min="1" max="5" required>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
