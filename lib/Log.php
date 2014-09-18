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
 * PHP version 5.4
 * 
 * @category  PHP
 * @package   RawPHP/RawLog
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use RawPHP\RawBase\Component;
use RawPHP\RawLog\Handlers\RawMailHandler;
use RawPHP\RawLog\Handlers\LogHandler;
use RawPHP\RawLog\Handlers\RotatingLogHandler;
use RawPHP\RawLog\ILog;
use RawPHP\RawMail\MailException;

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
    protected $monoLog;
    
    /**
     * @var string
     */
    public $name                = 'log';
    /**
     * @var string
     */
    public $logFile             = NULL;
    /**
     * @var int
     */
    public $maxFiles            = 10;
    public $types               = array( );
    public $mailer              = NULL;
    public $to                  = NULL;
    public $subject             = '';
    public $formatCallback      = NULL;
    
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
        
        $this->addFilter( self::ON_LOG_BUILD_HANDLER_FILTER, array( $this, 'isIHandlerFilter' ) );
        
        foreach( $config as $key => $value )
        {
            switch( $key )
            {
                case 'log_name':
                    $this->name = $value;
                    break;
                
                case 'log_type':
                    if ( is_array( $value ) )
                    {
                        $this->types = $value;
                    }
                    else
                    {
                        $this->types[] = $value;
                    }
                    break;
                
                case 'mailer':
                    $this->mailer = $value;
                    break;
                
                case 'to_address':
                    if ( NULL === $this->to )
                    {
                        $this->to = array( );
                    }
                    $this->to[ 'address' ] = $value;
                    break;
                
                case 'to_name':
                    if ( NULL === $this->to )
                    {
                        $this->to = array( );
                    }
                    $this->to[ 'name' ] = $value;
                    break;
                
                case 'subject':
                    $this->subject = $value;
                    break;
                
                case 'format_callback':
                    $this->formatCallback = $value;
                    break;
                
                case 'log_file':
                    $this->logFile = $value;
                    break;
                
                case 'max_files':
                    $this->maxFiles = $value;
                    break;
                
                default:
                    // do nothing
                    break;
            }
        }
        
        if ( NULL == $this->logFile )
        {
            throw new LogException( 'Missing log_file configuration parameter' );
        }
        
        $this->monoLog = new Logger( $this->name );
        
        foreach( $this->types as $type )
        {
            $this->addHandler( $type );
        }
        
        $this->doAction( self::ON_INIT_ACTION );
    }
    
    /**
     * Adds a handler to the log.
     * 
     * @param string $type handler type
     * 
     * @throws LogException if the requested handler type is not supported
     */
    public function addHandler( $type )
    {
        $handler = $this->_buildHandler( $type );
        
        if ( NULL === $handler )
        {
            throw new LogException( 'Unsupported log handler type: ' . $type );
        }
        
        $this->monoLog->pushHandler( $handler );
        
        $this->doAction( self::ON_LOG_PUSH_HANDLER_ACTION );
    }
    
    /**
     * Returns a list of existing handlers.
     * 
     * @filter ON_LOG_GET_HANDLERS_FILTER
     * 
     * @return array list of handlers
     */
    public function getHandlers( )
    {
        $handlers = array( );
        
        if ( NULL !== $this->monoLog )
        {
            $handlers = $this->monoLog->getHandlers( );
        }
        
        return $this->filter( self::ON_LOG_GET_HANDLERS_FILTER, $handlers );
    }
    
    /**
     * Closes all stream handlers.
     * 
     * @action ON_LOG_CLOSE_HANDLERS_ACTION
     */
    public function closeHandlers( )
    {
        if ( NULL !== $this->monoLog )
        {
            foreach( $this->monoLog->getHandlers( ) as $handler )
            {
                if ( $handler instanceof StreamHandler )
                {
                    $handler->close();
                }
            }
        }
        
        $this->doAction( self::ON_LOG_CLOSE_HANDLERS_ACTION );
    }
    
    /**
     * Detailed debug log message.
     * 
     * @param string $message the log message
     */
    public function debug( $message )
    {
        $this->_logIt( Logger::DEBUG, $message );
    }
    
    /**
     * Interesting events log message.
     * 
     * @param string $message the log message
     */
    public function info( $message )
    {
        $this->_logIt( Logger::INFO, $message );
    }
    
    /**
     * Normal but significant event log message.
     * 
     * @param string $message the log message
     */
    public function notice( $message )
    {
        $this->_logIt( Logger::NOTICE, $message );
    }
    
    /**
     * Exceptional occurrences that are not errors log message.
     * 
     * @param string $message the log message
     */
    public function warning( $message )
    {
        $this->_logIt( Logger::WARNING, $message );
    }
    
    /**
     * Runtime errors that do not require immediate attention
     * but should typically by logged and monitored messages.
     * 
     * @param string $message the log message
     */
    public function error( $message )
    {
        $this->_logIt( Logger::ERROR, $message );
    }
    
    /**
     * Critical conditions log messages.
     * 
     * @param string $message the log message
     */
    public function critical( $message )
    {
        $this->_logIt( Logger::CRITICAL, $message );
    }
    
    /**
     * Action must be taken immediately log messages.
     * 
     * @param string $message the log message
     */
    public function alert( $message )
    {
        $this->_logIt( Logger::ALERT, $message );
    }
    
    /**
     * Emergency message: system is unusable.
     * 
     * @param string $message the log message
     */
    public function emergency( $message )
    {
        $this->_logIt( Logger::EMERGENCY, $message );
    }
    
    /**
     * Logs messages to the log.
     * 
     * @param int    $level   the error log level
     * @param string $message the message to log
     * 
     * @filter ON_LOG_IT_FILTER
     */
    private function _logIt( $level, $message )
    {
        $this->monoLog->log( $level, $this->filter( self::ON_LOG_IT_FILTER, $message, $level ) );
    }
    
    /**
     * Returns a stream handler.
     * 
     * @param string $type handler type
     * 
     * @filter ON_LOG_BUILD_HANDLER_FILTER
     * 
     * @return IHandler the handler instance
     */
    private function _buildHandler( $type )
    {
        $handler = NULL;
        
        switch( $type )
        {
            case self::HANDLER_STANDARD_LOG:
                $handler = new LogHandler( $this->logFile );
                break;
                
            case self::HANDLER_ROTATE_LOG:
                $handler = new RotatingLogHandler( $this->logFile, $this->maxFiles );
                break;
            
            case self::HANDLER_RAW_MAIL:
                if ( NULL === $this->mailer )
                {
                    throw new MailException( 'The mailer cannot be NULL' );
                }
                if ( NULL == $this->to )
                {
                    throw new MailException( 'Missing TO: parameter on RawMailHandler' );
                }
                
                $this->mailer->addTo( $this->to );
                $this->mailer->setSubject( $this->subject );
                
                $handler = new RawMailHandler( $this, $this->mailer );
                
                if ( NULL !== $this->formatCallback )
                {
                    $this->addFilter( RawMailHandler::ON_RAW_MAIL_HANDLER_SEND_FILTER, $this->formatCallback );
                }
                break;
                
            default:
                // do nothing
                break;
        }
        
        return $this->filter( self::ON_LOG_BUILD_HANDLER_FILTER, $handler, $type );
    }
    
    /**
     * Checks that the requested handler implements IHandler interface.
     * 
     * @param mixed  $handler the constructed handler
     * @param string $type    requested handler type
     * 
     * @return IHandler the valid handler
     * 
     * @throws LogException if handler is not IHandler
     */
    public function isIHandlerFilter( $handler, $type )
    {
        if ( !$handler instanceof IHandler )
        {
            throw new LogException( get_class( $handler ) . ' is not a IHandler. Requested( ' . $type . ' )' );
        }
        
        return $handler;
    }
    
    // handler types
    const HANDLER_STANDARD_LOG              = 'standard_log';
    const HANDLER_ROTATE_LOG                = 'rotate_log';
    const HANDLER_RAW_MAIL                  = 'raw_mail';
    
    // actions
    const ON_INIT_ACTION                    = 'on_log_init_action';
    const ON_LOG_PUSH_HANDLER_ACTION        = 'on_log_push_handler_action';
    const ON_LOG_CLOSE_HANDLERS_ACTION      = 'on_log_close_handlers_action';
    
    // filters
    const ON_LOG_IT_FILTER                  = 'on_log_it_filter';
    const ON_LOG_BUILD_HANDLER_FILTER       = 'on_log_get_handler_filter';
    const ON_LOG_GET_HANDLERS_FILTER        = 'on_log_get_handlers_filter';
}
