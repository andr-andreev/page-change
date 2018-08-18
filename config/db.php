<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'sqlite:@app/../data/sqlite.db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
