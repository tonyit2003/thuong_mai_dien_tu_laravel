@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        <div class="panel-category">
            <div class="uk-container uk-container-center">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Sidebar -->
                        @include('frontend.customer.component.sidebar')

                        <!-- Main Content -->
                        <div class="col-lg-9 content">
                            <h2>Thay đổi mật khẩu</h2>
                            <p>Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác</p>
                            <hr>
                            <!-- Profile Form -->
                            @if ($errors->any())
                                <div class="uk-alert uk-alert-danger mt20">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="profile-form form shadow p-4 rounded" method="get" action="{{ route('customer.sendChangePassword') }}">
                                @csrf
                                <div class="container">
                                    <!-- Phần trên -->
                                    <div class="text-center mb-5">
                                        <div class="mb-4">
                                            <img src="frontend/img/security.png" alt="Security Icon" class="img-fluid rounded-circle"
                                                style="width: 300px; height: auto;">
                                        </div>
                                        <h5 class="mb-4 text-primary fw-bold">Bảo vệ tài khoản của bạn</h5>
                                        <p class="text-muted">
                                            Để tăng cường bảo mật, hãy xác minh thông tin tài khoản của bạn bằng một trong những cách sau.
                                        </p>
                                        <button type="submit" class="btn btn-default px-5 py-2 mt-3">
                                            Xác minh bằng liên kết Email
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        var province_id =
            '{{ old('province_id', isset($customer->province_id) ? $customer->province_id : '') }}';
        var district_id =
            '{{ old('district_id', isset($customer->district_id) ? $customer->district_id : '') }}';
        var ward_id =
            '{{ old('ward_id', isset($customer->ward_id) ? $customer->ward_id : '') }}';
    </script>
@endsection
