<?php

return [
    'module' => [
        [
            'title' => 'Posts',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Groups',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => 'Posts',
                    'route' => 'post.index'
                ]
            ]
        ],
        [
            'title' => 'Members',
            'icon' => 'fa fa-th-large',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'Member Groups',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => 'Members',
                    'route' => 'user.index'
                ]
            ]
        ],
        [
            'title' => 'General Configuration',
            'icon' => 'fa fa-file',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'Languages',
                    'route' => 'language.index'
                ]
            ]
        ]
    ]
];
