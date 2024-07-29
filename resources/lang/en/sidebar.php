<?php

return [
    'module' => [
        [
            'title' => 'Posts',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Catalogue',
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
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'User Groups',
                    'route' => 'user.catalogue.index'
                ],
                [
                    'title' => 'Members',
                    'route' => 'user.index'
                ],
                [
                    'title' => 'Permissions',
                    'route' => 'permission.index'
                ],
            ]
        ],
        [
            'title' => 'General Settings',
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
