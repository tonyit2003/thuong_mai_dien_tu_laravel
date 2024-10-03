<?php

return [
    'index' => [
        'title' => 'Quản lý khuyến mãi',
        'table' => 'Danh sách khuyến mãi'
    ],
    'create' => [
        'title' => 'Thêm mới khuyến mãi'
    ],
    'edit' => [
        'title' => 'Cập nhật khuyến mãi'
    ],
    'delete' => [
        'title' => 'Xóa khuyến mãi'
    ],
    'translate' => [
        'title' => 'Tạo bản dịch :language cho khuyến mãi'
    ],
    'request' => [
        'name_required'                 => 'Bạn chưa nhập tên của khuyến mãi',
        'code_required'                 => 'Bạn chưa nhập mã của khuyến mãi',
        'code_unique'                   => 'Mã khuyến mãi đã tồn tại',
        'startDate_required'            => 'Bạn chưa nhập ngày bắt đầu khuyến mãi',
        'startDate_custom_date_format'  => 'Ngày bắt đầu khuyến mãi không đúng định dạng',
        'endDate_required'              => 'Bạn chưa nhập ngày kết thúc khuyến mãi',
        'endDate_custom_date_format'    => 'Ngày kết thúc khuyến mãi không đúng định dạng',
        'endDate_custom_after'          => 'Ngày kết thúc khuyến mãi phải lớn hơn ngày bắt đầu khuyến mãi',
        'amountValue_fail'              => 'Cấu hình giá trị khuyến mãi không hợp lệ',
        'amount_fail'                   => 'Khoảng khuyến mãi chưa được khởi tạo đúng',
        'conflict_fail'                 => 'Có xung đột giữa các khoảng giá trị khuyến mãi',
        'quantity_fail'                 => 'Bạn chưa nhập số lượng mua tối thiểu',
        'discountValue_fail'            => 'Bạn chưa nhập giá trị chiết khấu',
        'object_fail'                   => 'Bạn chưa chọn đối tượng áp dụng chiết khấu',
        'method_not_in'                 => 'Bạn chưa chọn hình thức khuyến mãi',
        'discountValue_percent_fail'    => 'Phần trăm khuyến mãi không được lớn hơn 100%',
    ],
];
