<?php

return [
    'module' => [
        [
            'title' => '文章',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => '文章分组',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => '文章',
                    'route' => 'post.index'
                ]
            ]
        ],
        [
            'title' => '会员',
            'icon' => 'fa fa-th-large',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => '会员分组',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => '会员',
                    'route' => 'user.index'
                ]
            ]
        ],
        [
            'title' => '通用配置',
            'icon' => 'fa fa-file',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => '语言',
                    'route' => 'language.index'
                ]
            ]
        ]
    ]
];
