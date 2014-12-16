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
 * @package   RawPHP\RawLog\Contract
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Contract;

use RawPHP\RawLog\Exception\LogException;

/**
 * The log handler interface.
 *
 * @category  PHP
 * @package   RawPHP\RawLog\Contract
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
     */
    public function init( $config = [ ] );

    /**
     * Returns the minimum log level.
     *
     * @return int log level
     */
    public function getLevel();

    /**
     * Returns the formatter to be used with this handler.
     *
     * @return IFormatter the formatter
     */
    public function getFormatter();

    /**
     * Sets the formatter for this handler.
     *
     * @param IFormatter $formatter the formatter
     */
    public function setFormatter( IFormatter $formatter );

    /**
     * Creates and returns a new record.
     *
     * @param int   $level the log level
     * @param array $args  the log message
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
     * @throws LogException if it fails to create the file.
     */
    public function handle( IRecord $record );
}