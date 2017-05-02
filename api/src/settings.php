<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name'      => 'slim-app',
            'path'      => __DIR__ . '/../logs/app.log',
            'level'     => \Monolog\Logger::DEBUG,
        ],

        // Database settings

        'db' => [
            'host'      => $_ENV['MYSQL_SERVER'],
            'user'      => $_ENV['MYSQL_USER'],
            'pass'      => $_ENV['MYSQL_PASSWORD'],
            'dbname'    => $_ENV['MYSQL_DATABASE'],
            'port'      => 3306,
            'charset'   => "utf8",
            'collation' => "utf8_unicode_ci",
        ],

    ],
];

