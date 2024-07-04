<?php

return [
    'module' => [
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
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'Nhóm thành viên',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => 'Thành viên',
                    'route' => 'user.index'
                ]
            ]
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-file',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'Ngôn ngữ',
                    'route' => 'language.index'
                ]
            ]
        ]
    ]
];
