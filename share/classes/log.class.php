<?php
/**
 * Log class can add, update, delete and get data from log db
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename log.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 21:00
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
class Log {
    /**
     * ID 
     * @var int
     */
    public $id;
    /**
     * Timestamp 
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User 
     * @var int
     */
    public $user_id; 
    /**
     * name of User 
     * @var string
     */
    public $username; 
    /**
     * ip of user
     * @var string 
     */
    public $ip;
    /**
     * action
     * @var type 
     */
    public $action; 
    /**
     * current url
     * @var string
     */
    public $url;
    /**
     * Info
     * @var string
     */
    public $info; 

    /**
     * Add Log
     * @param int $user_id
     * @param string $action
     * @param string $url
     * @param string $info
     * @return boolean 
     */
    public function add($user_id, $action, $url, $info){
        $db = DB::prepare('INSERT INTO log (creation_time,user_id,ip,action,url,info) VALUES (NOW(),?,?,?,?,?)');
        return $db->execute(array($user_id, $_SERVER['REMOTE_ADDR'], $action, $url, $info));
    }
    
    public function getLogs($paginator = '') {
        global $USER;
        checkCapabilities('log:getLogs', $USER->role_id);
        $order_param    = orderPaginator($paginator); 
        $log            = array();
        $db             = DB::prepare('SELECT lg.*, us.username FROM log AS lg, users AS us WHERE lg.user_id = us.id '.$order_param );
        $db->execute();
        while($result = $db->fetchObject()) { 
            $this->id                = $result->id;
            $this->creation_time     = $result->creation_time;
            $this->user_id           = $result->user_id;
            $this->username          = $result->username;
            $this->ip                = $result->ip; 
            $this->action            = $result->action;
            $this->url               = $result->url;   
            $this->info              = $result->info;   
            $log[]                   = clone $this; 
        }           
        return $log;
    }

}