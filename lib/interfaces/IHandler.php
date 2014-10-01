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

use RawPHP\RawLog\IRecord;

/**
 * The log handler interface.
 *
 * @category  PHP
 * @package   RawPHP/RawLog
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IHandler
{
    /**
     * Initialises the handler.
     *
     * @param array $config configuration array
     *
     * @action ON_INIT_ACTION
     */
    public function init( $config = array( ) );

    /**
     * Returns the minimum log level.
     *
     * @filter ON_GET_LEVEL_FILTER(1)
     *
     * @return int log level
     */
    public function getLevel( );

    /**
     * Returns the formatter to be used with this handler.
     *
     * @filter ON_GET_FORMATTER_FILTER(1)
     *
     * @return IFormatter the formatter
     */
    public function getFormatter( );

    /**
     * Sets the formatter for this handler.
     *
     * @param IFormatter $formatter the formatter
     *
     * @filter ON_SET_FORMATTER_FILTER(1)
     */
    public function setFormatter( IFormatter $formatter );

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
    public function createRecord( $level, array $args );

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
    public function handle( IRecord $record );
}