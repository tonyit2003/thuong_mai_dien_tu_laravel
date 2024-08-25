@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['update']['title']]
)

@include('backend.dashboard.component.formError')

<form action="{{ route('menu.store') }}" method="post" class="box menuContainer">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @if ($config['method'] == 'create')
            @include('backend.menu.menu.component.catalogue')
            <hr>
        @endif
        @include('backend.menu.menu.component.list')
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
@include('backend.menu.menu.component.popup')
