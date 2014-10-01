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
 * @package   RawPHP/RawLog
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog;

use RawPHP\RawBase\Component;
use RawPHP\RawLog\ILog;

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
class Log extends Component implements ILog
{
    /**
     * @var array
     */
    private $_handlers              = array( );

    /**
     * Initialises the log.
     *
     * @param array $config configuration array
     *
     * @action ON_INIT_ACTION
     *
     * @throws LogException if log file is missing
     */
    public function init( $config = array( ) )
    {
        parent::init( $config );

        $handlers = array_values( $config[ 'handlers' ] );

        foreach( $handlers as $conf )
        {
            $class = $conf[ 'class' ];

            $handler = new $class( $conf );

            $this->addHandler( $handler );
        }

        $this->doAction( self::ON_INIT_ACTION );
    }

    /**
     * Adds a handler to the log.
     *
     * @param IHandler $handler the handler to add
     *
     * @action ON_LOG_ADD_HANDLER_ACTION
     *
     * @filter ON_ADD_HANDLER_FILTER(1)
     */
    public function addHandler( IHandler $handler )
    {
        $this->_handlers[] = $this->filter( self::ON_ADD_HANDLER_FILTER, $handler );

        $this->doAction( self::ON_ADD_HANDLER_ACTION );
    }

    /**
     * Returns a list of existing handlers.
     *
     * @filter ON_GET_HANDLERS_FILTER(1)
     *
     * @return array list of handlers
     */
    public function getHandlers( )
    {
        return $this->filter( self::ON_GET_HANDLERS_FILTER, $this->_handlers );
    }

    /**
     * Detailed debug log message.
     *
     * Info useful to developers for debugging the application, not useful during operations.
     *
     * @param string $message the log message
     */
    public function debug( $message )
    {
        $this->_logIt( self::LEVEL_DEBUG, $message );
    }

    /**
     * Interesting events log message.
     *
     * Normal operational messages - may be harvested for reporting, measuring
     * throughput, etc. - no action required.
     *
     * @param string $message the log message
     */
    public function info( $message )
    {
        $this->_logIt( self::LEVEL_INFO, $message );
    }

    /**
     * Normal but significant event log message.
     *
     * Events that are unusual but not error conditions - might be summarized in
     * an email to developers or admins to spot potential problems - no immediate
     * action required.
     *
     * @param string $message the log message
     */
    public function notice( $message )
    {
        $this->_logIt( self::LEVEL_NOTICE, $message );
    }

    /**
     * Exceptional occurrences that are not errors log message.
     *
     * Warning messages, not an error, but indication that an error will occur if
     * action is not taken, e.g. file system 85% full - each item must be resolved
     * within a given time.
     *
     * @param string $message the log message
     */
    public function warning( $message )
    {
        $this->_logIt( self::LEVEL_WARNING, $message );
    }

    /**
     * Runtime errors that do not require immediate attention
     * but should typically by logged and monitored messages.
     *
     * Non-urgent failures, these should be relayed to developers or admins;
     * each item must be resolved within a given time.
     *
     * @param string $message the log message
     */
    public function error( $message )
    {
        $this->_logIt( self::LEVEL_ERROR, $message );
    }

    /**
     * Critical conditions log messages.
     *
     * Should be corrected immediately, but indicates failure in a secondary system,
     * an example is a loss of a backup ISP connection.
     *
     * @param string $message the log message
     */
    public function critical( $message )
    {
        $this->_logIt( self::LEVEL_CRITICAL, $message );
    }

    /**
     * Action must be taken immediately log messages.
     *
     * Should be corrected immediately, therefore notify staff who can fix the problem.
     * An example would be the loss of a primary ISP connection.
     *
     * @param string $message the log message
     */
    public function alert( $message )
    {
        $this->_logIt( self::LEVEL_ALERT, $message );
    }

    /**
     * Emergency message: system is unusable.
     *
     * A "panic" condition usually affecting multiple apps/servers/sites.
     * At this level it would usually notify all tech staff on call.
     *
     * @param string $message the log message
     */
    public function emergency( $message )
    {
        $this->_logIt( self::LEVEL_EMERGENCY, $message );
    }

    /**
     * Logs messages to the log.
     *
     * @param int    $level   the log level
     * @param string $message the log message
     *
     * @filter ON_LOG_IT_FILTER
     */
    private function _logIt( $level, $message )
    {
        foreach( $this->_handlers as $handler )
        {
            $args[ 'message' ] = $message;

            $record = $handler->createRecord( $level, $args );

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

        switch( $level )
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
    const LEVEL_DEBUG                       = 0;
    const LEVEL_INFO                        = 1;
    const LEVEL_NOTICE                      = 2;
    const LEVEL_WARNING                     = 3;
    const LEVEL_ERROR                       = 4;
    const LEVEL_CRITICAL                    = 5;
    const LEVEL_ALERT                       = 6;
    const LEVEL_EMERGENCY                   = 7;


    // actions
    const ON_INIT_ACTION                    = 'on_init_action';
    const ON_ADD_HANDLER_ACTION             = 'on_push_handler_action';

    // filters
    const ON_LOG_IT_FILTER                  = 'on_log_it_filter';
    const ON_GET_HANDLERS_FILTER            = 'on_get_handlers_filter';
    const ON_ADD_HANDLER_FILTER             = 'on_add_handler_filter';
}