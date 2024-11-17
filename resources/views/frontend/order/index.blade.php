@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        <div class="panel-category">
            <div class="uk-container uk-container-center">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Sidebar -->
                        @include('frontend.order.component.sidebar')

                        <!-- Main Content -->
                        <div class="col-md-9">
                            <div class="card">
                                <!-- Nav Tabs -->
                                <div class="card-header">
                                    @include('frontend.order.component.tabTitle')
                                </div>

                                <!-- Tab Content -->
                                <div class="card-body tab-content" id="orderStatusTabsContent">
                                    <!-- Tất cả -->
                                    <div class="tab-pane show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                        @include('frontend.order.component.tabAll')
                                    </div>

                                    <!-- Đang xử lý -->
                                    <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                                        @include('frontend.order.component.tabPending')
                                    </div>

                                    <!-- Đang vận chuyển -->
                                    <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                        @include('frontend.order.component.tabProcessing')
                                    </div>

                                    <!-- Hoàn thành -->
                                    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                                        @include('frontend.order.component.tabSuccess')
                                    </div>

                                    <!-- Đã hủy -->
                                    <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                                        @include('frontend.order.component.tabCancel')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy tất cả các tab
            const tabs = document.querySelectorAll('[data-toggle="tab"]');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Bỏ active/show khỏi tất cả tab và content
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(content => {
                        content.classList.remove('show', 'active');
                    });

                    // Kích hoạt tab được nhấn
                    this.classList.add('active');
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.classList.add('show', 'active');
                    }
                });
            });
        });
    </script>
@endsection
