
# RawLog - A Simple Log Wrapper for Monolog

## Class Features

- Supports logging to single file or rotating files, one for each day
- Supports sending emails (with SMTP if desired)

## Installation

### Composer
RawLog is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-log).

Add `"rawphp/raw-log": "0.1.1"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-log": "0.1.1"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-log "0.1.1"
```

### Tarball
Alternatively, just copy the contents of the RawLog folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

use RawPHP\RawLog\Log;

// configuration
// email settings obviously not required if not using Log::HANDLER_RAW_MAIL
$config = array(
    'log_file'    => '/path/to/log.txt',
    'log_name'    => 'test-log',
    'log_type'    => array(
        Log::HANDLER_STANDARD_LOG,
        Log::HANDLER_ROTATE_LOG,
        Log::HANDLER_RAW_MAIL
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
$log = new Log( );

// initialise the log
$log->init( $config );

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

#### 14-09-2014
- Initial Code Commit