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
                            <div class="container">
                                <form method="POST" action="{{ route('customer.updateChangePassword') }}" class="shadow p-4 bg-white rounded-3 border">
                                    @csrf
                                    <div class="" style="margin-bottom: 12px">
                                        <label for="password" class="form-label fw-semibold">Mật khẩu mới</label>
                                        <input type="password" name="password" id="password" class="input-text" placeholder="Nhập mật khẩu mới" required>
                                    </div>
                                    <div class="" style="margin-bottom: 12px">
                                        <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-text"
                                            placeholder="Nhập lại mật khẩu mới" required>
                                    </div>
                                    <div class="">
                                        <button type="submit" class="btn btn-primary px-4 py-2">Cập nhật mật khẩu</button>
                                    </div>
                                </form>
                                <style>
                                    .form-label {
                                        color: #495057;
                                    }

                                    .btn-primary {
                                        background-color: #007bff;
                                        border-color: #007bff;
                                        border-radius: 10px;
                                    }

                                    .btn-primary:hover {
                                        background-color: #0056b3;
                                    }
                                </style>
                            </div>
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
