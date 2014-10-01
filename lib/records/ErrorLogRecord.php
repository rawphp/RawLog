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
 * @package   RawPHP/RawLog/Records
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Records;

use RawPHP\RawBase\Component;
use RawPHP\RawLog\IRecord;

/**
 * The Error Log Format.
 *
 * @category  PHP
 * @package   RawPHP/RawLog/Records
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ErrorLogRecord extends Component implements IRecord
{
    /**
     * @var string
     */
    private $_date                      = NULL;
    private $_level                     = 0;
    private $_clientIP                  = '-';
    private $_text                      = NULL;

    /**
     * Constructs a new record.
     *
     * @param int    $level log level
     * @param string $text  log message text
     */
    public function __construct( $level, $text )
    {
        parent::__construct( );

        $this->_date = new \DateTime( );
        $this->_level = $level;
        $this->_text = $text;
    }

    /**
     * Returns the date.
     *
     * @filter ON_GET_DATE_FILTER(1)
     *
     * @return DateTime
     */
    public function getDate( )
    {
        return $this->filter( self::ON_GET_DATE_FILTER, $this->_date );
    }

    /**
     * Returns the log level.
     *
     * @filter ON_GET_LEVEL_FILTER(1)
     *
     * @return int log level
     */
    public function getLevel( )
    {
        return $this->filter( self::ON_GET_LEVEL_FILTER, $this->_level );
    }

    /**
     * Returns the client IP.
     *
     * @filter ON_GET_CLIENT_IP_FILTER(1)
     *
     * @return string the ip
     */
    public function getClientIP( )
    {
        return $this->filter( self::ON_GET_CLIENT_IP_FILTER, $this->_clientIP );
    }

    /**
     * Returns the log text message.
     *
     * @filter ON_GET_TEXT_FILTER(1)
     *
     * @return string text message
     */
    public function getText( )
    {
        return $this->filter( self::ON_GET_TEXT_FILTER, $this->_text );
    }

    /**
     * Sets the record date.
     *
     * @param \DateTime $date the record date
     */
    public function setDate( \DateTime $date )
    {
        $this->_date = $date;
    }

    // filters
    const ON_GET_DATE_FILTER        = 'on_get_date_filter';
    const ON_GET_LEVEL_FILTER       = 'on_get_level_filter';
    const ON_GET_CLIENT_IP_FILTER   = 'on_get_client_ip_filter';
    const ON_GET_TEXT_FILTER        = 'on_get_text_filter';
}