<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename authenticate.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.07.18 17:45
 * @license 
 *
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

class Authenticate {
    /**
     * id
     * @var int
     */
    public $id              = null;
    /**
     * username
     * @var string 
     */
    public $username        = null; 
    /**
     * password
     * @var string 
     */
    public $password        = null; 
    /**
     * token
     * @var string
     */
    public $token           = null; 
    /**
     * real ip of user (as int value)
     * @var int 
     */
    public $ip              = null;
    /**
     * time of creation 
     * @var timestring
     */
    public $creation_time   = null; 
    /**
     * user id of creator
     * @var int
     */
    public $creator_id      = null; 
    /**
     * status id
     * @var int 
     */
    public $status          = null; 
    /**
     * firstname 
     * @var string
     */
    public $firstname       = null; 
    /**
     * lastname
     * @var string 
     */
    public $lastname        = null; 
    /**
     * emailadress
     * @var string
     */
    public $email           = null; 
    /**
     * user id on external plattform
     * @var int
     */
    public $user_external_id = null; 
    /**
     * username of webservice user
     * @var string
     */
    public $ws_username     = null;
 
    
    /**
     * get user via token 
     * @param string $dependency 
     */
    public function getUser($dependency = null){
        switch ($dependency) {
            case 'token':   $db = DB::prepare('SELECT * FROM authenticate WHERE token =?');
                            $db->execute(array($this->token));
                            $result = $db->fetchObject();
                break;
            case 'login':   $db = DB::prepare('SELECT * FROM authenticate WHERE UPPER(username) = UPPER(?) AND password=?');
                            $db->execute(array($this->username, $this->password));
                            $result = $db->fetchObject();
                break;
            case 'username':$db = DB::prepare('SELECT * FROM authenticate WHERE UPPER(username) = UPPER(?)');
                            $db->execute(array($this->username));
                            $result = $db->fetchObject();
                            break;

            default:
                break;
        }

        $this->id               = $result->id;
        $this->username         = $result->username;
        $this->password         = $result->password;
        $this->creation_time    = $result->creation_time;
        $this->creator_id       = $result->creator_id;
        $this->status           = $result->status;           // 1 == User exists, 0 == Register New User
        $this->firstname        = $result->firstname;
        $this->lastname         = $result->lastname;    
        $this->email            = $result->email;    
        $this->user_external_id = $result->user_external_id;    
        $this->ws_username      = $result->ws_username;  
        $this->token            = $result->token;
        $this->ip               = $result->ip;
    }
    
    public function check($ip){
        $db = DB::prepare('SELECT ip FROM authenticate WHERE token = ? AND username = ?');
        $db->execute(array($this->token, $this->username));
        $result = $db->fetchObject();
        if ($result->ip == $ip){return true;} else {return false;}
         
                            
    }
    
}
?>