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
 * @package   RawPHP/RawLog/Formatters/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Formatters\Tests;

use RawPHP\RawLog\Formatters\MailLogFormatter;
use RawPHP\RawLog\Records\MailLogRecord;
use RawPHP\RawLog\Log;

/**
 * MailLogFormatterTest
 *
 * @category  PHP
 * @package   RawPHP/RawLog/Formatters/Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class MailLogFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailLogFormatter
     */
    public $formatter                           = NULL;

    /**
     * Setup before each test.
     */
    public function setUp( )
    {
        $this->formatter = new MailLogFormatter( );
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
        $args = array(
            'heading' => 'Test Heading',
            'title'   => 'Test Title',
            'body' => 'This is the error message',
        );

        $record = new MailLogRecord( Log::LEVEL_ERROR, $args );
        $date  = new \DateTime( );
        $date->setDate( 2014, 10, 01 );
        $date->setTime( 23, 14, 55 );

        $record->setDate( $date );

        $expected =
            "<html>
                <head>
                    <title>Test Title</title>
                </head>
                <body>
                    <h1>Test Title</h1>
                    <h5>Test Heading</h5>
                    <p>This is the error message</p>
                </body>
            </html>";

        $this->assertEquals( $expected, $this->formatter->format( $record ) );
    }
}