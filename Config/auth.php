<?php

/*
 * Configurações de rotas de acesso ao controllers do sistema.
 */
$config = [
    'default' => [
        'model' => 'Usuarios',
        'crypt' => 'md5',
        'params' => [
            'email' => 'email',
            'password' => 'senha'
        ],
        'redirect' => [
            'success' => '/',
            'error' => '/'
        ]
    ],
    'clientes' => [
        'model' => 'Clientes',
        'crypt' => 'md5',
        'keyName' => 'Auth.Clientes',
        'params' => [
            'email' => 'email',
            'password' => 'senha'
        ],
        'redirect' => [
            'success' => '/admin/home/index',
            'error' => '/admin/home/index'
        ]
    ]
];
