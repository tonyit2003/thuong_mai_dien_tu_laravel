@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)
@if ($errors->any())
    <div class="alert alert-danger mt20">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url = $config['method'] == 'create' ? route('post.store') : route('post.update', $post->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                @include('backend.post.post.component.general')
                @include('backend.dashboard.component.album')
                @include('backend.post.post.component.seo')
            </div>
            <div class="col-lg-3">
                @include('backend.post.post.component.aside')
            </div>
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="Lưu lại" />
        </div>
    </div>
</form>