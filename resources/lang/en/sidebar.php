<?php

return [
    'module' => [
        [
            'title' => 'Product',
            'icon' => 'fa fa-cube',
            'name' => ['product', 'attribute'],
            'subModule' => [
                [
                    'title' => 'Product Group',
                    'route' => 'product.catalogue.index'
                ],
                [
                    'title' => 'Product',
                    'route' => 'product.index'
                ],
                [
                    'title' => 'Attribute Type',
                    'route' => 'attribute.catalogue.index'
                ],
                [
                    'title' => 'Attribute',
                    'route' => 'attribute.index'
                ],
            ]
        ],
        [
            'title' => 'Customer',
            'icon' => 'fa fa-user',
            'name' => ['customer'],
            'subModule' => [
                [
                    'title' => 'Customer Group',
                    'route' => 'customer.catalogue.index'
                ],
                [
                    'title' => 'Customer',
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
                    'title' => 'Promotion',
                    'route' => 'promotion.index'
                ],
                [
                    'title' => 'Customer Source',
                    'route' => 'source.index'
                ],
            ]
        ],
        [
            'title' => 'Post',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Group',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => 'Post',
                    'route' => 'post.index'
                ]
            ]
        ],
        [
            'title' => 'Warehouse',
            'icon' => 'fa fa-edit',
            'name' => ['receipt'],
            'subModule' => [
                [
                    'title' => 'Manage Receipts',
                    'route' => 'receipt.index'
                ],
                [
                    'title' => 'Monitor Receipts',
                    'route' => 'receipt.monitor'
                ]
            ]
        ],
        [
            'title' => 'User',
            'icon' => 'fa fa-th-large',
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'User Group',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => 'User',
                    'route' => 'user.index'
                ],
                [
                    'title' => 'Permission',
                    'route' => 'permission.index'
                ],
            ]
        ],
        [
            'title' => 'Supplier',
            'icon' => 'fa fa-vcard',
            'name' => ['supplier'],
            'subModule' => [
                [
                    'title' => 'Supplier',
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
                    'title' => 'Slide Settings',
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
                    'title' => 'Menu Settings',
                    'route' => 'menu.index'
                ],
            ]
        ],
        [
            'title' => 'General Settings',
            'icon' => 'fa fa-cog',
            'name' => ['language', 'generate', 'system', 'widget'],
            'subModule' => [
                [
                    'title' => 'Language',
                    'route' => 'language.index'
                ],
                [
                    'title' => 'Module',
                    'route' => 'generate.index'
                ],
                [
                    'title' => 'System Configuration',
                    'route' => 'system.index'
                ],
                [
                    'title' => 'Widget Management',
                    'route' => 'widget.index'
                ],
            ]
        ]
    ]
];
