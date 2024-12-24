@extends('frontend.homepage.layout')

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
                            <div class="container">
                                <form method="POST" action="{{ route('customer.updateChangePassword') }}"
                                    class="shadow p-4 bg-white rounded-3 border">
                                    @csrf
                                    <div class="" style="margin-bottom: 12px">
                                        <label for="password"
                                            class="form-label fw-semibold">{{ __('info.new_pass') }}</label>
                                        <input type="password" name="password" id="password" class="input-text"
                                            placeholder="{{ __('info.enter_new_pass') }}" required>
                                    </div>
                                    <div class="" style="margin-bottom: 12px">
                                        <label for="password_confirmation"
                                            class="form-label fw-semibold">{{ __('info.repass') }}</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="input-text" placeholder="{{ __('info.enter_repass') }}" required>
                                    </div>
                                    <div class="">
                                        <button type="submit"
                                            class="btn btn-primary px-4 py-2">{{ __('info.ok_pass') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
