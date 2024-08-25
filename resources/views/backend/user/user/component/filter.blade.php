<form method="GET" action="{{ route('user.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @php
                        $userCatalogueSelected = old('user_catalogue_id') ?? request('user_catalogue_id');
                    @endphp
                    <select name="user_catalogue_id" class="form-control mr10 setupSelect2" id="">
                        <option value="0">
                            {{ __('form.select_user_catalogue') }}
                        </option>
                        @foreach ($userCatalogues as $value)
                            <option value="{{ $value->id }}" @if ($userCatalogueSelected == $value->id) selected @endif>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('user.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('user.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
