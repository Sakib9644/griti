@extends('backend.app', ['title' => 'Themes'])

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
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Theme List</h3>
                    <div class="card-options ms-auto">
                        <a href="{{ route('admin.theme.create') }}" class="btn btn-primary btn-sm">Add Theme</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($themes as $key => $theme)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $theme->category->name ?? '-' }}</td>
                                <td>{{ $theme->name }}</td>
                                <td>{{ ucfirst($theme->type) }}</td>
                                <td>
                                    @if($theme->image)
                                        <img src="{{ asset($theme->image) }}" alt="Theme Image" width="60">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.theme.edit', $theme->id) }}" class="btn btn-sm btn-info">Edit</a>

                                    <form action="{{ route('admin.theme.destroy', $theme->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this theme?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No themes found.</td>
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
