<?php  
if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename mail.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.05.08 21:21
 * @license
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or     
 * (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful,       
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
 * GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */

class Mail {
    /**
     * mail id
     * @var int
     */
    public $id;
    /**
     * senders id
     * @var int
     */
    public $sender_id;
    /**
     * senders username
     * @var string 
     */
    public $sender_username;
    /**
     * senders firstname
     * @var string
     */
    public $sender_firstname;
    /**
     * senders lastname
     * @var string
     */
    public $sender_lastname; 
    /**
     * receivers id
     * @var int
     */
    public $receiver_id;
    /**
     * receivers username
     * @var string
     */
    public $receiver_username;
    /**
     * receivers firstname
     * @var string
     */
    public $receiver_firstname;
    /**
     * receivers lastname
     * @var string
     */
    public $receiver_lastname;
           
    /**
     * subject of message
     * @var string 
     */
    public $subject;
    /**
     * message (html-format)
     * @var string 
     */
    public $message;
    /**
     * timestamp of creation
     * @var timestamp 
     */
    public $creation_time;
    /**
     * status
     * @var int 
     */
    public $status;
    
    /**
     * class constructor 
     */
    public function __construct() {
        
    }
    
    /**
     * load mail over id
     * @param int $id 
     */
    public function loadMail($id){
       $query = sprintf("SELECT *
                      FROM message
                      WHERE id = '%s'",
                      mysql_real_escape_string($id));
       $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            $this->id           = mysql_result($result, 0, "id");
            $this->sender_id    = mysql_result($result, 0, "sender_id");
            $this->receiver_id  = mysql_result($result, 0, "receiver_id");
            $this->subject      = mysql_result($result, 0, "subject");
            $this->message      = mysql_result($result, 0, "message");
            $this->creation_time = mysql_result($result, 0, "creation_time");
            $this->status       = mysql_result($result, 0, "status");
            
        } else {
            //$this->institutions[] = NULL;
        }    
       
    }
    
    /**
     * post Mail
     * @return boolean 
     */
    public function postMail(){
        $query = sprintf("INSERT INTO message 
                                    (sender_id,receiver_id,subject,message,status) 
                                    VALUES ('%s','%s','%s','%s',0)",
                                    mysql_real_escape_string($this->sender_id), 
                                    mysql_real_escape_string($this->receiver_id),
                                    mysql_real_escape_string($this->subject),
                                    mysql_real_escape_string($this->message));
        return mysql_query($query);
    }
    
    
    public function setStatus($status){
       $query = sprintf("UPDATE message SET status = %s
                        WHERE id = '%s'",
                        mysql_real_escape_string($status),
                        mysql_real_escape_string($this->id)); 
       return mysql_query($query);
    }
}
?>