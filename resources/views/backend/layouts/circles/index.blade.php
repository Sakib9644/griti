@extends('backend.app', ['title' => 'Circles'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center">
                    <h1 class="page-title">Circles</h1>
                    <a href="{{ route('admin.circles.create') }}" class="btn btn-primary">Add Circle</a>
                </div>

                <!-- Circles Table -->
                <div class="card">
                    <div class="card-body">


                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Video Title</th>
                                    <th>Title</th>
                                    <th>Thumbnail</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                     
                            <tbody>
                                @forelse($circles as $key => $circle)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $circle->video->title ?? '-' }}</td>
                                        <td>{{ $circle->title ?? '-' }}</td>
                                        <td>
                                            @if ($circle->image)
                                                <img src="{{ asset($circle->image) }}" width="60"
                                                    alt="Circle Thumbnail">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $circle->description ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.circles.edit', $circle->id) }}"
                                                class="btn btn-sm btn-info mb-1">Edit</a>

                                            <form action="{{ route('admin.circles.destroy', $circle->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this circle?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No circles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
