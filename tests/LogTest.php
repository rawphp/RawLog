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
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawLog\Tests;

use PHPUnit_Framework_TestCase;
use RawPHP\RawLog\Log;
use RawPHP\RawMail\Mail;

/**
 * This is the logging class.
 *
 * @category  PHP
 * @package   RawPHP\RawLog\Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class LogTest extends PHPUnit_Framework_TestCase
{
    /** @var Log */
    public $log;

    private $_testMessage = 'test message';

    private static $_file;
    private static $_rotateFile;

    /**
     * Setup before each test.
     */
    public function setUp()
    {
        global $config;

        self::$_file       = $config[ 'handlers' ][ 'standard_log' ][ 'file' ];
        self::$_rotateFile = $config[ 'handlers' ][ 'rotate_log' ][ 'file' ];

        $config[ 'mailer' ] = new Mail( $config );

        $this->log = new $config[ 'class' ]( $config );
    }

    /**
     * Cleanup after each test.
     */
    public function tearDown()
    {
        if ( NULL !== $this->log )
        {
            $this->log = NULL;
        }

        if ( file_exists( self::$_file ) )
        {
            unlink( self::$_file );
        }

        if ( file_exists( self::$_rotateFile ) )
        {
            unlink( self::$_rotateFile );
        }

        $this->assertFalse( file_exists( self::$_file ) );
        $this->assertFalse( file_exists( self::$_rotateFile ) );
    }

    /**
     * Test log instantiated correctly.
     */
    public function testLogInstantiatedSuccessfully()
    {
        $this->assertNotNull( $this->log );
        $this->assertEquals( 3, count( $this->log->getHandlers() ) );
    }

    /**
     * Test file handler log file gets created successfully.
     */
    public function testFileLogCreatedCorrectly()
    {
        $this->log->debug( $this->_testMessage );

        $this->_testLogContent( self::$_file );
    }

    /**
     * Test rotate log file gets created successfully.
     */
    public function testRotateLogCreatedCorrectly()
    {
        $this->_testMessage = 'Notice message';

        $this->log->notice( $this->_testMessage );

        $this->_testLogContent( self::$_rotateFile );
    }

    /**
     * Helper method to test log content.
     *
     * @param string $file    file path
     * @param string $message message to test for
     */
    private function _testLogContent( $file, $message = NULL )
    {
        if ( NULL === $message )
        {
            $message = $this->_testMessage;
        }

        $this->assertFileExists( $file );

        $content = file_get_contents( $file );

        $this->assertContains( $message, $content );

        $lines = explode( '\n', $content );

        $this->assertEquals( 1, count( $lines ) );
    }
}