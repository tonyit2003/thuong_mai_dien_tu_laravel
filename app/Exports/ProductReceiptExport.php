<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductReceiptExport implements FromArray, WithHeadings
{
    protected $formattedDetails;
    protected $total;

    public function __construct($formattedDetails, $total)
    {
        $this->formattedDetails = $formattedDetails;
        $this->total = $total;
    }

    public function headings(): array
    {
        return ['Tên sản phẩm', 'Phiên bản', 'Số lượng', 'Giá (VND)'];
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->formattedDetails as $detail) {
            $data[] = [
                $detail['product_name'],
                $detail['variant_name'],
                $detail['quantity'],
                number_format($detail['price'], 0, ',', '.')
            ];
        }

        $data[] = ["", "", "Tổng tiền", number_format($this->total, 0, ',', '.')];
        return $data;
    }
}
