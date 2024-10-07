@if ($model->hasPages())
    <ul class="pagination">
        @foreach ($model->getUrlRange(1, $model->lastPage()) as $page => $url)
            @php
                $paginationUrl = str_replace('?page=', '/page-', $url) . config('apps.general.suffix');
                $paginationUrl = $page == 1 ? str_replace('/page-' . $page, '', $paginationUrl) : $paginationUrl;
            @endphp
            <li class="page-item {{ $page == $model->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginationUrl }}">{{ $page }}</a>
            </li>
        @endforeach
    </ul>
@endif
