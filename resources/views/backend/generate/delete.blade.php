@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('generate.destroy', $generate->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('confirm.delete_generate') }}
                    </div>
                    <div class="panel-description">
                        {{-- gọi đến file confirm và truyền dữ liệu qua --}}
                        <p>{!! __('confirm.delete_generate_name', ['name' => $generate->name]) !!}</p>
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
                                        {{ __('form.generate_name') }}
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $generate->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.generate_code') }}
                                    </label>
                                    <input readonly type="text" name="canonical"
                                        value="{{ old('canonical', $generate->canonical ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="{{ __('button.delete_generate') }}" />
        </div>
    </div>
</form>