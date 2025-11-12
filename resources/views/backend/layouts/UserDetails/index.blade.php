@extends('backend.app', ['title' => 'Show User'])

@section('content')
    <!--app-content open-->
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <div class="page-header">
                    <div>
                        <h1 class="page-title">User</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Show</li>
                        </ol>
                    </div>
                </div>

                @foreach ($users as $user)
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <div class="card">
                                <img src="{{ $user->avatar ? asset($user->avatar) : asset('default/profile.jpg') }}"
                                    class="img-fluid" alt="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card product-sales-main">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Age</th>
                                            <td>{{ $user->user_info->age ?? 'N/A' }} Yrs</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div><!-- COL END -->
                    </div>
                @endforeach

                <!-- Pagination links -->
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
    <!-- CONTAINER CLOSED -->
@endsection

@push('scripts')
    <script type="text/javascript">
        const copyBtns = document.querySelectorAll(".copy-btn");

        if (copyBtns.length > 0) {
            copyBtns.forEach(copyBtn => {
                copyBtn.addEventListener("click", async function() {
                    try {
                        const copyText = this.dataset.clipboardText;
                        await navigator.clipboard.writeText(copyText);
                        alert("Copied to clipboard!");
                    } catch (error) {
                        console.error("Error copying text: ", error);
                    }
                });
            });
        }
    </script>
@endpush
