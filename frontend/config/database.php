<?php

return [
    'host'     => getenv('DB_HOST') ?: 'mvc_db',
    'port'     => getenv('DB_PORT') ?: '5432',
    'database' => getenv('DB_NAME') ?: 'thegazette',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: 'password',
];
