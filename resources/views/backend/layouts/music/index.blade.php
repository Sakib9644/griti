@extends('backend.app', ['title' => 'Manage Music'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Music Library</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.music.index') }}">Music</a></li>
                    <li class="breadcrumb-item active">Manage</li>
                </ol>
                <div class="mt-2">
                    <a href="{{ route('admin.music.create') }}" class="btn btn-success">Upload New Music</a>
                </div>
            </div>

            <!-- Music Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body border-0">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Duration</th>
                                        <th>File</th>
                                        <th>Default</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($music as $m)
                                    <tr>
                                        <td>{{ $loop->iteration + ($music->currentPage()-1)*$music->perPage() }}</td>
                                        <td>{{ $m->title ?? '-' }}</td>
                                        <td>{{ $m->duration ?? '-' }}</td>
                                        <td>
                                            @if($m->music_file)
                                                <audio controls style="width: 150px;">
                                                    <source src="{{ asset($m->music_file) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($m->is_default)
                                                <span class="badge bg-success">Default</span>
                                            @else
                                                <form method="POST" action="{{ route('admin.music.default', $m->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Set as Default</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.music.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('admin.music.destroy', $m->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this music?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No music uploaded yet.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="mt-3">
                                {{ $music->links() }}
                            </div>
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
