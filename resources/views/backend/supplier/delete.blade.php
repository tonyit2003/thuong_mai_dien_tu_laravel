@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('supplier.destroy', $supplier->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('confirm.delete_supplier') }}
                    </div>
                    <div class="panel-description">
                        {{-- gọi đến file confirm và truyền dữ liệu qua --}}
                        <p>{!! __('confirm.delete_supplier_email', ['email' => $supplier->email]) !!}</p>
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
                                    <input readonly type="text" name="email" value="{{ old('email', $supplier->email ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.name') }}
                                    </label>
                                    <input readonly type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="{{ __('button.delete_supplier') }}" />
        </div>
    </div>
</form>
