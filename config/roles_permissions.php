<?php

return [

    'web' => [
        'super-admin' => [
            'display_name' => [
                'en' => 'Super Admin',
                'ar' => 'سوبر مسؤول',
            ],
            'permissions' => '*',
        ],
        'admin' => [
            'display_name' => [
                'en' => 'Admin',
                'ar' => 'مسؤول',
            ],
            'permissions' => [
                'tag' => ['show', 'edit', 'create', 'delete'],
                'service' => ['show', 'edit', 'create', 'delete'],
                'project' => ['show', 'edit', 'create', 'delete'],
                'partner' => ['show', 'edit', 'create', 'delete'],
                'form' => ['show', 'edit', 'create', 'delete', 'export'],
                'form-email' => ['show', 'edit', 'create', 'delete'],
                'form-contact' => ['show', 'edit', 'delete', 'export'],
                'page' => ['show'],
                'section' => ['show', 'edit']
            ]
        ]
    ]

];
