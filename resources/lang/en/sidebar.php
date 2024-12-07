<?php

return [
    'module' => [
        [
            'title' => 'Statistics',
            'icon' => 'fa fa-database',
            'name' => ['dashboard'],
            'route' => 'dashboard/index',
            'class' => 'special',
            'subModule' => [
                [
                    'title' => 'Invoice Statistics',
                    'route' => 'dashboard.index',
                    'permission' => 'dashboard.index'
                ],
                [
                    'title' => 'Stock In Statistics',
                    'route' => 'statisticalReceipt.index',
                    'permission' => 'dashboard.receipt.index'
                ]
            ]
        ],
        [
            'title' => 'Products',
            'icon' => 'fa fa-cube',
            'name' => ['product', 'attribute'],
            'subModule' => [
                [
                    'title' => 'Product Categories',
                    'route' => 'product.catalogue.index',
                    'permission' => 'product.catalogue.index'
                ],
                [
                    'title' => 'Products',
                    'route' => 'product.index',
                    'permission' => 'product.index'
                ],
                [
                    'title' => 'Attribute Types',
                    'route' => 'attribute.catalogue.index',
                    'permission' => 'attribute.catalogue.index'
                ],
                [
                    'title' => 'Attributes',
                    'route' => 'attribute.index',
                    'permission' => 'attribute.index'
                ],
            ]
        ],
        [
            'title' => 'Orders',
            'icon' => 'fa fa-shopping-bag',
            'name' => ['order'],
            'subModule' => [
                [
                    'title' => 'Orders',
                    'route' => 'order.index',
                    'permission' => 'order.index'
                ],
                [
                    'title' => 'Stock Out',
                    'route' => 'order.outOfStock',
                    'permission' => 'order.outOfStock'
                ],
            ]
        ],
        [
            'title' => 'Warranty',
            'icon' => 'fa fa-shield',
            'name' => ['warranty'],
            'subModule' => [
                [
                    'title' => 'Warranty Reception',
                    'route' => 'warranty.index',
                    'permission' => 'warranty.index'
                ],
                [
                    'title' => 'Product Return',
                    'route' => 'warranty.warrantyRepair',
                    'permission' => 'warranty.warrantyRepair'
                ],
            ]
        ],
        [
            'title' => 'Customers',
            'icon' => 'fa fa-user',
            'name' => ['customer'],
            'subModule' => [
                [
                    'title' => 'Customer Groups',
                    'route' => 'customer.catalogue.index',
                    'permission' => 'customer.catalogue.index'
                ],
                [
                    'title' => 'Customers',
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
                    'title' => 'Promotions',
                    'route' => 'promotion.index',
                    'permission' => 'promotion.index'
                ],
                [
                    'title' => 'Customer Sources',
                    'route' => 'source.index',
                    'permission' => 'source.index'
                ],
            ]
        ],
        [
            'title' => 'Posts',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Categories',
                    'route' => 'post.catalogue.index',
                    'permission' => 'post.catalogue.index'
                ],
                [
                    'title' => 'Posts',
                    'route' => 'post.index',
                    'permission' => 'post.index'
                ]
            ]
        ],
        [
            'title' => 'Reviews',
            'icon' => 'fa fa-star',
            'name' => ['review'],
            'subModule' => [
                [
                    'title' => 'Reviews',
                    'route' => 'review.index',
                    'permission' => 'review.index'
                ],
            ]
        ],
        [
            'title' => 'Stock In',
            'icon' => 'fa fa-edit',
            'name' => ['receipt'],
            'subModule' => [
                [
                    'title' => 'Receipt Management',
                    'route' => 'receipt.index',
                    'permission' => 'receipt.index'
                ],
                [
                    'title' => 'Receipt Monitoring',
                    'route' => 'receipt.monitor',
                    'permission' => 'monitor.receipt'
                ]
            ]
        ],
        [
            'title' => 'Members',
            'icon' => 'fa fa-th-large',
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'Member Groups',
                    'route' => 'user.catalogue.index',
                    'permission' => 'user.catalogue.index'
                ],
                [
                    'title' => 'Members',
                    'route' => 'user.index',
                    'permission' => 'user.index'
                ],
                [
                    'title' => 'Permissions',
                    'route' => 'permission.index',
                    'permission' => 'permission.index'
                ],
            ]
        ],
        [
            'title' => 'Suppliers',
            'icon' => 'fa fa-vcard',
            'name' => ['supplier'],
            'subModule' => [
                [
                    'title' => 'Suppliers',
                    'route' => 'supplier.index',
                    'permission' => 'supplier.index'
                ]
            ]
        ],
        [
            'title' => 'Banners & Slides',
            'icon' => 'fa fa-picture-o',
            'name' => ['slide'],
            'subModule' => [
                [
                    'title' => 'Slide Settings',
                    'route' => 'slide.index',
                    'permission' => 'slide.index'
                ],
            ]
        ],
        [
            'title' => 'Menus',
            'icon' => 'fa fa-bars',
            'name' => ['menu'],
            'subModule' => [
                [
                    'title' => 'Menu Settings',
                    'route' => 'menu.index',
                    'permission' => 'menu.index'
                ],
            ]
        ],
        [
            'title' => 'General Configuration',
            'icon' => 'fa fa-cog',
            'name' => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'Languages',
                    'route' => 'language.index',
                    'permission' => 'language.index'
                ],
                [
                    'title' => 'Modules',
                    'route' => 'generate.index',
                    'permission' => 'generate.index'
                ],
                [
                    'title' => 'System Configuration',
                    'route' => 'system.index',
                    'permission' => 'system.index'
                ],
                [
                    'title' => 'Widget Management',
                    'route' => 'widget.index',
                    'permission' => 'widget.index'
                ],
            ]
        ]
    ]
];
