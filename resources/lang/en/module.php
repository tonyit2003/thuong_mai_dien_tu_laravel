<?php

return [
    'model' => [
        'PostCatalogue'                 => "Post Group",
        'Post'                          => "Post",
        'ProductCatalogue'              => "Product Group",
        'Product'                       => "Product",
    ],
    'type' => [
        'dropdown-menu'                 => 'Dropdown menu',
        'mega-menu'                     => 'Mega menu',
    ],
    'effect' => [
        'fade'                          => 'Fade',
        'cube'                          => 'Cube',
        'coverflow'                     => 'CoverFlow',
        'flip'                          => 'Flip',
        'cards'                         => 'Cards',
        'creative'                      => 'Creative',
    ],
    'navigate' => [
        'hide'                          => 'Hide',
        'dots'                          => 'Dots',
        'thumbnails'                    => 'Thumbnails',
    ],
    'promotion' => [
        'order_amount_range'            => 'Discount by total order value',
        'product_and_quantity'          => 'Discount by individual product',
        'product_quantity_range'        => 'Discount by product quantity',
        'goods_discount_by_quantity'    => 'Buy product - get product discount',
    ],
    'item' => [
        'Product'                       => 'Product Version',
        'ProductCatalogue'              => 'Product Type',
    ],
    'gender' => [
        [
            'id' => 1,
            'name' => 'Male'
        ],
        [
            'id' => 2,
            'name' => 'Female'
        ],
    ],
    'day' => array_map(function ($value) {
        return ['id' => $value - 1, 'name' => $value];
    }, range(1, 31)),
    'applyStatus' => [
        [
            'id'                        => "staff_take_care_customer",
            'name'                      => 'Assigned Staff',
        ],
        [
            'id'                        => "customer_group",
            'name'                      => 'Customer Group',
        ],
        [
            'id'                        => "customer_gender",
            'name'                      => 'Gender',
        ],
        [
            'id'                        => "customer_birthday",
            'name'                      => 'Birthday',
        ],
    ],
];
