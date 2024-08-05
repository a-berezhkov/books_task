<?php

return [
    [
        'id' => 1,
        'username' => 'admin',
        'auth_key' => 'test_auth_key1',
        'access_token' => 'access_token_1',
        'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
        'email' => 'admin@admin.com',
    ],
    [
        'id' => 2,
        'username' => 'user',
        'auth_key' => 'test_auth_key2',
        'access_token' => 'access_token_2',
        'password_hash' => Yii::$app->security->generatePasswordHash('user'),
        'email' => 'user@user.ru',
    ],
];
