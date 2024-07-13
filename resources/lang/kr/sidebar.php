<?php

return [
    'module' => [
        [
            'title' => '게시물',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => '게시물 그룹',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => '게시물',
                    'route' => 'post.index'
                ]
            ]
        ],
        [
            'title' => '회원',
            'icon' => 'fa fa-th-large',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => '회원 그룹',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => '회원',
                    'route' => 'user.index'
                ]
            ]
        ],
        [
            'title' => '일반 설정',
            'icon' => 'fa fa-file',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => '언어',
                    'route' => 'language.index'
                ]
            ]
        ]
    ]
];
