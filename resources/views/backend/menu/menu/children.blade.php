@include('backend.dashboard.component.breadcrumb', [
    'title' => $config['seo']['create']['children'],
])

@include('backend.dashboard.component.formError')

<form action="{{ route('menu.save.children', $menu->id) }}" method="post" class="box menuContainer">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.menu.menu.component.list')
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
