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

namespace RawPHP\RawLog\Handlers;

use Monolog\Handler\MailHandler;
use Monolog\Logger;
use RawPHP\RawMail\Mail;
use RawPHP\RawLog\IHandler;
use RawPHP\RawLog\Log;

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
class RawMailHandler extends MailHandler implements IHandler
{
    /**
     * @var Mail
     */
    protected $mailer   = NULL;
    
    /**
     * @var Log
     */
    protected $log      = NULL;
    
    /**
     * Handler constructor.
     * 
     * @param Log  $log    reference to the owner log
     * @param Mail $mail   instance of Mail class
     * @param int  $level  the minimumm level at which this handler will be triggered
     * @param bool $bubble whether the messages that are handled can bubble up the stack or not
     */
    public function __construct( Log &$log, Mail $mail, $level = Logger::DEBUG, $bubble = TRUE )
    {
        parent::__construct( $level, $bubble );
        
        $this->log      = $log;
        $this->mailer   = $mail;
    }
    
    /**
     * Sends the message.
     * 
     * @param string $content the message body
     * @param array  $records log list of key->value pairs
     * 
     * @filter ON_RAW_MAIL_HANDLER_SEND_FILTER
     * 
     * @action ON_RAW_MAIL_HANDLER_SEND_ACTION
     */
    protected function send( $content, array $records )
    {
        $content = $this->log->filter( self::ON_RAW_MAIL_HANDLER_SEND_FILTER, $content, $records );
        
        $this->mailer->setBody( $content );
        $this->mailer->send( );
        
        $this->log->doAction( self::ON_RAW_MAIL_HANDLER_SEND_ACTION );
    }
    
    const ON_RAW_MAIL_HANDLER_SEND_ACTION = 'on_raw_mail_handler_send_action';
    
    const ON_RAW_MAIL_HANDLER_SEND_FILTER = 'on_raw_mail_handler_send_filter';
}