@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['variant']['title']])

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <div class="row m-t-lg">
            <div class="col-md-9">
                <div class="profile-image">
                    <img src={{ asset($product->image ?? 'backend/img/no-photo.png') }} class="m-b-md img-cover"
                        alt={{ $product->name }}>
                </div>
                <div class="profile-info">
                    <div class="">
                        <div>
                            <h2 class="no-margins">
                                {{ $product->name }}
                            </h2>
                            <h4>{{ __('form.category') }}:
                                {{ $productCatalogue->languages->first()->pivot->name ?? '' }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <table class="table small m-b-xs">
                    <tbody>
                        @if (isset($product->code))
                            <tr>
                                <td>
                                    <strong> {{ __('form.product_code') }}: </strong> <strong
                                        style="color: red">{{ $product->code }}</strong>
                                </td>
                            </tr>
                        @endif
                        @if (isset($product->warranty_time))
                            <tr>
                                <td>
                                    <strong> {{ __('form.warranty_time') }}: </strong>
                                    {{ $product->warranty_time ?? '' }}
                                    ({{ __('form.month') }})
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <strong>{{ __('form.number_of_variant') }}: </strong>
                                {{ count($productVariants) ?? 0 }}
                                ({{ __('unit.variant') }})
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if (isset($productVariants) && count($productVariants) > 0)
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="20">
                            <thead>
                                <tr>
                                    <th data-toggle="true">{{ __('table.variant_name') }}</th>
                                    <th class="text-center" data-hide="phone">{{ __('table.quantity_entered') }}</th>
                                    <th class="text-center" data-hide="phone">{{ __('table.quantity_sold') }}</th>
                                    <th class="text-center" data-hide="phone">{{ __('table.quantity_in_stock') }}</th>
                                    <th data-hide="all">{{ __('table.image') }}</th>
                                    <th data-hide="phone">{{ __('table.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productVariants as $productVariant)
                                    <tr>
                                        <td>
                                            {{ $productVariant->languages->first()->pivot->name ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $productVariant->quantity_entered ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $productVariant->quantity_sold ?? 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $productVariant->quantity ?? 0 }}
                                        </td>
                                        <td>
                                            @php
                                                $images = explode(',', $productVariant->album ?? '');
                                            @endphp
                                            <div class="wrapper wrapper-content">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="ibox float-e-margins">
                                                            <div class="ibox-content">
                                                                <div class="lightBoxGallery">
                                                                    @if (isset($images) && count($images) > 0)
                                                                        @foreach ($images as $image)
                                                                            <img width="100px"
                                                                                src="{{ asset($image) }}">
                                                                        @endforeach
                                                                    @else
                                                                        <img width="100px"
                                                                            src="{{ asset('backend/img/no-photo.png') }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ formatCurrency($productVariant->price) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    $(document).ready(function() {
        $(".footable").footable();
    });
</script>
