<?php

return [
    'components' => [
        'db' => [
            'host' => '',
            'db' => '',
            'user' => '',
            'password' => '',
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