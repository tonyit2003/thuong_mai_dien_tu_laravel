@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row">
    <div class="col-lg-12 mt20">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['table'] }} </h5>
                @include('backend.dashboard.component.toolbox')
            </div>
            <div class="ibox-content">
                @include('backend.menu.menu.component.filter')
                @include('backend.menu.menu.component.table')
            </div>
        </div>
    </div>
</div>
