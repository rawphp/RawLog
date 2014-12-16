<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 *
 * Copyright (c) 2014 RawPHP.org
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   RawPHP\RawLog
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog;

use Psr\Log\LoggerInterface;
use RawPHP\RawLog\Contract\IHandler;
use RawPHP\RawLog\Contract\ILog;
use RawPHP\RawLog\Exception\LogException;

/**
 * This is the logging class.
 *
 * @category  PHP
 * @package   RawPHP/RawLog
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class Log implements ILog, LoggerInterface
{
    /** @var  array */
    private $_handlers = [ ];

    /**
     * @param array $config
     */
    public function __construct( $config = [ ] )
    {
        $this->init( $config );
    }

    /**
     * Initialises the log.
     *
     * @param array $config configuration array
     *
     * @throws LogException if log file is missing
     */
    public function init( $config = [ ] )
    {
        $handlers = array_values( $config[ 'handlers' ] );

        foreach ( $handlers as $conf )
        {
            $class = $conf[ 'class' ];

            $handler = new $class( $conf );

            $this->addHandler( $handler );
        }
    }

    /**
     * Adds a handler to the log.
     *
     * @param IHandler $handler the handler to add
     */
    public function addHandler( IHandler $handler )
    {
        $this->_handlers[ ] = $handler;
    }

    /**
     * Returns a list of existing handlers.
     *
     * @return array list of handlers
     */
    public function getHandlers()
    {
        return $this->_handlers;
    }

    /**
     * Detailed debug log message.
     *
     * Info useful to developers for debugging the application, not useful during operations.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function debug( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_DEBUG, $message, $context );
    }

    /**
     * Interesting events log message.
     *
     * Normal operational messages - may be harvested for reporting, measuring
     * throughput, etc. - no action required.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function info( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_INFO, $message, $context );
    }

    /**
     * Normal but significant event log message.
     *
     * Events that are unusual but not error conditions - might be summarized in
     * an email to developers or admins to spot potential problems - no immediate
     * action required.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function notice( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_NOTICE, $message, $context );
    }

    /**
     * Exceptional occurrences that are not errors log message.
     *
     * Warning messages, not an error, but indication that an error will occur if
     * action is not taken, e.g. file system 85% full - each item must be resolved
     * within a given time.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function warning( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_WARNING, $message, $context );
    }

    /**
     * Runtime errors that do not require immediate attention
     * but should typically by logged and monitored messages.
     *
     * Non-urgent failures, these should be relayed to developers or admins;
     * each item must be resolved within a given time.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function error( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_ERROR, $message, $context );
    }

    /**
     * Critical conditions log messages.
     *
     * Should be corrected immediately, but indicates failure in a secondary system,
     * an example is a loss of a backup ISP connection.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function critical( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_CRITICAL, $message, $context );
    }

    /**
     * Action must be taken immediately log messages.
     *
     * Should be corrected immediately, therefore notify staff who can fix the problem.
     * An example would be the loss of a primary ISP connection.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function alert( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_ALERT, $message, $context );
    }

    /**
     * Emergency message: system is unusable.
     *
     * A "panic" condition usually affecting multiple apps/servers/sites.
     * At this level it would usually notify all tech staff on call.
     *
     * @param string $message the log message
     * @param array  $context
     *
     * @return null|void
     */
    public function emergency( $message, array $context = [ ] )
    {
        $this->_logIt( self::LEVEL_EMERGENCY, $message, $context );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param int    $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log( $level, $message, array $context = [ ] )
    {
        $this->_logIt( $level, $message, $context );
    }

    /**
     * Logs messages to the log.
     *
     * @param int    $level   the log level
     * @param string $message the log message
     * @param array  $context
     */
    private function _logIt( $level, $message, $context = [ ] )
    {
        foreach ( $this->_handlers as $handler )
        {
            $args[ 'message' ] = $message;

            /** @var IHandler $handler */
            $record = $handler->createRecord( $level, $args, $context );

            $handler->handle( $record );
        }
    }

    /**
     * Returns the level as string.
     *
     * @param int $level the level number
     *
     * @return string the level label
     */
    public static function getLevelString( $level )
    {
        $name = '';

        switch ( $level )
        {
            case self::LEVEL_DEBUG:
                $name = 'DEBUG';
                break;

            case self::LEVEL_INFO:
                $name = 'INFO';
                break;

            case self::LEVEL_NOTICE:
                $name = 'NOTICE';
                break;

            case self::LEVEL_WARNING:
                $name = 'WARNING';
                break;

            case self::LEVEL_ERROR:
                $name = 'ERROR';
                break;

            case self::LEVEL_CRITICAL:
                $name = 'CRITICAL';
                break;

            case self::LEVEL_ALERT:
                $name = 'ALERT';
                break;

            case self::LEVEL_EMERGENCY:
                $name = 'EMERGENCY';
                break;

            default:
                $name = '';
                break;
        }

        return $name;
    }

    // log levels
    const LEVEL_DEBUG = 0;
    const LEVEL_INFO = 1;
    const LEVEL_NOTICE = 2;
    const LEVEL_WARNING = 3;
    const LEVEL_ERROR = 4;
    const LEVEL_CRITICAL = 5;
    const LEVEL_ALERT = 6;
    const LEVEL_EMERGENCY = 7;
}