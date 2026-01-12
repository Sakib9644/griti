@extends('backend.app', ['title' => 'Workout Videos'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header d-flex justify-content-between align-items-center mb-3">
                <h1 class="page-title">Workout Videos</h1>
                <a href="{{ route('admin.workout_videos.create') }}" class="btn btn-primary">Add Workout Video</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover" id="workoutTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Video Title</th>
                                <th>Thumbnail</th>
                                <th>Workout Video</th>
                                <th>Duration (s)</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
    $('#workoutTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.workout_videos.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
            { data: 'videos', name: 'videos', orderable: false, searchable: false },
            { data: 'seconds', name: 'seconds' },
            { data: 'descriptions', name: 'descriptions', orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
