<?php
/**
 * Log class can add, update, delete and get data from log db
 * 
 * @example
 * // Add new objective <br>
 * $new_objective = new Objective(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename objective.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 21:00
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
class Log {
    /**
     * ID 
     * @var int
     */
    public $id = null;
    /**
     * Timestamp 
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User 
     * @var int
     */
    public $user_id = null; 
    /**
     * name of User 
     * @var string
     */
    public $username = null; 
    /**
     * ip of user
     * @var string 
     */
    public $ip = null;
    /**
     * action
     * @var type 
     */
    public $action = null; 
    /**
     * current url
     * @var string
     */
    public $url = null;
    /**
     * Info
     * @var string
     */
    public $info = null; 

    /**
     * Add Log
     * @param int $user_id
     * @param string $action
     * @param string $url
     * @param string $info
     * @return boolean 
     */
    public function add($user_id, $action, $url, $info){
    $query = sprintf("INSERT INTO log (creation_time,user_id,ip,action,url,info) VALUES (NOW(),'%s','%s','%s','%s','%s')",
                    mysql_real_escape_string($user_id),
                    mysql_real_escape_string($_SERVER['REMOTE_ADDR']),
                    mysql_real_escape_string($action),
                    mysql_real_escape_string($url),
                    mysql_real_escape_string($info));
    return mysql_query($query);	
    }
    
    /**
     * Update log
     * @return boolean 
     */
    public function update(){

    }
    
    /**
     * Delete log
     * @return mixed 
     */
    public function delete(){

    } 
    
    /**
     * Load log
     */
    public function load(){
  
    }
    
    public function getLogs() {
        $query = sprintf("SELECT lg.*, us.username  
                            FROM log AS lg, users AS us
                            WHERE lg.user_id = us.id"); //DATE(creation_time) = CURDATE()
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                $this->id                = $row["id"];
                $this->creation_time     = $row["creation_time"];
                $this->user_id           = $row["user_id"];   
                $this->username          = $row["username"];   
                $this->ip                = $row["ip"];   
                $this->action            = $row["action"];   
                $this->url               = $row["url"];   
                $this->info              = $row["info"];   
                $log[]                   = clone $this; 
            } 
        }  
        
        if (isset($log)){
            return $log;
        } else { return false;}
    }
    
}
?>