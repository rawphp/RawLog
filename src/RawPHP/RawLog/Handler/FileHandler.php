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
 * @package   RawPHP\RawLog\Handler
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Handler;

use RawPHP\RawLog\Contract\IFormatter;
use RawPHP\RawLog\Contract\IHandler;
use RawPHP\RawLog\Contract\IRecord;
use RawPHP\RawLog\Exception\LogException;
use RawPHP\RawLog\Log;
use RawPHP\RawLog\Record\ErrorLogRecord;

/**
 * This is the normal logging class.
 *
 * @category  PHP
 * @package   RawPHP\RawLog\Handler
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class FileHandler implements IHandler
{
    /**
     * @var int
     */
    private $_level = Log::LEVEL_DEBUG;
    /**
     * @var string
     */
    protected $fileName = 'log.txt';
    /**
     * @var IFormatter
     */
    private $_formatter = NULL;

    /**
     * @param array $config
     */
    public function __construct( $config = [ ] )
    {
        $this->init( $config );
    }

    /**
     * Initialises the handler.
     *
     * @param array $config configuration array
     */
    public function init( $config = [ ] )
    {
        foreach ( $config as $key => $value )
        {
            switch ( $key )
            {
                case 'level':
                    $this->_level = ( int ) $value;
                    break;

                case 'file':
                    $this->fileName = $value;
                    break;

                case 'formatter':
                    $this->_formatter = new $value();
                    break;

                default:
                    // do nothing
                    break;
            }
        }
    }

    /**
     * Returns the minimum log level.
     *
     * @return int log level
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * Returns the log file name.
     *
     * @return string log file name
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Returns the assigned formatter.
     *
     * @return IFormatter the formatter
     */
    public function getFormatter()
    {
        return $this->_formatter;
    }

    /**
     * Sets the formatter for this handler to use.
     *
     * @param IFormatter $formatter the formatter
     */
    public function setFormatter( IFormatter $formatter )
    {
        $this->_formatter = $formatter;
    }

    /**
     * Creates and returns a new record.
     *
     * @param int   $level the log level
     * @param array $args  the log message
     *
     * @return IRecord the record instance
     */
    public function createRecord( $level, array $args )
    {
        $record = new ErrorLogRecord( $level, $args[ 'message' ] );

        return $record;
    }

    /**
     * Handles the logging.
     *
     * This method calls FileHandler::getFormatter() which means that
     * you can use the filter to alter the format if desired.
     *
     * @param IRecord $record the record to log
     *
     * @throws LogException if it fails to create the file.
     */
    public function handle( IRecord $record )
    {
        // check if we should be logging this record
        if ( $record->getLevel() >= $this->_level )
        {
            // check if file exists
            if ( !file_exists( $this->fileName ) )
            {
                if ( !touch( $this->fileName ) )
                {
                    throw new LogException( 'Failed to create log file: ' . $this->fileName );
                }
            }

            file_put_contents(
                $this->fileName,
                $this->getFormatter()->format( $record ),
                FILE_APPEND
            );
        }
    }
}