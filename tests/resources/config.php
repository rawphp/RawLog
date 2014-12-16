<?php

return
    [
        'class'      => 'RawPHP\\RawLog\\Log',
        'debug'      => FALSE,
        'handlers'   =>
            [
                'standard_log' =>
                    [
                        'class'     => 'RawPHP\\RawLog\\Handler\\FileHandler',
                        'file'      => 'log.txt',
                        'formatter' => 'RawPHP\\RawLog\\Formatter\\ErrorLogFormatter',
                        'level'     => 0,
                    ],
                'rotate_log'   =>
                    [
                        'class'     => 'RawPHP\\RawLog\\Handler\\RotatingFileHandler',
                        'file'      => 'rotate.txt',
                        'formatter' => 'RawPHP\\RawLog\\Formatter\\ErrorLogFormatter',
                        'level'     => 2,
                    ],
                'mail'         =>
                    [
                        'class'     => 'RawPHP\\RawLog\\Handler\\MailHandler',
                        'formatter' => 'RawPHP\\RawLog\\Formatter\\MailLogFormatter',
                        'level'     => 4,
                    ],

            ],

        'from_email' => 'no-reply@rawphp.org',
        'from_name'  => 'RawPHP',
        'to_address' => 'test@example.com',
        'to_name'    => 'RawPHP',
        'subject'    => 'RawPHP Error Log Message',

        'smtp'       =>
            [
                'auth'     => TRUE,
                'host'     => 'smtp.gmail.com',
                'username' => 'username',
                'password' => 'password',
                'security' => 'ssl',
                'port'     => 465,
            ],
    ];
