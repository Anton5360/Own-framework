<?php

return [
    'components' => [
        'db' => [
            'host' => 'db',
            'name' => 'own_framework_db',
            'user' => 'own_framework_user',
            'password' => 'own_framework_pwd',
            ],
        'template' => [
            'baseDir' => __DIR__ . '/../views/templates/',
            'template' => ''
        ],
        'dispatcher' => [
            'defaultControllerName' => 'index',
            'defaultActionName' => 'index',
        ],
        'router' => [
            'controllersNamespace' => '\\app\\controllers\\',
        ],
    ]
];