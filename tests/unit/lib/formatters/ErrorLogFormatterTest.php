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

use RawPHP\RawLog\Formatters\ErrorLogFormatter;
use RawPHP\RawLog\Records\ErrorLogRecord;

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
class ErrorLogFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ErrorLogFormatter
     */
    public $formatter                           = NULL;

    /**
     * Setup before each test.
     */
    public function setUp( )
    {
        $this->formatter = new ErrorLogFormatter( );
    }

    /**
     * Cleanup before each test.
     */
    public function tearDown( )
    {
        $this->formatter = NULL;
    }

    /**
     * Test formatter initialised correctly.
     */
    public function testFormatterInitialised( )
    {
        $this->assertNotNull( $this->formatter );
    }

    /**
     * Test formatting with valid settings.
     */
    public function testFormatValidSettings( )
    {
        $record = new ErrorLogRecord( Log::LEVEL_ERROR, 'This is the error message' );
        $date  = new \DateTime( );
        $date->setDate( 2014, 10, 01 );
        $date->setTime( 23, 14, 55 );

        $record->setDate( $date );

        $expected = '[Wed Oct 01 23:14:55 EST] [ERROR] [-] This is the error message' . PHP_EOL;

        $this->assertEquals( $expected, $this->formatter->format( $record ) );
    }
}