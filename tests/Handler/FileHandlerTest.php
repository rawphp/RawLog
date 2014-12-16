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
 * @package   RawPHP\RawLog\Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Tests;

use PHPUnit_Framework_TestCase;
use RawPHP\RawLog\Formatter\ErrorLogFormatter;
use RawPHP\RawLog\Handler\FileHandler;
use RawPHP\RawLog\Log;
use RawPHP\RawLog\Record\ErrorLogRecord;

/**
 * FileHandlerTest
 *
 * @category  PHP
 * @package   RawPHP\RawLog\Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class FileHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FileHandler
     */
    public $handler;

    private $_file;

    /**
     * Setup before each test.
     */
    public function setup()
    {
        $this->_file = OUTPUT_DIR . 'log.txt';

        $config = [
            'file'  => $this->_file,
            'level' => Log::LEVEL_DEBUG,
        ];

        $this->handler = new FileHandler( $config );
    }

    /**
     * Cleanup after each test.
     */
    public function tearDown()
    {
        $this->handler = NULL;

        if ( file_exists( $this->_file ) )
        {
            unlink( $this->_file );
        }
    }

    /**
     * Test file handler instantiated correctly.
     */
    public function testFileHandlerInstantiatedCorrectly()
    {
        $this->assertNotNull( $this->handler );
    }

    /**
     * Test logging debug message.
     */
    public function testLogDebugMessage()
    {
        $record = new ErrorLogRecord( Log::LEVEL_DEBUG, 'Test message' );

        $this->handler->setFormatter( new ErrorLogFormatter() );

        $this->handler->handle( $record );

        $this->assertFileExists( $this->_file );

        $result = file_get_contents( $this->_file );

        $this->assertNotFalse( strstr( $result, '[DEBUG]' ) );
        $this->assertNotFalse( strstr( $result, 'Test message' ) );
    }
}