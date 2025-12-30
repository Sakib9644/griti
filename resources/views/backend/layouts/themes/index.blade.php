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
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Show Theme to user</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($themes as $key => $theme)
                            @php
                                $backgroundColor = $theme->status == 1 ? '#4CAF50' : '#ccc';
                                $sliderTranslateX = $theme->status == 1 ? '26px' : '2px';
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $theme->name }}</td>
                                <td>
                                    @if($theme->image)
                                        <img src="{{ asset($theme->image) }}" alt="Theme Image" width="60">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="form-check form-switch"
                                            style="position: relative; width: 50px; height: 24px; background-color: {{ $backgroundColor }}; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">

                                            <input type="checkbox"
                                                   class="form-check-input theme-status-toggle"
                                                   id="customSwitch{{ $theme->id }}"
                                                   data-id="{{ $theme->id }}"
                                                   name="status"
                                                   {{ $theme->status == 1 ? 'checked' : '' }}
                                                   style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">

                                            <span style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX({{ $sliderTranslateX }});"></span>
                                        </div>
                                    </div>
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
                                <td colspan="5" class="text-center">No themes found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
function showStatusChangeAlert(toggle) {
    let themeId = toggle.dataset.id;
    let newStatus = toggle.checked ? 1 : 0; // desired status

    // Show confirmation before changing
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to update the status?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with status update
            statusChange(toggle, themeId, newStatus);
        } else {
            // Revert toggle if cancelled
            toggle.checked = !toggle.checked;
        }
    });
}

function statusChange(toggle, themeId, status) {
    let parentDiv = toggle.parentElement;
    let span = parentDiv.querySelector('span');

    // Update slider immediately
    parentDiv.style.backgroundColor = status ? '#4CAF50' : '#ccc';
    span.style.transform = status ? 'translateX(26px)' : 'translateX(2px)';

    // AJAX request to backend
    fetch('/admin/theme/status/' + themeId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            toastr.success('Theme status updated successfully');
            if(typeof $('#datatable').DataTable === 'function') {
                $('#datatable').DataTable().ajax.reload();
            }
        } else {
            toastr.error('Failed to update status');
            // Revert toggle and slider
            toggle.checked = !toggle.checked;
            parentDiv.style.backgroundColor = toggle.checked ? '#4CAF50' : '#ccc';
            span.style.transform = toggle.checked ? 'translateX(26px)' : 'translateX(2px)';
        }
    })
    .catch(() => {
        toastr.error('Error updating status');
        // Revert toggle and slider
        toggle.checked = !toggle.checked;
        parentDiv.style.backgroundColor = toggle.checked ? '#4CAF50' : '#ccc';
        span.style.transform = toggle.checked ? 'translateX(26px)' : 'translateX(2px)';
    });
}

// Attach confirmation to all toggles
document.querySelectorAll('.theme-status-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function(e) {
        e.preventDefault(); // prevent immediate change
        showStatusChangeAlert(toggle);
    });
});
</script>

@endsection
