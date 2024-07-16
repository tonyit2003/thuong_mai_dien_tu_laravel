@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('user.catalogue.destroy', $userCatalogue->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('confirm.delete_user_group') }}
                    </div>
                    <div class="panel-description">
                        <p>{!! __('confirm.delete_user_group_name', ['name' => $userCatalogue->name]) !!}</p>
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
                                        {{ __('form.user_catalogue_name') }}
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $userCatalogue->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.description') }}
                                    </label>
                                    <input readonly type="text" name="description"
                                        value="{{ old('description', $userCatalogue->description ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="{{ __('button.delete_user_group') }}" />
        </div>
    </div>
</form>
