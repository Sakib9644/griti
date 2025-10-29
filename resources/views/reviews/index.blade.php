@extends('backend.app', ['title' => 'Reviews'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header">
                    <div>
                        <h1 class="page-title">Reviews</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <a href="{{ route('reviews.create') }}" class="btn btn-primary btn-sm">Add Review</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Rating</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $review->title }}</td>
                                        <td>
                                            <div style="max-width: 900px; word-wrap: break-word; white-space: normal;">
                                                {{ $review->description }}
                                            </div>
                                        </td>
                                        <td>{{ $review->rating }}</td>
                                        <td>
                                            @if ($review->image)
                                                <img src="{{ asset($review->image) }}" width="50">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('reviews.edit', $review->id) }}"
                                                class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $reviews->links() }} <!-- Pagination links -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
