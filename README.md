# RawLog - A Simple Log Wrapper for Monolog

[![Build Status](https://travis-ci.org/rawphp/RawLog.svg?branch=master)](https://travis-ci.org/rawphp/RawLog) [![Coverage Status](https://coveralls.io/repos/rawphp/RawLog/badge.png)](https://coveralls.io/r/rawphp/RawLog)
[![Latest Stable Version](https://poser.pugx.org/rawphp/raw-log/v/stable.svg)](https://packagist.org/packages/rawphp/raw-log) [![Total Downloads](https://poser.pugx.org/rawphp/raw-log/downloads.svg)](https://packagist.org/packages/rawphp/raw-log) 
[![Latest Unstable Version](https://poser.pugx.org/rawphp/raw-log/v/unstable.svg)](https://packagist.org/packages/rawphp/raw-log) [![License](https://poser.pugx.org/rawphp/raw-log/license.svg)](https://packagist.org/packages/rawphp/raw-log)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/68ab07d4-f4ba-44a6-8003-4344778463f2/big.png)](https://insight.sensiolabs.com/projects/68ab07d4-f4ba-44a6-8003-4344778463f2)

## Package Features
- Supports logging to single file or rotating files, one for each day
- Supports sending emails (with SMTP if desired)

## Installation

### Composer
RawLog is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-log).

Add `"rawphp/raw-log": "0.*@dev"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-log": "0.*@dev"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-log "0.*@dev"
```

### Tarball
Alternatively, just copy the contents of the RawLog folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

use RawPHP\RawLog\Log;

// configuration
// email settings obviously not required if not using MailHandler

$config = array(
    
    class => 'RawPHP\\RawLog\\Log',
    debug =>  FALSE,
    $handlers = array(
        'standard_log' = array( 
            class     => 'RawPHP\\RawLog\\Handlers\\FileHandler',
            file      => '/path/to/log.txt',
            formatter => 'RawPHP\\RawLog\\Formatters\\ErrorLogFormatter',
            level     => 0, // debug
        ),
        rotate_log = array(
            class     => 'RawPHP\\RawLog\\Handlers\\RotatingFileHandler',
            file      => '/path/to/rotate.txt',
            formatter => 'RawPHP\\RawLog\\Formatters\\ErrorLogFormatter',
            level     => 2, // notice
        ),
        mail = array( 
            class     => 'RawPHP\\RawLog\\Handlers\\MailHandler',
            formatter => 'RawPHP\\RawLog\\Formatters\\MailLogFormatter',
            level     => 4, // error
        ),
    ),

    'from_email'  => 'no-reply@rawphp.org',              // default from email to use in log emails
    'from_name'   => 'RawPHP',                           // default from name to use in log emails
    'to_address'  => 'test@example.com',                 // default email address to use in log emails
    'to_name'     => 'RawPHP',                           // default name 
    'subject'     => 'RawPHP Error Log Message',         // log email subject line

    'smtp' => array( 'auth'      => TRUE ),              // enable SMTP authentication
    'smtp' => array( 'host'      => 'smtp.gmail.com' ),  // main and backup SMTP servers
    'smtp' => array( 'username'  => 'username' ),        // SMTP username
    'smtp' => array( 'password'  => 'password' ),        // SMTP password
    'smtp' => array( 'security'  => 'ssl' ),             // Enable TLS encryption, 'ssl' also accepted
    'smtp' => array( 'port'      => '465' ),             // SMTP port
);

// get new instance
$log = new Log( $config );

// use cases
$log->debug( 'message' );
$log->info( 'message' );
$log->notice( 'message' );
$log->warning( 'message' );
$log->error( 'message' );
$log->critical( 'message' );
$log->alert( 'message' );
$log->emergency( 'message' );
```

## License
This package is licensed under the [MIT](https://github.com/rawphp/RawLog/blob/master/LICENSE). Read LICENSE for information on the software availability and distribution.

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/rawphp/RawLog/issues).

## Changelog

#### 01-10-2014
- Removed Monolog dependency.
- Rebuilt log from scratch - inspired by Monolog features

#### 22-09-2014
- Updated to PHP 5.3.

#### 20-09-2014
- Replaced php array configuration with yaml

#### 18-09-2014
- Updated to work with the latest rawphp/rawbase package.

#### 14-09-2014
- Initial Code Commit