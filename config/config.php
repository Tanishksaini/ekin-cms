<?php
return [
    'db' => [
        'host' => 'localhost',
        'user' => 'xibo_user',
        'pass' => 'xibo_password',
        'name' => 'xibo_cms',
        'port' => 3306
    ],
    'apiKeyPaths' => [
        'publicKeyPath' => '/var/www/html/xibo/library/certs/public.key',
        'privateKeyPath' => '/var/www/html/xibo/library/certs/private.key',
        'encryptionKey' => 'bO4nnVMyfcIgnkSdsAEh8tKPy0aeSDlc' // Replace with your generated key
    ],
    'settings' => [
        'installed' => true
    ]
];
