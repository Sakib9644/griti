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
                                            <th>Email</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>

                                        <tr>
                                            <th>Age</th>
                                            <td>
                                                {{ $user->user_info?->age ? \Carbon\Carbon::parse($user->user_info->age)->age . ' Years' : 'N/A' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Height (Text Input)</th>
                                            <td>{{ $user->user_info->height_in ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Weight (Text Input)</th>
                                            <td>{{ $user->user_info->weight_in ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Target Weight (Text Input)</th>
                                            <td>{{ $user->user_info->target_weight_in ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>BMI</th>
                                            <td>{{ $user->user_info->bmi ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Body Part Focus</th>
                                            <td>{{ $user->user_info->body_part_focus ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Body Satisfaction</th>
                                            <td>{{ $user->user_info->body_satisfaction ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Celebration Plan</th>
                                            <td>{{ $user->user_info->celebration_plan ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Current Body Type</th>
                                            <td>{{ $user->user_info->current_body_type ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Current Weight</th>
                                            <td>{{ $user->user_info->current_weight ?? 'N/A' }} kg</td>
                                        </tr>

                                        <tr>
                                            <th>Dream Body</th>
                                            <td>{{ $user->user_info->dream_body ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Height</th>
                                            <td>{{ $user->user_info->height ?? 'N/A' }} cm</td>
                                        </tr>

                                        <tr>
                                            <th>Target Weight</th>
                                            <td>{{ $user->user_info->target_weight ?? 'N/A' }} kg</td>
                                        </tr>

                                        <tr>
                                            <th>Trying Duration</th>
                                            <td>{{ $user->user_info->trying_duration ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Urgent Improvement</th>
                                            <td>{{ $user->user_info->urgent_improvement ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Price</th>
                                            <td>{{ $user->user_info->price ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Payment Status</th>
                                            <td>{{ $user->user_info->payment_status ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Subscription ID</th>
                                            <td>{{ $user->user_info->subscription_id ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Signature</th>
                                            <td>{{ $user->user_info->signature ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Payment Method</th>
                                            <td>{{ $user->user_info->payment_method ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $user->user_info->created_at ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $user->user_info->updated_at ?? 'N/A' }}</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
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
