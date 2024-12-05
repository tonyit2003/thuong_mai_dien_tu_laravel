<?php

return [
    'module' => [
        [
            'title' => 'Thống kê',
            'icon' => 'fa fa-database',
            'name' => ['dashboard'],
            'route' => 'dashboard/index',
            'class' => 'special',
            'subModule' => [
                [
                    'title' => 'Thống kế hóa đơn',
                    'route' => 'dashboard.index',
                    'permission' => 'dashboard.index'
                ],
                [
                    'title' => 'Thống kê nhập kho',
                    'route' => 'statisticalReceipt.index',
                    'permission' => 'dashboard.receipt.index'
                ]
            ]
        ],
        [
            'title' => 'Sản phẩm',
            'icon' => 'fa fa-cube',
            'name' => ['product', 'attribute'],
            'subModule' => [
                [
                    'title' => 'Nhóm sản phẩm',
                    'route' => 'product.catalogue.index',
                    'permission' => 'product.catalogue.index'
                ],
                [
                    'title' => 'Sản phẩm',
                    'route' => 'product.index',
                    'permission' => 'product.index'
                ],
                [
                    'title' => 'Loại thuộc tính',
                    'route' => 'attribute.catalogue.index',
                    'permission' => 'attribute.catalogue.index'
                ],
                [
                    'title' => 'Thuộc tính',
                    'route' => 'attribute.index',
                    'permission' => 'attribute.index'
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
                    'route' => 'order.index',
                    'permission' => 'order.index'
                ],
                [
                    'title' => 'Xuất kho',
                    'route' => 'order.outOfStock',
                    'permission' => 'order.outOfStock'
                ],
            ]
        ],
        [
            'title' => 'Bảo hành',
            'icon' => 'fa fa-shield',
            'name' => ['warranty'],
            'subModule' => [
                [
                    'title' => 'Tiếp nhận bảo hành',
                    'route' => 'warranty.index',
                    'permission' => 'warranty.index'
                ],
                [
                    'title' => 'Giao trả sản phẩm',
                    'route' => 'warranty.warrantyRepair',
                    'permission' => 'warranty.warrantyRepair'
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
                    'route' => 'customer.catalogue.index',
                    'permission' => 'customer.catalogue.index'
                ],
                [
                    'title' => 'Khách hàng',
                    'route' => 'customer.index',
                    'permission' => 'customer.index'
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
                    'route' => 'promotion.index',
                    'permission' => 'promotion.index'
                ],
                [
                    'title' => 'Nguồn khách',
                    'route' => 'source.index',
                    'permission' => 'source.index'
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
                    'route' => 'post.catalogue.index',
                    'permission' => 'post.catalogue.index'
                ],
                [
                    'title' => 'Bài viết',
                    'route' => 'post.index',
                    'permission' => 'post.index'
                ]
            ]
        ],
        [
            'title' => 'Đánh giá',
            'icon' => 'fa fa-star',
            'name' => ['review'],
            'subModule' => [
                [
                    'title' => 'Đánh giá',
                    'route' => 'review.index',
                    'permission' => 'review.index'
                ],
            ]
        ],
        [
            'title' => 'Nhập kho',
            'icon' => 'fa fa-edit',
            'name' => ['receipt'],
            'subModule' => [
                [
                    'title' => 'Quản lý phiếu nhập',
                    'route' => 'receipt.index',
                    'permission' => 'receipt.index'
                ],
                [
                    'title' => 'Giám sát phiếu nhập',
                    'route' => 'receipt.monitor',
                    'permission' => 'monitor.receipt'
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
                    'route' => 'user.catalogue.index',
                    'permission' => 'user.catalogue.index'
                ],
                [
                    'title' => 'Thành viên',
                    'route' => 'user.index',
                    'permission' => 'user.index'
                ],
                [
                    'title' => 'Quyền',
                    'route' => 'permission.index',
                    'permission' => 'permission.index'
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
                    'route' => 'supplier.index',
                    'permission' => 'supplier.index'
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
                    'route' => 'slide.index',
                    'permission' => 'slide.index'
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
                    'route' => 'menu.index',
                    'permission' => 'menu.index'
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
                    'route' => 'language.index',
                    'permission' => 'language.index'
                ],
                [
                    'title' => 'Module',
                    'route' => 'generate.index',
                    'permission' => 'generate.index'
                ],
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system.index',
                    'permission' => 'system.index'
                ],
                [
                    'title' => 'Quản lý widget',
                    'route' => 'widget.index',
                    'permission' => 'widget.index'
                ],
            ]
        ]
    ]
];
