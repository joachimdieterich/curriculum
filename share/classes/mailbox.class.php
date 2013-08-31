<?php
require_once 'mail.class.php';

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}
/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename mailbox.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.05.09 21:21
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


class Mailbox {
    /**
     * inbox array
     * @var array 
     */
    public $inbox = array();
     /**
     * outbox array
     * @var array 
     */
    public $outbox = array();
     /**
     * inbox array
     * @var array 
     */
    public $deleted_messages = array();
    
    /**
     * class constructor 
     */
    public function __construct() {
       
    }
    /**
     * load a mail over id
     * @param int $id 
     */
    public function loadMail($id){
        $getMail = new Mail();
        $this->inbox[] = $getMail->loadMail($id);   
    }
    /**
     * load inbox of a user
     * @param int $user_id 
     */
    public function loadInbox($user_id){
        $this->loadMailbox($user_id, 'receiver_id');
    }
    /**
     * load outbox of a user
     * @param int $user_id 
     */
    public function loadOutbox($user_id){
        $this->loadMailbox($user_id, 'sender_id');
    }
    /**
     * load deleted messages of a user
     * @param int $user_id 
     */
    public function loadDeletedMessages($user_id){
        $this->loadMailbox($user_id, 'deleted');
    }
    /**
     * load a mailbox
     * in = inbox
     * out = outbox
     * deleted = all deleted mails of a user
     * @param int $user_id
     * @param string $mailbox 
     */
    public function loadMailbox($user_id, $mailbox = 'in'){
        if ($mailbox != 'deleted'){
            
            $query = sprintf("SELECT *
                        FROM message
                        WHERE %s = '%s' ORDER BY id DESC",
                        mysql_real_escape_string($mailbox),
                        mysql_real_escape_string($user_id));
        } else {
            $query = sprintf("SELECT msg.*
                        FROM message AS msg
                        WHERE msg.sender_id = '%s' OR msg.receiver_id = '%s' 
                        AND msg.status = '-1' 
                        ORDER BY msg.id DESC",
                        mysql_real_escape_string($user_id),
                        mysql_real_escape_string($user_id));
        }
       $result = mysql_query($query);
        if ($result && mysql_num_rows($result)){
            while ($row = mysql_fetch_assoc($result)) {
                $getMail = new Mail();
                $getMail->id                 = $row['id'];
                $getMail->sender_id          = $row['sender_id'];

                $query = sprintf("SELECT username, firstname, lastname FROM users WHERE id = '%s'",
                        mysql_real_escape_string($getMail->sender_id));
                $sender = mysql_query($query);
                if ($sender && mysql_num_rows($sender)){
                    $getMail->sender_username    = mysql_result($sender, 0, "username");
                    $getMail->sender_firstname   = mysql_result($sender, 0, "firstname");
                    $getMail->sender_lastname    = mysql_result($sender, 0, "lastname");
                }
                $getMail->receiver_id        = $row['receiver_id'];
                $query = sprintf("SELECT username, firstname, lastname FROM users WHERE id = '%s'",
                        mysql_real_escape_string($getMail->sender_id));
                $receiver = mysql_query($query);
                if ($receiver && mysql_num_rows($receiver)){
                $getMail->receiver_username  = mysql_result($receiver, 0, "username");
                $getMail->receiver_firstname = mysql_result($receiver, 0, "firstname");
                $getMail->receiver_lastname  = mysql_result($receiver, 0, "lastname");
                }
                $getMail->subject            = $row['subject'];
                $getMail->message            = $row['message'];
                $getMail->creation_time      = $row['creation_time'];
                $getMail->status             = $row['status'];
                switch ($mailbox) {
                    case 'receiver_id': //inbox
                                        $this->inbox[]            = $getMail;
                                        break;
                    case 'sender_id': // outbox
                                        $this->outbox[]           = $getMail;
                                        break;
                        break;
                    case 'deleted':   // deleted messages
                                        $this->deleted_messages[] = $getMail;
                                        break;
                        break;

                    default:
                        break;
                }
            }
            
        }   
    }    
}
?>