<?php
/** 
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename absent.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.20 14:54
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
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            $user           = new User(); 
            $this->user     = $user->resolveUserId($result->user_id);
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
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            $user        = new User();
            $user->load('id', $result->user_id);
            $this->user  = $user;
            $entrys[]    = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        if (isset($entrys)){
            return $entrys;                    
        } else {
            return array();
        }
    } 

}