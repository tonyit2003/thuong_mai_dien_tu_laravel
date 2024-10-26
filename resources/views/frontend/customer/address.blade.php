@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        <div class="panel-category">
            <div class="uk-container uk-container-center">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-lg-3 sidebar">
                            <div class="d-flex align-items-center justify-content-start profile-header">
                                <img src="{{ old('image', $customer->image ?? 'backend/img/no-photo.png') }}" alt="Profile Photo">
                                <div class="ml-3">
                                    <div>{{ $customer->name }}</div>
                                    <p><strong>{{ __('customerInfo.edit_info') }}</strong></p>
                                </div>
                            </div>
                            <hr>
                            <ul class="list-unstyled">
                                <li class="no-boder"><img src="frontend/img/icons8-sale-100.png" alt="">
                                    <a href="#">25.10 Lương Về SaleTo</a>
                                </li>
                                <li class="no-boder"><img src="frontend/img/icons8-account-100.png" alt="">
                                    <a href="#">{{ __('customerInfo.my_account') }}</a>
                                </li>
                                <li class="no-boder"><a href="#">{{ __('customerInfo.info') }}</a></li>
                                <li class="no-boder"><a href="#">{{ __('customerInfo.bank') }}</a></li>
                                <li class="no-boder"><a href="{{ route('customer.address') }}">{{ __('customerInfo.address') }}</a></li>
                                <li class="no-boder"><a href="#">{{ __('customerInfo.change_password') }}</a></li>
                                <li class="no-boder"><a href="#">{{ __('customerInfo.notify') }}</a></li>
                                <li class="no-boder"><a href="#">{{ __('customerInfo.privacy_settings') }}</a></li>
                                <li class="no-boder"><img src="frontend/img/icons8-bill-100.png" alt=""><a
                                        href="">{{ __('customerInfo.bill') }}</a></li>
                                <li class="no-boder"><img src="frontend/img/icons8-bell-100.png" alt=""><a
                                        href="">{{ __('customerInfo.notify') }}</a></li>
                                <li class="no-boder"><img src="frontend/img/icons8-card-100.png" alt=""><a
                                        href="">{{ __('customerInfo.voucher') }}</a></li>
                            </ul>
                        </div>

                        <!-- Main Content -->
                        <div class="col-lg-9 content">
                            <h2>Địa Chỉ Của Tôi</h2>
                            <p>Quản lý thông tin địa chỉ để đặt hàng</p>
                            <div style="padding-top: 8px"></div>
                            <hr>
                            <!-- Profile Form -->
                            <div class="row">
                                @if ($errors->any())
                                    <div class="uk-alert uk-alert-danger mt20">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="profile-form" method="post" action="{{ route('customer.updateInfo') }}">
                                    @csrf
                                    <div class="col-lg-9 mt-2">
                                        <div class="uk-grid uk-grid-medium mb20">
                                            <div class="uk-width-large-1-3">
                                                <select name="province_id" id="" class="setupSelect2 province location" data-target="district">
                                                    <option value="0">{{ __('form.select_province') }}</option>
                                                    @foreach ($provinces as $key => $val)
                                                        <option @if (old('province_id') == $val->code) selected @endif value="{{ $val->code }}">
                                                            {{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="uk-width-large-1-3">
                                                <select name="district_id" id="" class="setupSelect2 district location" data-target="ward">
                                                    <option value="0">{{ __('form.select_district') }}</option>
                                                </select>
                                            </div>
                                            <div class="uk-width-large-1-3">
                                                <select name="ward_id" id="" class="setupSelect2 ward">
                                                    <option value="0">{{ __('form.select_ward') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row mb20">
                                            <input type="text" name="address" id=""
                                                value="{{ old('address', isset($model->address) ? $model->address : '') }}"
                                                placeholder="{{ __('form.enter_address') }}" class="input-text">
                                        </div>
                                        <div class="form-row">
                                            <input type="text" name="description" id="" value="{{ old('description') }}"
                                                placeholder="{{ __('form.note') }}" class="input-text">
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
            '{{ old('province_id', isset($model->province_id) ? $model->province_id : '') }}';
        var district_id =
            '{{ old('district_id', isset($model->district_id) ? $model->district_id : '') }}';
        var ward_id =
            '{{ old('ward_id', isset($model->ward_id) ? $model->ward_id : '') }}';
    </script>
@endsection
