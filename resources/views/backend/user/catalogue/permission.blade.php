@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['permission']['title']])

@php
    $canonical = \Illuminate\Support\Facades\App::getLocale();
    $canonical = $canonical == 'vn' ? 'vi' : $canonical;
@endphp

<form action="{{ route('user.catalogue.updatePermission') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.permission') }}</h5>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th></th>
                            @foreach ($userCatalogues as $userCatalogue)
                                <th class="text-center">
                                    {{ translateContent($userCatalogue->name, $canonical) }}
                                </th>
                            @endforeach
                        </tr>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>
                                    <a href="#" class="uk-flex uk-flex-middle uk-flex-space-between">
                                        {{ translateContent($permission->name, $canonical) }}
                                        <span style="color: red">
                                            ({{ $permission->canonical }})
                                        </span>
                                    </a>
                                </td>
                                @foreach ($userCatalogues as $userCatalogue)
                                    <td>
                                        <input {{--
                                            - collect: Tạo một tập hợp (collection) các permission của 1 nhóm thành viên.
                                            - contains: Kiểm tra $permission->id có bằng với bất kỳ giá trị của trường id nào trong collection hay không
                                            => Kiểm tra xem 1 permission có tồn tại trong 1 tập hợp các permission hay không?
                                         --}}
                                            {{ collect($userCatalogue->permissions)->contains('id', $permission->id) ? 'checked' : '' }}
                                            type="checkbox" {{-- vd: name = permission[1][] => quyền 1 sẽ có mảng các quyền --}}
                                            name="permission[{{ $userCatalogue->id }}][]" value="{{ $permission->id }}"
                                            class="form-control">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <div class="text-right mb15 mt20">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
