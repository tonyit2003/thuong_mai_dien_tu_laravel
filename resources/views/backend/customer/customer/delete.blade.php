@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('customer.destroy', $customer->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('confirm.delete_customer') }}
                    </div>
                    <div class="panel-description">
                        <p>{!! __('confirm.delete_customer_email', ['email' => $customer->email]) !!}</p>
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
                                        {{ __('form.email') }}
                                    </label>
                                    <input readonly type="text" name="email"
                                        value="{{ old('email', $customer->email ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.name') }}
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $customer->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="{{ __('button.delete_customer') }}" />
        </div>
    </div>
</form>
