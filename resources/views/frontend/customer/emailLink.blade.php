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
                                        <img src="frontend/img/mail.png" alt="Security Icon" class="img-fluid rounded-circle"
                                            style="width: 300px; height: auto;">
                                    </div>
                                    <h5 class="mb-4 text-primary fw-bold">{{ __('info.verify_mail') }}</h5>
                                    <p class="text-muted">
                                        {{ __('info.verify_link') }}
                                        <br>
                                        {{ $hiddenEmail }}
                                    </p>
                                    <button type="button" id="resendButton" class="btn btn-default px-5 py-2 mt-3" disabled>
                                        {{ __('info.send') }} (<span id="timer">60</span>s)
                                    </button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let timerElement = document.getElementById('timer');
                                let resendButton = document.getElementById('resendButton');
                                let countdown = 60;

                                // Hàm đếm ngược
                                let interval = setInterval(function() {
                                    countdown--;
                                    timerElement.textContent = countdown;

                                    // Khi đếm ngược về 0, kích hoạt nút
                                    if (countdown <= 0) {
                                        clearInterval(interval);
                                        resendButton.disabled = false;
                                        resendButton.textContent = {{ __('info.send') }};
                                        resendButton.addEventListener('click', function() {
                                            // Gửi yêu cầu gửi lại xác minh
                                            window.location.href = "{{ route('customer.sendChangePassword') }}";
                                        });
                                    }
                                }, 1000);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
