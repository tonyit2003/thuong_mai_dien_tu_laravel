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
        @include('backend.menu.menu.component.catalogue')
        <hr>
        @include('backend.menu.menu.component.list')
        <input type="hidden" name="redirect" value="{{ $id ?? 0 }}">
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
@include('backend.menu.menu.component.popup')
