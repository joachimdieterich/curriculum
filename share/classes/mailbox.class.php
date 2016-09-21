<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename mailbox.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.05.09 21:21
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Mailbox {
    /**
     * inbox array
     * @var array 
     */
    public $inbox = null;//array();
     /**
     * outbox array
     * @var array 
     */
    public $outbox = null;//array();
     /**
     * inbox array
     * @var array 
     */
    public $deleted_messages = null;//array();
    
    /**
     * class constructor 
     */
    public function __construct() {
       
    }
    
    /**
     * load inbox of a user
     * @param int $user_id 
     */
    public function loadInbox($user_id){
        global $USER; 
        checkCapabilities('mail:loadInbox', $USER->role_id);
        $this->loadMailbox($user_id, 'receiver_id');
    }
    /**
     * load new messages of a user
     * @param int $user_id 
     */
    public function loadNewMessages($user_id){
        global $USER; 
        checkCapabilities('mail:loadInbox', $USER->role_id); //check capability
        $this->loadMailbox($user_id, 'new');
    }
    /**
     * load outbox of a user
     * @param int $user_id 
     */
    public function loadOutbox($user_id){
        global $USER; 
        checkCapabilities('mail:loadOutbox', $USER->role_id);//check capability
        $this->loadMailbox($user_id, 'sender_id');
    }
    /**
     * load deleted messages of a user
     * @param int $user_id 
     */
    public function loadDeletedMessages($user_id){
        global $USER; 
        if (checkCapabilities('mail:loadDeletedMessages', $USER->role_id)){ //check capability
            $this->loadMailbox($user_id, 'deleted');
        }    
    }
    /**
     * load a mailbox
     * in = inbox
     * out = outbox
     * deleted = all deleted mails of a user
     * @param int $user_id
     * @param string $mailbox 
     */
    private function loadMailbox($user_id, $mailbox = 'delete'){ 
        
        switch ($mailbox) {
            case 'new':         $db = DB::prepare('SELECT msg.* FROM message AS msg WHERE msg.receiver_id = ?
                                            AND msg.receiver_status = 0 ORDER BY msg.id DESC');
                                $db->execute(array($user_id));
                break;
            case 'sender_id':
            case 'receiver_id': $db = DB::prepare('SELECT * FROM message WHERE '.$mailbox.' = ? ORDER BY id DESC');
                                $db->execute(array($user_id));
                break;
            case 'deleted':     $db = DB::prepare('SELECT msg.* FROM message AS msg WHERE msg.sender_id = ? OR msg.receiver_id = ?
                                            AND msg.receiver_status = NULL ORDER BY msg.id DESC');
                                $db->execute(array($user_id, $user_id)); 
            default:
                break;
        }

        while ($result = $db->fetchObject()) {
            $getMail                = new Mail();
            $getMail->id            = $result->id;
            $getMail->sender_id     = $result->sender_id;
            
            $db_01  = DB::prepare('SELECT username, firstname, lastname, avatar_id FROM users WHERE id = ?');
            $db_01->execute(array($getMail->sender_id));
            $sender = $db_01->fetchObject();
            if ($sender){
                $getMail->sender_username    = $sender->username;
                $getMail->sender_firstname   = $sender->firstname;
                $getMail->sender_lastname    = $sender->lastname;
                $getMail->sender_file_id     = $sender->avatar_id;
                $getMail->sender_status      = $result->sender_status;
                
            }
            $getMail->receiver_id        = $result->receiver_id;
            $db_02    = DB::prepare('SELECT username, firstname, lastname, avatar_id FROM users WHERE id = ?');
            $db_02->execute(array($getMail->receiver_id)); 
            $receiver = $db_02->fetchObject();
            if ($receiver){
                $getMail->receiver_username  = $receiver->username;
                $getMail->receiver_firstname = $receiver->firstname;
                $getMail->receiver_lastname  = $receiver->lastname;
                $getMail->receiver_file_id   = $receiver->avatar_id;
                $getMail->receiver_status    = $result->receiver_status;
            }
            $getMail->subject            = $result->subject;
            $getMail->message            = $result->message;
            $getMail->creation_time      = $result->creation_time;
            
            switch ($mailbox) {
                case 'receiver_id': //inbox
                                    $this->inbox[]            = $getMail;
                                    break;
                case 'sender_id': // outbox
                                    $this->outbox[]           = $getMail;
                                    break;
                case 'deleted':   // deleted messages
                                    $this->deleted_messages[] = $getMail;
                                    break;
                default:            break;
            }
        }
    }
    
    public function backup($id, $mailbox = 'receiver_id') {
        $this->loadMailbox($id, $mailbox);
       
        $xml = new DOMDocument("1.0", "UTF-8");
        
        switch ($mailbox) {
            case 'receiver_id': $mails = $this->inbox; 
                break;
            case 'sender_id':   $mails = $this->outbox; 
                break;
            // no backup for deleted messages
            default:
                break;
        }
        
        if (is_array($mails)){
            foreach($mails as $value) {
                $message = $xml->createElement("mail");
                foreach($value as $k => $v) {
                    $child = $xml->createElement($k, $v);
                    $message->appendChild($child);
                }
                $xml->appendChild($message);
            }
            $xml->preserveWhiteSpace    = false; 
            $xml->formatOutput          = true;

            return $xml;
        } else {
            return false;
        }
    }
}