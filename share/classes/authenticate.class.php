<?php
/**
 *
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
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
            case 'token':   $query = sprintf("SELECT * FROM authenticate WHERE token ='%s'",
                                                        mysql_real_escape_string($this->token));
                            $result = mysql_query($query);
                            $this->id               = mysql_result($result, 0, "id");
                            $this->username         = mysql_result($result, 0, "username");
                            $this->password         = mysql_result($result, 0, "password");
                            $this->creation_time    = mysql_result($result, 0, "creation_time");
                            $this->creator_id       = mysql_result($result, 0, "creator_id");
                            $this->status           = mysql_result($result, 0, "status");           // 1 == User exists, 0 == Register New User
                            $this->firstname        = mysql_result($result, 0, "firstname");
                            $this->lastname         = mysql_result($result, 0, "lastname");    
                            $this->email            = mysql_result($result, 0, "email");    
                            $this->user_external_id = mysql_result($result, 0, "user_external_id");    
                            $this->ws_username      = mysql_result($result, 0, "ws_username");  
                break;
            case 'login':   $query = sprintf("SELECT * FROM authenticate WHERE UPPER(username) = UPPER('%s') AND password='%s'",
                                            mysql_real_escape_string($this->username),
                                            mysql_real_escape_string($this->password));
                            $result = mysql_query($query);
                            $this->id               = mysql_result($result, 0, "id");
                            $this->username         = mysql_result($result, 0, "username");
                            $this->password         = mysql_result($result, 0, "password");
                            $this->creation_time    = mysql_result($result, 0, "creation_time");
                            $this->creator_id       = mysql_result($result, 0, "creator_id");
                            $this->status           = mysql_result($result, 0, "status");           // 1 == User exists, 0 == Register New User
                            $this->firstname        = mysql_result($result, 0, "firstname");
                            $this->lastname         = mysql_result($result, 0, "lastname");    
                            $this->email            = mysql_result($result, 0, "email");    
                            $this->user_external_id = mysql_result($result, 0, "user_external_id");    
                            $this->ws_username      = mysql_result($result, 0, "ws_username"); 
                break;

            default:
                break;
        }
    }
}
?>