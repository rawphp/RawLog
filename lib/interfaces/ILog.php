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

/**
 * The log interface.
 *
 * @category  PHP
 * @package   RawPHP/RawLog
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface ILog
{
    /**
     * Adds a handler to the log.
     *
     * @param IHandler $handler the handler to add
     *
     * @action ON_LOG_ADD_HANDLER_ACTION
     *
     * @filter ON_ADD_HANDLER_FILTER(1)
     */
    public function addHandler( IHandler $handler );

    /**
     * Returns a list of existing handlers.
     *
     * @filter ON_GET_HANDLERS_FILTER(1)
     *
     * @return array list of handlers
     */
    public function getHandlers( );

    /**
     * Detailed debug log message.
     *
     * @param string $message the log message
     */
    public function debug( $message );

    /**
     * Interesting events log message.
     *
     * @param string $message the log message
     */
    public function info( $message );

    /**
     * Normal but significant event log message.
     *
     * @param string $message the log message
     */
    public function notice( $message );

    /**
     * Exceptional occurrences that are not errors log message.
     *
     * @param string $message the log message
     */
    public function warning( $message );

    /**
     * Runtime errors that do not require immediate attention
     * but should typically by logged and monitored messages.
     *
     * @param string $message the log message
     */
    public function error( $message );

    /**
     * Critical conditions log messages.
     *
     * @param string $message the log message
     */
    public function critical( $message );

    /**
     * Action must be taken immediately log messages.
     *
     * @param string $message the log message
     */
    public function alert( $message );

    /**
     * Emergency message: system is unusable.
     *
     * @param string $message the log message
     */
    public function emergency( $message );
}