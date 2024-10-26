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
                                    <a href="{{ route('customer.info') }}">{{ __('customerInfo.my_account') }}</a>
                                </li>
                                <li class="no-boder"><a href="{{ route('customer.info') }}">{{ __('customerInfo.info') }}</a></li>
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
                            <h2>{{ __('customerInfo.my_info') }}</h2>
                            <p>{{ __('customerInfo.mess') }}</p>
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
                                        <div class="mt-3 row">
                                            <label for="username" class="col-sm-2 col-form-label mt-1 ">Email</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" name="email" id="username" value="{{ $customer->email }}"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-1"></div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label for="name" class="col-sm-2 col-form-label mt-1">{{ __('customerInfo.name') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="name" id="name" value="{{ $customer->name }}"
                                                    placeholder="{{ __('customerInfo.enter_name') }}">
                                            </div>
                                            <div class="col-sm-1"></div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label for="phone" class="col-sm-2 col-form-label mt-1">{{ __('customerInfo.phone') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="phone" id="phone" value="{{ $customer->phone }}"
                                                    placeholder="{{ __('customerInfo.enter_phone') }}">
                                            </div>
                                            <div class="col-sm-1"></div>
                                        </div>

                                        @php
                                            $sex = $customer->sex;
                                        @endphp

                                        <div class="mt-3 row">
                                            <label class="col-sm-2 col-form-label mt-1">{{ __('customerInfo.sex') }}</label>
                                            <div class="col-sm-10 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sex" id="male"
                                                        value="{{ __('customerInfo.male') }}" {{ $sex == 'Nam' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="male">{{ __('customerInfo.male') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sex" id="female"
                                                        value="{{ __('customerInfo.female') }}" {{ $sex == 'Nữ' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="female">{{ __('customerInfo.female') }}</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sex" id="other"
                                                        value="{{ __('customerInfo.other') }}" {{ $sex == 'Khác' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="other">{{ __('customerInfo.other') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label for="birthday" class="col-sm-2 col-form-label mt-1">{{ __('customerInfo.birthday') }}</label>
                                            <div class="col-sm-3">
                                                <input type="date" class="form-control" name="birthday" id="birthday"
                                                    value="{{ old('birthday', isset($customer->birthday) ? date('Y-m-d', strtotime($customer->birthday)) : '') }}">
                                            </div>
                                            <div class="col-sm-7">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label for="" class="col-sm-2 col-form-label mt-1"></label>
                                            <div class="col-sm-10">
                                                <input type="submit" class="btn button-default" value="{{ __('customerInfo.save') }}"></input>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mt-2">
                                        <div class="text-center mb-2">
                                            <h4>{{ __('customerInfo.choose_image') }}</h4>
                                        </div>
                                        <div class="text-center">
                                            <span class="image img-cover img-target img-avatar">
                                                <img src="{{ old('image', $customer->image ?? 'backend/img/no-photo.png') }}" alt="">
                                            </span>
                                            <input type="hidden" name="image"
                                                value="{{ old('image', $customer->image ?? 'backend/img/no-photo.png') }}"
                                                class="form-control input-image upload-image" data-upload="Images">
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
@endsection
