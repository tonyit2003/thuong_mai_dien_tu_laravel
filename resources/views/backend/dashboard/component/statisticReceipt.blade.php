<div class="row">
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-success pull-right">Monthly</span>
                <h5>{{ __('dashboard.receipts_of_the_month') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ $receiptStatistic['receiptCurrentMonth'] ?? 0 }}</h1>
                {!! growthHtml($receiptStatistic['growth']) !!}
                <small>{{ __('dashboard.growth') }}</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">Total</span>
                <h5>{{ __('dashboard.total_receipts') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ number_format($receiptStatistic['totalReceipts'], null, ',', '.') }}</h1>
                <div class="stat-percent font-bold text-danger">
                    {{ number_format(($receiptStatistic['cancelReceipts'] / ($receiptStatistic['totalReceipts'] == 0 ? 1 : $receiptStatistic['totalReceipts'])) * 100, null, ',', '.') }}%
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
                <h5>{{ __('dashboard.total_revenue_receipts') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ formatCurrency($receiptStatistic['revenueReceipts']) }}</h1>
                <div class="stat-percent font-bold text-navy">{{ getCurrency() }} <i class="fa fa-money"></i>
                </div>
                <small>{{ __('dashboard.currency') }}</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">Quantity</span>
                <h5>{{ __('dashboard.total_quantity') }}</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{{ number_format($receiptStatistic['totalQuantity'], null, ',', '.') }}
                </h1>
                <div class="stat-percent font-bold text-navy">{{ $receiptStatistic['totalQuantityMonth'] }} <i class="fa fa-line-chart"></i>
                </div>
                <small>{{ __('dashboard.new_quantity_month') }}</small>
            </div>
        </div>
    </div>
</div>
