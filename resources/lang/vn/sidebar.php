<?php

return [
    'module' => [
        [
            'title' => 'Sản phẩm',
            'icon' => 'fa fa-cube',
            'name' => ['product'],
            'subModule' => [
                [
                    'title' => 'Nhóm sản phẩm',
                    'route' => 'product.catalogue.index'
                ],
                [
                    'title' => 'Sản phẩm',
                    'route' => 'product.index'
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
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-file',
            'name' => ['language', 'generate'],
            'subModule' => [
                [
                    'title' => 'Ngôn ngữ',
                    'route' => 'language.index'
                ],
                [
                    'title' => 'Module',
                    'route' => 'generate.index'
                ]
            ]
        ]
    ]
];
