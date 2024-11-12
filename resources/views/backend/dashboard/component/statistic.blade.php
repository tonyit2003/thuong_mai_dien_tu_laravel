<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">Monthly</span>
                <h5>{{ __('dashboard.orders_of_the_month') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $orderStatistic['orderCurrentMonth'] ?? 0 }}</h1>
                {!! growthHtml($orderStatistic['growth']) !!}
                <small>{{ __('dashboard.growth') }}</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">Total</span>
                <h5>{{ __('dashboard.total_orders') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ number_format($orderStatistic['totalOrders'], null, ',', '.') }}</h1>
                <div class="stat-percent font-bold text-danger">
                    {{ number_format(($orderStatistic['cancelOrders'] / ($orderStatistic['totalOrders'] == 0 ? 1 : $orderStatistic['totalOrders'])) * 100, null, ',', '.') }}%
                    <i class="fa fa-bolt"></i>
                </div>
                <small>{{ __('dashboard.cancellation_rate') }}</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-primary pull-right">Revenue</span>
                <h5>{{ __('dashboard.total_revenue') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ formatCurrency($orderStatistic['revenueOrders']) }}</h1>
                <div class="stat-percent font-bold text-navy">{{ getCurrency() }} <i class="fa fa-money"></i>
                </div>
                <small>{{ __('dashboard.currency') }}</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">Customer</span>
                <h5>{{ __('dashboard.total_number_of_customers') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ number_format($customerStatistic['totalCustomer'], null, ',', '.') }}
                </h1>
                <div class="stat-percent font-bold text-navy">{{ $customerStatistic['customerCurrentMonth'] }} <i
                        class="fa fa-user"></i>
                </div>
                <small>{{ __('dashboard.new_customers_month') }}</small>
            </div>
        </div>
    </div>
</div>
