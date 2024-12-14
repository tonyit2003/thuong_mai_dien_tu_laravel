<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ __('invoices::invoice.invoice') }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css" media="screen">
            body {
                font-family: "DejaVu Sans", Arial, sans-serif;
                font-weight: 400;
                line-height: 1.6;
                color: #495057;
                text-align: left;
                background-color: #fff !important;
                font-size: 10px;
            }

            h4 {
                font-size: 16px;
                margin-top: 0;
                margin-bottom: 10px;
                font-weight: bold;
                color: #343a40;
            }

            p {
                margin: 5px 0;
            }

            .invoice-title {
                font-size: 20px;
                color: #2196f3;
                font-weight: bold;
                text-transform: uppercase;
            }

            .table {
                width: 100%;
                margin-bottom: 20px;
                border-collapse: collapse;
            }

            .table th, .table td {
                padding: 5px 10px;
                vertical-align: middle;
                border: 1px solid #dee2e6;
            }

            .table thead th {
                background-color: #2196f3;
                color: #fff;
                font-weight: bold;
                text-align: center;
                font-size: 14px;
            }

            .table tbody tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            .table tbody tr:hover {
                background-color: #e9ecef;
            }

            .table tbody tr td {
                font-size: 12px;
            }

            .total-amount {
                font-size: 14px;
                font-weight: bold;
                color: #e63946;
                text-align: right;
            }

            .party-header {
                font-size: 14px;
                font-weight: bold;
                color: #495057;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .highlight {
                background-color: #2196f3;
                color: #fff;
                padding: 5px 10px;
                border-radius: 5px;
                font-weight: bold;
                display: inline-block;
            }

            .section-title {
                background-color: #e9ecef;
                padding: 10px 15px;
                font-weight: bold;
                color: #495057;
                border-left: 5px solid #2196f3;
                margin-bottom: 15px;
            }

            .amount-in-words {
                font-style: italic;
                color: #6c757d;
                margin-top: 15px;
                font-size: 10px;
            }

            .page-footer {
                font-size: 10px;
                text-align: center;
                color: #6c757d;
                border-top: 1px solid #dee2e6;
                margin-top: 20px;
                padding-top: 10px;
            }

            .logo {
                margin-bottom: 15px;
            }

            .seller-info, .buyer-info {
                line-height: 1.4;
            }

            .signatures {
                margin-top: 30px;
                border: none;
            }

            .signatures .signature-box {
                width: 150px;
                height: 50px;
                margin: 10px auto;
                border: 1px dashed #495057;
                background-color: #fff;
            }

            .signatures .signer-name {
                font-weight: bold;
                color: #495057;
                margin-top: 5px;
                text-transform: uppercase;
            }
        </style>
    </head>

    <body>
        {{-- Header --}}
        @if($invoice->logo)
            <img src="{{ $invoice->getLogo() }}" alt="logo" height="100">
        @endif

        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase">
                            <strong>{{ __('invoices::invoice.invoice') }}</strong>
                        </h4>
                    </td>
                    <td class="border-0 pl-0">
                        <p>{{ __('invoices::invoice.serial') }}: <strong>{{ $invoice->getSerialNumber() }}</strong></p>
                        <p>{{ __('invoices::invoice.date') }}: <strong>{{ $invoice->getDate() }}</strong></p>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="border-0 pl-0 party-header" width="48.5%">
                        {{ __('invoices::invoice.seller') }}
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0 party-header">
                        {{ __('invoices::invoice.buyer') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        @if($invoice->seller->name)
                            <p class="seller-name">
                                <strong>{{ $invoice->seller->name }}</strong>
                            </p>
                        @endif

                        @if($invoice->seller->taxCode)
                            <p class="seller-taxCode">
                                {{ __('invoices::invoice.taxCode') }}: {{ $invoice->seller->taxCode }}
                            </p>
                        @endif

                        @if($invoice->seller->address)
                            <p class="seller-address">
                                {{ __('invoices::invoice.address') }}: {{ $invoice->seller->address }}
                            </p>
                        @endif

                        @if($invoice->seller->phone)
                            <p class="seller-phone">
                                {{ __('invoices::invoice.phone') }}: {{ $invoice->seller->phone }}
                            </p>
                        @endif
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
                        @if($invoice->buyer->name)
                            <p class="buyer-name">
                                <strong>{{ $invoice->buyer->name }}</strong>
                            </p>
                        @endif

                        @if($invoice->buyer->email)
                            <p class="buyer-email">
                                {{ __('invoices::invoice.email') }}: {{ $invoice->buyer->email }}
                            </p>
                        @endif

                        @if($invoice->buyer->phone)
                            <p class="buyer-phone">
                                {{ __('invoices::invoice.phone') }}: {{ $invoice->buyer->phone }}
                            </p>
                        @endif

                        @if($invoice->buyer->address)
                            <p class="buyer-address">
                                {{ __('invoices::invoice.address') }}: {{ $invoice->buyer->address }}
                            </p>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="border-0 pl-0">{{ __('invoices::invoice.description') }}</th>
                    @if($invoice->hasItemUnits)
                        <th scope="col" class="text-center border-0">{{ __('invoices::invoice.units') }}</th>
                    @endif
                    <th scope="col" class="text-center border-0">{{ __('invoices::invoice.quantity') }}</th>
                    <th scope="col" class="text-right border-0">{{ __('invoices::invoice.price') }}</th>
                    @if($invoice->hasItemDiscount)
                        <th scope="col" class="text-right border-0">{{ __('invoices::invoice.discount') }}</th>
                    @endif
                    @if($invoice->hasItemTax)
                        <th scope="col" class="text-right border-0">{{ __('invoices::invoice.tax') }}</th>
                    @endif
                    <th scope="col" class="text-right border-0 pr-0">{{ __('invoices::invoice.sub_total') }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                @foreach($invoice->items as $item)
                <tr>
                    <td class="pl-0">
                        {{ $item->title }}

                        @if($item->description)
                            <p class="cool-gray">{{ $item->description }}</p>
                        @endif
                    </td>
                    @if($invoice->hasItemUnits)
                        <td class="text-center">{{ $item->units }}</td>
                    @endif
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">
                        {{ $invoice->formatCurrency($item->price_per_unit) }}
                    </td>
                    @if($invoice->hasItemDiscount)
                        <td class="text-right">
                            {{ $invoice->formatCurrency($item->discount) }}
                        </td>
                    @endif
                    @if($invoice->hasItemTax)
                        <td class="text-right">
                            {{ $invoice->formatCurrency($item->tax) }}
                        </td>
                    @endif

                    <td class="text-right pr-0">
                        {{ $invoice->formatCurrency($item->sub_total_price) }}
                    </td>
                </tr>
                @endforeach
                {{-- Summary --}}
                @if($invoice->hasItemOrInvoiceDiscount())
                    <tr style="background-color: #fff !important">
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('invoices::invoice.total_discount') }}</td>
                        <td class="text-right pr-0">
                            {{ $invoice->formatCurrency($invoice->total_discount) }}
                        </td>
                    </tr>
                @endif
                @if($invoice->taxable_amount)
                    <tr style="background-color: #fff !important">
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('invoices::invoice.taxable_amount') }}</td>
                        <td class="text-right pr-0">
                            {{ $invoice->formatCurrency($invoice->taxable_amount) }}
                        </td>
                    </tr>
                @endif
                @if($invoice->tax_rate)
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('invoices::invoice.tax_rate') }}</td>
                        <td class="text-right pr-0">
                            {{ $invoice->tax_rate }}%
                        </td>
                    </tr>
                @endif
                @if($invoice->hasItemOrInvoiceTax())
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('invoices::invoice.total_taxes') }}</td>
                        <td class="text-right pr-0">
                            {{ $invoice->formatCurrency($invoice->total_taxes) }}
                        </td>
                    </tr>
                @endif
                @if($invoice->shipping_amount)
                    <tr>
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('invoices::invoice.shipping') }}</td>
                        <td class="text-right pr-0">
                            {{ $invoice->formatCurrency($invoice->shipping_amount) }}
                        </td>
                    </tr>
                @endif
                    <tr style="background-color: #fff !important">
                        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
                        <td class="text-right pl-0">{{ __('invoices::invoice.total_amount') }}</td>
                        <td class="text-right pr-0 total-amount">
                            {{ $invoice->formatCurrency($invoice->total_amount) }}
                        </td>
                    </tr>
            </tbody>
        </table>

        @if($invoice->notes)
            <p>
                {{ __('invoices::invoice.notes') }}: {!! $invoice->notes !!}
            </p>
        @endif

        <!-- Signature Section -->
        <table class="table signatures">
            <tbody>
                <tr>
                    <td class="text-center seller-signature">
                        <p style="font-weight: bold">{{ __('invoices::invoice.seller') }}</p>
                        <p>{{ __('invoices::invoice.sign_and_full_name') }}</p>
                        <div class="signature-box"></div>
                        <p class="signer-name">{{ '________________' }}</p>
                    </td>
                    <td class="text-center buyer-signature">
                        <p style="font-weight: bold">{{ __('invoices::invoice.buyer') }}</p>
                        <p>{{ __('invoices::invoice.sign_and_full_name') }}</p>
                        <div class="signature-box"></div>
                        <p class="signer-name">{{ '________________' }}</p>
                    </td>
                </tr>
            </tbody>
        </table>


        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
