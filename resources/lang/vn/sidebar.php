<?php

return [
    'module' => [
        [
            'title' => 'Sản phẩm',
            'icon' => 'fa fa-cube',
            'name' => ['product', 'attribute'],
            'subModule' => [
                [
                    'title' => 'Nhóm sản phẩm',
                    'route' => 'product.catalogue.index'
                ],
                [
                    'title' => 'Sản phẩm',
                    'route' => 'product.index'
                ],
                [
                    'title' => 'Loại thuộc tính',
                    'route' => 'attribute.catalogue.index'
                ],
                [
                    'title' => 'Thuộc tính',
                    'route' => 'attribute.index'
                ],
            ]
        ],
        [
            'title' => 'Đơn hàng',
            'icon' => 'fa fa-shopping-bag',
            'name' => ['order'],
            'subModule' => [
                [
                    'title' => 'Đơn hàng',
                    'route' => 'order.index'
                ],
            ]
        ],
        [
            'title' => 'Bảo hành',
            'icon' => 'fa fa-shield',
            'name' => ['warranty'],
            'subModule' => [
                [
                    'title' => 'Bảo hành',
                    'route' => 'warranty.index'
                ],
                [
                    'title' => 'Danh sách sản phẩm',
                    'route' => 'warranty.warrantyRepair'
                ],
            ]
        ],
        [
            'title' => 'Khách hàng',
            'icon' => 'fa fa-user',
            'name' => ['customer'],
            'subModule' => [
                [
                    'title' => 'Nhóm khách hàng',
                    'route' => 'customer.catalogue.index'
                ],
                [
                    'title' => 'Khách hàng',
                    'route' => 'customer.index'
                ],
            ]
        ],
        [
            'title' => 'Marketing',
            'icon' => 'fa fa-money',
            'name' => ['promotion', 'source'],
            'subModule' => [
                [
                    'title' => 'Khuyến mãi',
                    'route' => 'promotion.index'
                ],
                [
                    'title' => 'Nguồn khách',
                    'route' => 'source.index'
                ],
            ]
        ],
        [
            'title' => 'Bài viết',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Nhóm bài viết',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => 'Bài viết',
                    'route' => 'post.index'
                ]
            ]
        ],
        [
            'title' => 'Nhập kho',
            'icon' => 'fa fa-edit',
            'name' => ['receipt'],
            'subModule' => [
                [
                    'title' => 'Quản lý phiếu nhập',
                    'route' => 'receipt.index'
                ],
                [
                    'title' => 'Giám sát phiếu nhập',
                    'route' => 'receipt.monitor'
                ]
            ]
        ],
        [
            'title' => 'Thành viên',
            'icon' => 'fa fa-th-large',
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'Nhóm thành viên',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => 'Thành viên',
                    'route' => 'user.index'
                ],
                [
                    'title' => 'Quyền',
                    'route' => 'permission.index'
                ],
            ]
        ],
        [
            'title' => 'Nhà cung cấp',
            'icon' => 'fa fa-vcard',
            'name' => ['supplier'],
            'subModule' => [
                [
                    'title' => 'Nhà cung cấp',
                    'route' => 'supplier.index'
                ]
            ]
        ],
        [
            'title' => 'Banner & Slide',
            'icon' => 'fa fa-picture-o',
            'name' => ['slide'],
            'subModule' => [
                [
                    'title' => 'Cài đặt slide',
                    'route' => 'slide.index'
                ],
            ]
        ],
        [
            'title' => 'Menu',
            'icon' => 'fa fa-bars',
            'name' => ['menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt menu',
                    'route' => 'menu.index'
                ],
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-cog',
            'name' => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'Ngôn ngữ',
                    'route' => 'language.index'
                ],
                [
                    'title' => 'Module',
                    'route' => 'generate.index'
                ],
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system.index'
                ],
                [
                    'title' => 'Quản lý widget',
                    'route' => 'widget.index'
                ],
            ]
        ]
    ]
];
