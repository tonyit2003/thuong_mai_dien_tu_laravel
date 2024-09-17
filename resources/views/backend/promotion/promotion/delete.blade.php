@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('promotion.destroy', $promotion->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('confirm.delete_promotion') }}
                    </div>
                    <div class="panel-description">
                        <p>{!! __('confirm.delete_promotion_name', ['name' => $promotion->name]) !!}</p>
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
                                        {{ __('form.promotion_name') }}
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $promotion->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.promotion_code') }}
                                    </label>
                                    <input readonly type="text" name="code"
                                        value="{{ old('code', $promotion->code ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="{{ __('button.delete_promotion') }}" />
        </div>
    </div>
</form>
