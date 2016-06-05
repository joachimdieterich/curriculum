<?php
/**
 * 
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename absent.class.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.20 14:54
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
class Absent {
    /**
     * ID 
     * @var int
     */
    public $id;
    public $cb_id;
    public $user_id;
    public $user;
    public $reason;
    public $status;
    public $done;
    public $creator_id;


    public function add(){
        global $USER;
        checkCapabilities('absent:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO user_absent (cb_id,user_id,reason,done,status,creator_id) VALUES (?,?,?,?,?,?)');
        return $db->execute(array($this->cb_id, $this->user_id, $this->reason, $this->done,$this->status,$USER->id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('absent:update', $USER->role_id);
        $db = DB::prepare('UPDATE user_absent SET cb_id = ?,user_id = ?, reason = ?, done = ?, status = ?, creator_id = ? WHERE id = ?');
        return $db->execute(array($this->cb_id, $this->user_id, $this->reason, $this->done,$this->status,$USER->id, $this->id));
    }
    
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db         = DB::prepare('SELECT * FROM user_absent WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result     = $db->fetchObject();
        if ($result){
            $user   = new User(); 
            $this->id            = $result->id;
            $this->cb_id         = $result->cb_id;
            $this->user_id       = $result->user_id;
            //$user->load('id', $result->user_id, false);
            $this->user          = $user->resolveUserId($result->user_id);
            $this->reason        = $result->reason;
            $this->status        = $result->status;
            $this->done          = $result->done;
            $this->creator_id    = $result->creator_id;
            $this->creation_time = $result->creation_time;
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
    
    
    public function get($paginator = ''){
        $order_param = orderPaginator($paginator, array()); 
        $db = DB::prepare('SELECT ub.* FROM user_absent AS ub
                                                WHERE ub.cb_id = ? '.$order_param );
                            $db->execute(array($this->cb_id));
        
        while($result = $db->fetchObject()) { 
                $user = new User(); 
                $this->id            = $result->id;
                $this->cb_id         = $result->cb_id;
                $this->user_id       = $result->user_id;
                $user->load('id', $result->user_id, false);
                $this->user          = $user;
                $this->reason        = $result->reason;
                $this->status        = $result->status;
                $this->done          = $result->done;
                $this->creator_id    = $result->creator_id;
                $entrys[]            = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        if (isset($entrys)){
            return $entrys;                    
        } else {
            return array();
        }
    } 

}