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
        if (checkCapabilities('mail:loadMail', $_SESSION['USER']->role_id)){ //$_SESSION is used to work with request script
            $db = DB::prepare('SELECT * FROM message WHERE id = ?');
            $db->execute(array($id));
            $result = $db->fetchObject();
                if ($result){
                    $this->id           = $result->id;
                    $this->sender_id    = $result->sender_id;
                    $this->receiver_id  = $result->receiver_id;
                    $this->subject      = $result->subject;
                    $this->message      = $result->message;
                    $this->creation_time = $result->creation_time;
                    $this->status       = $result->status;    
                } else {
                    //$this->institutions[] = NULL;
                } 
        }
    }
    
    /**
     * post Mail
     * @return boolean 
     */
    public function postMail(){
        if (checkCapabilities('mail:postMail', $_SESSION['USER']->role_id)){
            $db = DB::prepare('INSERT INTO message (sender_id,receiver_id,subject,message,status) VALUES (?,?,?,?,0)');
            return $db->execute(array($this->sender_id, $this->receiver_id, $this->subject, $this->message));
        } else {
            return false; 
        } 
    }
    
    /**
     * set status of a message
     * @param int $status
     * @return boolean 
     */
    public function setStatus($status){
       $db = DB::prepare('UPDATE message SET status = ? WHERE id = ?');
       return $db->execute(array($status, $this->id));
    }
}
?>