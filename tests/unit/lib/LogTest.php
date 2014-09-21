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

use RawPHP\RawLog\Log;
use RawPHP\RawMail\Mail;

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
class LogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Log
     */
    public $log;
    private $_callbackWorks = FALSE;
    
    private $_testMessage = 'test message';
    
    private static $_streamFile;
    private static $_rotateFile;
    
    /**
     * Setup before test suite run.
     */
    public static function setUpBeforeClass( )
    {
        parent::setUpBeforeClass( );
        
        $date = new \DateTime( );
        
        self::$_streamFile = OUTPUT_DIR . 'log.txt';
        self::$_rotateFile = OUTPUT_DIR . 'log-' . $date->format( 'Y-m-d' ) . '.txt';
    }
    
    /**
     * Setup before each test.
     */
    public function setup( )
    {
        global $config;
        
        $this->_callbackWorks = FALSE;
        
        $this->log = new Log( );
        
        $config[ 'mailer' ] = new Mail( );
        $config[ 'mailer' ]->init( $config );
        $config[ 'format_callback' ] = array( $this, 'formatCallback' );
        
        $this->log->init( $config );
    }
    
    /**
     * Cleanup after each test.
     */
    public function tearDown( )
    {
        $this->log->closeHandlers( );
        
        $this->log = NULL;
        $this->_callbackWorks = FALSE;
        
        if ( file_exists( self::$_streamFile ) )
        {
            unlink( self::$_streamFile );
        }
        
        if ( file_exists( self::$_rotateFile ) )
        {
            unlink( self::$_rotateFile );
        }
        
        $this->assertFalse( file_exists( self::$_streamFile ) );
        $this->assertFalse( file_exists( self::$_rotateFile ) );
    }
    
    /**
     * Test log instantiated correctly.
     */
    public function testLogInstantiatedSuccessfully( )
    {
        $this->assertNotNull( $this->log );
    }
    
    /**
     * Test stream log file gets created successfully.
     */
    public function testStreamLogCreatedCorrectly( )
    {
        $this->log->debug( $this->_testMessage );
        
        $this->_testLogContent( self::$_streamFile );
    }
    
    /**
     * Test rotate log file gets created successfully.
     */
    public function testRotateLogCreatedCorrectly( )
    {
        $this->log->debug( $this->_testMessage );
        
        $this->_testLogContent( self::$_rotateFile );
    }
    
    /**
     * Test mail handler filter works.
     */
    public function testRegisterFormatFilter( )
    {
        $this->log->debug( 'test message' );
        
        $this->assertTrue( $this->_callbackWorks );
    }
    
    /**
     * Helper filter callback method.
     * 
     * @param string $content log content
     * @param array  $records log error content attributes
     * 
     * @return string the filtered log content
     */
    public function formatCallback( $content, $records )
    {
        $this->_callbackWorks = TRUE;
        
        return 'blah';
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