@extends('backend.app', ['title' => 'Motivational Quotes'])



@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header">
                    <h1 class="page-title">Motivational Quotes</h1>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">Add
                        Quote</button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Quote</th>
                                    <th>Status for Quotes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="createForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add Quote</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="quote" class="form-control" placeholder="Enter quote" required></textarea>

                    </div>
                    <div class="modal-footer"><button class="btn btn-primary">Save</button></div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm">
                @csrf @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Quote</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="quote" id="edit_quote" class="form-control" required></textarea>
                        <div class="mt-3 d-flex align-items-center">
                            <label class="switch">
                                <input type="checkbox" name="status" value="1" id="edit_status">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-primary">Update</button></div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
$(function() {

    // Initialize Yajra DataTable
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.motivational-quotes.index') }}",
        columns: [
            {   data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false},
            { data: 'quote', name: 'quote' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // ----------------------
    // CREATE
    // ----------------------
    $('#createForm').submit(function(e) {
        e.preventDefault();
        $.post("{{ route('admin.motivational-quotes.store') }}", $(this).serialize(), function(resp) {
            $('#createModal').modal('hide');
            table.ajax.reload();
        });
    });

    // ----------------------
    // EDIT
    // ----------------------
    window.editQuote = function(id) {
        let url = "{{ route('admin.motivational-quotes.edit', ':id') }}".replace(':id', id);
        $.get(url, function(data) {
            $('#edit_id').val(data.id);
            $('#edit_quote').val(data.quote);
            $('#edit_status').prop('checked', data.status == 1);
            $('#editModal').modal('show');
        });
    }

    // ----------------------
    // UPDATE
    // ----------------------
    $('#editForm').submit(function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let url = "{{ route('admin.motivational-quotes.update', ':id') }}".replace(':id', id);
        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(resp) {
                $('#editModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

    // ----------------------
    // DELETE
    // ----------------------
    window.deleteQuote = function(id) {
        if (confirm('Are you sure?')) {
            let url = "{{ route('admin.motivational-quotes.destroy', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    table.ajax.reload();
                }
            });
        }
    }

    // ----------------------
    // TOGGLE STATUS
    // ----------------------
    window.toggleStatus = function(id) {
        let url = "{{ route('admin.motivational-quotes.toggle-status', ':id') }}".replace(':id', id);
        $.get(url, function(resp) {
            table.ajax.reload();
        });
    }

});
</script>
@endpush
