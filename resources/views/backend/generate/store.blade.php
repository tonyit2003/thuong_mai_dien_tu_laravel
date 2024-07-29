@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)

@include('backend.dashboard.component.formError')

@php
    $url = $config['method'] == 'create' ? route('generate.store') : route('generate.update', $generate->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('form.general_info') }}
                    </div>
                    <div class="panel-description">
                        <p>{{ __('form.enter_general_info', ['model' => 'module']) }}</p>
                        <p>{!! __('form.required_fields') !!}</p>
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
                                        {{ __('form.model_name') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $generate->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.function_name') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="module"
                                        value="{{ old('module', $generate->module ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    @php
                                        $moduleType = [
                                            0 => __('form.select_module_type'),
                                            'catalogue' => __('form.category_module'),
                                            'detail' => __('form.detail_module'),
                                            'other' => __('form.other_module'),
                                        ];
                                    @endphp
                                    <label for="" class="control-label text-left">
                                        {{ __('form.module_type') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <select name="moduleType" id="" class="form-control setupSelect2">
                                        @foreach ($moduleType as $key => $val)
                                            <option {{ $key == old('moduleType') ? 'selected' : '' }}
                                                value="{{ $key }}">
                                                {{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.path') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="path"
                                        value="{{ old('path', $generate->path ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('form.general_schema') }}
                    </div>
                    <div class="panel-description">
                        <p>{{ __('form.enter_general_info', ['model' => 'schema']) }}</p>
                        <p>{!! __('form.required_fields') !!}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.schema') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    @php
                                        $content = "Schema::create('ten_bang', function (Blueprint \$table) {
            \$table->id();
            \$table->integer('parent_id')->default(0);
            \$table->integer('lft')->default(0);
            \$table->integer('rgt')->default(0);
            \$table->integer('level')->default(0);
            \$table->string('image')->nullable();
            \$table->string('icon')->nullable();
            \$table->text('album')->nullable();
            \$table->tinyInteger('publish')->default(1);
            \$table->tinyInteger('follow')->default(1);
            \$table->integer('order')->default(0);
            \$table->unsignedBigInteger('user_id');
            \$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            \$table->timestamp('deleted_at')->nullable();
            \$table->timestamps();
        });";
                                    @endphp
                                    <textarea name="schema" class="form-control schema">{{ old('schema', $generate->schema ?? $content) }}</textarea>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.schema2') }}
                                    </label>
                                    <textarea name="schema2[]" class="form-control schema">{{ old('schema', $generate->schema ?? '') }}</textarea>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
