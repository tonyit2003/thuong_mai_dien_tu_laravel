<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Storage;

class ProductReceiptExport
{
    protected $formattedDetails;
    protected $total;
    protected $system;
    protected $productReceipt;

    public function __construct($formattedDetails, $total, $system, $productReceipt)
    {
        $this->formattedDetails = $formattedDetails;
        $this->total = $total;
        $this->system = $system;
        $this->productReceipt = $productReceipt;
    }

    public function export()
    {
        // Đường dẫn tới file Excel mẫu
        $templatePath = storage_path('app/public/thong_tin_phieu_dat_hang.xlsx');

        // Mở file Excel mẫu
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getSheetByName('Đơn hàng');

        // Chèn các thông tin bổ sung vào các ô
        $sheet->setCellValue("A1", "Mã số thuế: " . $this->system['contact_tax']);
        $sheet->setCellValue("A2", "Địa chỉ: " . $this->system['contact_address']);
        $sheet->setCellValue("A3", "Số điện thoại: " . $this->system['contact_phone']);
        $sheet->setCellValue("A4", "Website: " . $this->system['contact_website']);
        $sheet->setCellValue("A5", "Email: " . $this->system['contact_email']);
        $sheet->setCellValue("E6", "Mã phiếu: " . $this->productReceipt->id);
        $sheet->setCellValue("C8", "Ngày " . now()->day . " tháng " . now()->month . " năm " . now()->year);
        $sheet->setCellValue("A10", "Kính gửi: " . $this->productReceipt->suppliers->name);

        // Chèn thông tin chi tiết sản phẩm
        $startRow = 14;
        $templateStyle = $sheet->getStyle("A$startRow:E$startRow");

        foreach ($this->formattedDetails as $index => $detail) {
            $currentRow = $startRow + $index;

            // Chèn một dòng mới nếu cần
            if ($index > 0) {
                $sheet->insertNewRowBefore($currentRow, 1);
            }

            // Áp dụng style đã sao chép vào dòng mới
            $sheet->duplicateStyle($templateStyle, "A$currentRow:E$currentRow");

            // Điền dữ liệu vào các ô trong dòng
            $sheet->setCellValue("A$currentRow", $index + 1);
            $sheet->setCellValue("B$currentRow", $detail['product_name']);
            $sheet->setCellValue("C$currentRow", $detail['variant_name']);
            $sheet->setCellValue("D$currentRow", $detail['quantity']);
            $sheet->setCellValue("E$currentRow", number_format($detail['price'], 0, ',', '.'));

            // Căn chỉnh
            $sheet->getStyle("A$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("D$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E$currentRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        // Dòng tổng tiền nằm ngay sau danh sách sản phẩm
        $totalRow = $startRow + count($this->formattedDetails);
        $sheet->setCellValue("D$totalRow", "Tổng tiền");
        $sheet->setCellValue("E$totalRow", number_format($this->total, 0, ',', '.') . " VND");

        // Định dạng tổng tiền
        $sheet->getStyle("D$totalRow:E$totalRow")->getFont()->setBold(true);
        $sheet->getStyle("D$totalRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E$totalRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Hợp nhất và thêm thông báo
        $mergeRow = $totalRow + 2;
        $sheet->mergeCells("A{$mergeRow}:E{$mergeRow}");
        $sheet->setCellValue("A{$mergeRow}", "Chúng tôi mong muốn nhận được hàng vào ngày dự kiến: " . now()->addWeeks(2)->format('d-m-Y') . " để đảm bảo tiến độ kinh doanh và phục vụ khách hàng một cách tốt nhất.");

        // Thêm dòng Trân trọng
        $thanksnum = $mergeRow + 4;
        $sheet->setCellValue("A{$thanksnum}", "Trân trọng, " . $this->system['homepage_company']);

        // Lưu file Excel mới
        $filePath = 'order_receipt_generated.xlsx';
        $fullPath = storage_path("app/public/$filePath");
        $writer = new Xlsx($spreadsheet);
        $writer->save($fullPath);

        return $fullPath;
    }
}
