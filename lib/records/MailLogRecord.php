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
 * @package   RawPHP
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Records;

use RawPHP\RawBase\Component;
use RawPHP\RawLog\IRecord;

/**
 * MailLogRecord
 *
 * @category  PHP
 * @package   RawPHP
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class MailLogRecord extends Component implements IRecord
{
    /**
     * @var string
     */
    private $_date                      = NULL;
    private $_level                     = 0;
    private $_title                     = '';
    private $_heading                   = '';
    private $_body                      = '';

    /**
     * Constructs a new record.
     *
     * @param int   $level log level
     * @param array $args  record arguments
     */
    public function __construct( $level, $args )
    {
        parent::__construct( );

        $this->_date = new \DateTime( );

        $this->_level = $level;

        foreach ( $args as $key => $value )
        {
            switch( $key )
            {
                case 'heading':
                    $this->_heading = $value;
                    break;

                case 'title':
                    $this->_title = $value;
                    break;

                case 'body':
                    $this->_body = $value;
                    break;

                default:
                    // do nothing
                    break;
            }
        }
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
     * Returns the message title.
     *
     * @filter ON_GET_TITLE_FILTER(1)
     *
     * @return string the title
     */
    public function getTitle( )
    {
        return $this->filter( self::ON_GET_TITLE_FILTER, $this->_title );
    }

    /**
     * Returns the message heading.
     *
     * @filter ON_GET_HEADING_FILTER(1)
     *
     * @return string the message heading
     */
    public function getHeading( )
    {
        return $this->filter( self::ON_GET_HEADING_FILTER, $this->_heading );
    }

    /**
     * Returns the log body message.
     *
     * @filter ON_GET_TEXT_FILTER(1)
     *
     * @return string text message
     */
    public function getBody( )
    {
        return $this->filter( self::ON_GET_BODY_FILTER, $this->_body );
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
    const ON_GET_TITLE_FILTER       = 'on_get_title_filter';
    const ON_GET_HEADING_FILTER     = 'on_get_heading_filter';
    const ON_GET_BODY_FILTER        = 'on_get_body_filter';
}