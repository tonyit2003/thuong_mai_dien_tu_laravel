<div class="ibox-tools">
    <a class="collapse-link">
        <i class="fa fa-chevron-up"></i>
    </a>
    @if ($config['model'] != 'ProductReceipt')
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-wrench"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li>
                <a class="changeStatusAll" data-field="publish" data-model="{{ $config['model'] }}" data-value="1" href="#">
                    {{ __('button.bulk_unlock') }}
                </a>
            </li>
            <li>
                <a class="changeStatusAll" data-field="publish" data-model="{{ $config['model'] }}" data-value="0" href="#">
                    {{ __('button.bulk_lock') }}
                </a>
            </li>
        </ul>
    @endif
    <a class="close-link">
        <i class="fa fa-times"></i>
    </a>
</div>
