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