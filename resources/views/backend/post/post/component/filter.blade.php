<form method="GET" action="{{ route('post.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @php
                        $postCatalogueId = request('post_catalogue_id') != null ? request('post_catalogue_id') : 0;
                    @endphp
                    <select name="post_catalogue_id" class="form-control mr10 setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option {{ $key == $postCatalogueId ? 'selected' : '' }} value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('post.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('post.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>
