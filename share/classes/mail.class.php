<?php  
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename mail.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.05.08 21:21
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
    public $sender_file_id; 
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
    public $receiver_file_id;
           
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
     * sender status
     * @var int 
     */
    public $sender_status;
    /**
     * receiver status
     * @var int 
     */
    public $reveicer_status;
    
    /**
     * class constructor 
     */
    public function __construct() {
        
    }
    
    /**
     * load mail over id
     * @param int $id 
     */
    public function loadMail($id, $set_status = false){
        checkCapabilities('mail:loadMail', $_SESSION['USER']->role_id); //$_SESSION is used to work with request script
        $db = DB::prepare('SELECT * FROM message WHERE id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            $this->id               = $result->id;
            $this->sender_id        = $result->sender_id;
            $this->receiver_id      = $result->receiver_id;
            $this->subject          = $result->subject;
            $this->message          = $result->message;
            $this->creation_time    = $result->creation_time;
            $this->sender_status    = $result->sender_status;  
            $this->receiver_status  = $result->receiver_status;  
            if ($set_status){
                if ($this->receiver_id == $_SESSION['USER']->id){ 
                    $this->setStatus('receiver', true);
                } else if ($this->sender_id == $_SESSION['USER']->id) {
                    $this->setStatus('sender', true);
                }
            }
        } 
    }
    
    public function loadCorrespondence($id, $sender_id, $receiver_id, $dependency = 'mail'){
        switch ($dependency) {
            case 'mail':    checkCapabilities('mail:loadMail', $_SESSION['USER']->role_id); //$_SESSION is used to work with request script
                            $db = DB::prepare('SELECT * FROM message WHERE (id < ? AND sender_id = ? AND receiver_id= ?) OR (id < ? AND sender_id = ? AND receiver_id = ?) ORDER BY creation_time DESC');
                            $db->execute(array($id, $sender_id, $receiver_id, $id, $receiver_id, $sender_id));
            break;
            case 'recent':  checkCapabilities('mail:loadMail', $_SESSION['USER']->role_id); //$_SESSION is used to work with request script
                            $db = DB::prepare('SELECT * FROM message WHERE sender_id = ? OR receiver_id= ? ORDER BY creation_time DESC LIMIT ?');
                            $db->execute(array($sender_id, $receiver_id, $id,));
            break;
        }
        $user = new User();
        while ($result = $db->fetchObject()) {
            $this->id               = $result->id;
            $this->sender_id        = $result->sender_id;
            
            $user->load('id', $this->sender_id, false);
            $this->sender_firstname = $user->firstname;
            $this->sender_lastname  = $user->lastname;
            $this->sender_file_id   = $user->avatar_id;
            
            $this->receiver_id      = $result->receiver_id;
            $user->load('id', $this->receiver_id, false);
            $this->receiver_firstname = $user->firstname;
            $this->receiver_lastname  = $user->lastname;
            $this->receiver_file_id   = $user->avatar_id;
            
            $this->subject          = $result->subject;
            $this->message          = $result->message;
            $this->creation_time    = $result->creation_time;
            $this->sender_status    = $result->sender_status;  
            $this->receiver_status  = $result->receiver_status;  
            $mails[]                = clone $this;
        } 
        
        if (isset($mails)){
            return $mails;
        } else {
            return false;
        }
    }
    
    
    public function delete($dependency = 'mail'){
        global $USER;
        checkCapabilities('mail:delete', $USER->role_id);
        switch ($dependency) {
            case 'mail':        $db = DB::prepare('DELETE FROM message WHERE id = ?');
                                return $db->execute(array($this->id));
                break;
            case 'obsolete':    $db = DB::prepare('DELETE FROM message WHERE subject = ? AND sender_id = ? AND receiver_id <> ?');
                                return $db->execute(array('Passwort vergessen', $this->sender_id, $this->receiver_id));
                break;

            default:
                break;
        }
        
    }
    
    /**
     * post Mail
     * @return boolean 
     */
    public function postMail($dependency = 'person', $id = null){
        global $CFG;
        if ($dependency != 'reset'){
            checkCapabilities('mail:postMail', $_SESSION['USER']->role_id); // User kann per cronjob festgelegt sein, daher $_SESSION 
        } else {
            $dependency = 'person';
        }
        
        if ($CFG->settings->resetPW == 'email'){
            $email              = new PHPMailer();
            $email->isSMTP();                                      // Set mailer to use SMTP
            $email->Host        = $CFG->email_Host;                // Specify main and backup SMTP servers
            $email->SMTPAuth    = $CFG->email_SMTPAuth;            // Enable SMTP authentication
            $email->Username    = $CFG->email_Username;            // SMTP username
            $email->Password    = $CFG->email_Password;            // SMTP password
            $email->SMTPSecure  = $CFG->email_SMTPSecure;          // Enable TLS encryption, `ssl` also accepted
            $email->Port        = $CFG->email_Port;                // TCP port to connect to
        }
        
        switch ($dependency) {
            case 'person':  switch ($CFG->settings->resetPW) {
                                case 'email':   $u = new User();
                                                $u->load('id',$this->sender_id, false);
                                                $u->set('confirmed', 2); //set confirmed to reset PW
                                                $email->CharSet = 'UTF-8';
                                                $email->setFrom($CFG->email_Username, $CFG->app_title);
                                                $email->addAddress($u->email);                          // Add a recipient
                                                $email->isHTML(true);                                   // Set email format to HTML

                                                $email->Subject = $this->subject;
                                                $email->Body    = $this->message;
                                                $email->AltBody = strip_tags($this->message);

                                                if(!$email->send()) {
                                                    error_log('Message could not be sent.');
                                                    error_log('Mailer Error: ' . $email->ErrorInfo);
                                                } else {
                                                    return true;
                                                }
                                    break;

                                default:    $db = DB::prepare('INSERT INTO message (sender_id,receiver_id,subject,message,sender_status,receiver_status) VALUES (?,?,?,?,1,0)');
                                            return $db->execute(array($this->sender_id, $this->receiver_id, $this->subject, $this->message)); 
                                    break;
                            }
                           
                break;
            case 'group':  $user = new User();
                           $group_members = $user->getGroupMembers('group', $id);
                           foreach ($group_members as $value) {
                              $db = DB::prepare('INSERT INTO message (sender_id,receiver_id,subject,message,sender_status,receiver_status) VALUES (?,?,?,?,1,0)');
                              if (!$db->execute(array($this->sender_id, $value, $this->subject, $this->message))){
                                  $error = true;
                              } 
                           }
                           if (isset($error)){
                               return true;
                           }
                break;

            default:
                break;
        }
        
    }
    
    /**
     * set status of a message
     * @param int $status
     * @return boolean 
     */
    public function setStatus($field = 'receiver', $status){
       $db = DB::prepare('UPDATE message SET '.$field.'_status = ? WHERE id = ?');
       return $db->execute(array($status, $this->id));
    }
}