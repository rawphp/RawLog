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
 * @package   RawPHP/RawLog/Formatters
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Formatters;

use RawPHP\RawBase\Component;
use RawPHP\RawLog\IFormatter;
use RawPHP\RawLog\IRecord;
use RawPHP\RawLog\Records\ErrorLogRecord;
use RawPHP\RawLog\Log;
use RawPHP\RawLog\LogException;

/**
 * The Common Log Format formatter.
 *
 * @category  PHP
 * @package   RawPHP/RawLog/Formatters
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class ErrorLogFormatter extends Component implements IFormatter
{
    /**
     * Formats the log record as a string.
     *
     * @param IRecord $record the log record
     *
     * @filter ON_FORMAT_FILTER(2)
     *
     * @throws LogException if record is of the wrong type
     *
     * @return string the formatted log text
     */
    public function format( IRecord $record )
    {
        if ( !$record instanceof ErrorLogRecord )
        {
            throw new LogException( 'Record must be an instance of ErrorLogRecord' );
        }

        $format = '[%s] [%s] [%s] %s';

        $result = sprintf( $format,
            $record->getDate( )->format( 'D M d G:i:s T' ),
            Log::getLevelString( $record->getLevel( ) ),
            $record->getClientIP( ),
            $record->getText( )
        );

        $result .= PHP_EOL;

        return $this->filter( self::ON_FORMAT_FILTER, $result, $record );
    }

    const ON_FORMAT_FILTER          = 'on_format_filter';
}