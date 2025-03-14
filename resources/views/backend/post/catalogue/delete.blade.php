@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@include('backend.dashboard.component.formError')
<form action="{{ route('post.catalogue.destroy', $postCatalogue->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('confirm.delete_post_catalogue') }}
                    </div>
                    <div class="panel-description">
                        <p>{!! __('confirm.delete_post_catalogue_name', ['name' => $postCatalogue->name]) !!}</p>
                        <p>{{ __('confirm.cannot_restore') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.post_catalogue_name') }}
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $postCatalogue->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.path') }}
                                    </label>
                                    <input readonly type="text" name="canonical"
                                        value="{{ config('app.url') . old('canonical', $postCatalogue->canonical ?? 'duong-dan-cua-ban') . config('apps.general.suffix') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send"
                value="{{ __('button.delete_post_catalogue') }}" />
        </div>
    </div>
</form>
