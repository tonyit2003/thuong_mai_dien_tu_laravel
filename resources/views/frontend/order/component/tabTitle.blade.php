<ul class="nav nav-tabs" id="orderStatusTabs" role="tablist" style="display: flex; width: 100%;">
    <li class="nav-item active" style="flex: 1; text-align: center;">
        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true"
            style="font-size: 1.5rem;" aria-expanded="true">
            {{ __('info.all') }}
        </a>
    </li>
    <li class="nav-item" style="flex: 1; text-align: center;">
        <a class="nav-link" id="processing-tab" data-toggle="tab" href="#processing" role="tab" aria-controls="processing" aria-selected="false"
            style="font-size: 1.5rem;">
            {{ __('info.pending') }}
        </a>
    </li>
    <li class="nav-item" style="flex: 1; text-align: center;">
        <a class="nav-link" id="shipping-tab" data-toggle="tab" href="#shipping" role="tab" aria-controls="shipping" aria-selected="false"
            style="font-size: 1.5rem;">
            {{ __('info.processing') }}
        </a>
    </li>
    <li class="nav-item" style="flex: 1; text-align: center;">
        <a class="nav-link" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false"
            style="font-size: 1.5rem;">
            {{ __('info.success') }}
        </a>
    </li>
    <li class="nav-item" style="flex: 1; text-align: center;">
        <a class="nav-link" id="canceled-tab" data-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false"
            style="font-size: 1.5rem;">
            {{ __('info.cancel') }}
        </a>
    </li>
</ul>
