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
                            <h2>{{ __('info.changePass') }}</h2>
                            <p>{{ __('info.changePassNote') }}</p>
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
                                        <h5 class="mb-4 text-primary fw-bold">{{ __('info.protect') }}</h5>
                                        <p class="text-muted">
                                            {{ __('info.protectinfo') }}
                                        </p>
                                        <button type="submit" class="btn btn-default px-5 py-2 mt-3">
                                            {{ __('info.verify_mail') }}
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
@endsection
