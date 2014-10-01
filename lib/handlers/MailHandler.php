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

namespace RawPHP\RawLog\Handlers;

use RawPHP\RawBase\Component;
use RawPHP\RawLog\IHandler;
use RawPHP\RawLog\Log;
use RawPHP\RawLog\IFormatter;
use RawPHP\RawLog\IRecord;
use RawPHP\RawLog\Records\MailLogRecord;

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
class MailHandler extends Component implements IHandler
{
    /**
     * @var Mail
     */
    private $_mailer                = NULL;
    /**
     * @var int
     */
    private $_level                 = Log::LEVEL_DEBUG;
    /**
     * @var IFormatter
     */
    private $_formatter             = NULL;

    /**
     * Initialises the handler.
     *
     * @param array $config configuration array
     *
     * @action ON_INIT_ACTION
     */
    public function init( $config = array( ) )
    {
        foreach( $config as $key => $value )
        {
            switch( $key )
            {
                case 'level':
                    $this->_level = ( int )$value;
                    break;

                case 'mailer':
                    $this->_mailer = $value;
                    break;

                case 'formatter':
                    $this->_formatter = new $value( );
                    break;

                default:
                    // do nothing
                    break;
            }
        }

        $this->doAction( self::ON_INIT_ACTION );
    }

    /**
     * Returns the minimum log level before handling the record.
     *
     * @filter ON_GET_LEVEL_FILTER(1)
     *
     * @return int the level
     */
    public function getLevel( )
    {
        return $this->filter( self::ON_GET_LEVEL_FILTER, $this->_level );
    }

    /**
     * Returns the formatter to be used with this handler.
     *
     * @filter ON_GET_FORMATTER_FILTER(1)
     *
     * @return IFormatter the formatter
     */
    public function getFormatter( )
    {
        return $this->filter( self::ON_GET_FORMATTER_FILTER, $this->_formatter );
    }

    /**
     * Sets the formatter for this handler.
     *
     * @param IFormatter $formatter the formatter
     *
     * @filter ON_SET_FORMATTER_FILTER(1)
     */
    public function setFormatter( IFormatter $formatter )
    {
        $this->_formatter = $this->filter( self::ON_SET_FORMATTER_FILTER, $formatter );
    }

    /**
     * Creates and returns a new record.
     *
     * @param int   $level the log level
     * @param array $args  the log message
     *
     * @filter ON_CREATE_RECORD_FILTER(3)
     *
     * @return IRecord the record instance
     */
    public function createRecord( $level, array $args )
    {
        $record = new MailLogRecord( $level, $args );

        return $this->filter( self::ON_CREATE_RECORD_FILTER, $record, $level, $args );
    }

    /**
     * Handles the logging.
     *
     * This method calls FileHandler::getFormatter() which means that
     * you can use the filter to alter the format if desired.
     *
     * @param IRecord $record the record to log
     *
     * @action ON_BEFORE_HANDLE_ACTION
     * @action ON_AFTER_HANDLE_ACTION
     *
     * @throws LogException if it fails to create the file.
     */
    public function handle( IRecord $record )
    {
        $this->doAction( self::ON_BEFORE_HANDLE_ACTION );

        // check if we should be logging this record
        if ( $record->getLevel( ) >= $this->_level )
        {
            $record = $this->filter( self::ON_HANDLE_FILTER, $record );

            $this->_mailer->isHtml( TRUE );
            $this->_mailer->setSubject( $record->getTitle( ) );

            $content = $this->getFormatter( )->format( $record );

            $this->_mailer->setBody( $content );

            $this->_mailer->send( );
        }

        $this->doAction( self::ON_AFTER_HANDLE_ACTION );
    }

    // actions
    const ON_INIT_ACTION                = 'on_init_action';
    const ON_BEFORE_HANDLE_ACTION       = 'on_before_handle_action';
    const ON_AFTER_HANDLE_ACTION        = 'on_handle_action';

    // filters
    const ON_GET_LEVEL_FILTER           = 'on_get_level_filter';
    const ON_SET_FORMATTER_FILTER       = 'on_set_formatter_filter';
    const ON_GET_FORMATTER_FILTER       = 'on_get_formatter_filter';
    const ON_HANDLE_FILTER              = 'on_handle_filter';
    const ON_CREATE_RECORD_FILTER       = 'on_create_record_filter';
}