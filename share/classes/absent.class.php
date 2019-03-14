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
    public $status_text;
    public $done;
    public $creator_id;
    public $creator;


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
    
    public function delete(){
        global $USER;
        checkCapabilities('absent:delete', $USER->role_id);
        $this->load();
        $db = DB::prepare('DELETE FROM user_absent WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    public function load($dependency = 'id', $value = null, $usr_obj = false){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db         = DB::prepare('SELECT * FROM user_absent WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result     = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            $user           = new User(); 
            if ($usr_obj == true){
                $user->load('id', $result->user_id, false);
                $this->user     = $user;
            } else {
                $this->user     = $user->resolveUserId($result->user_id);
            }
            $this->creator  = $user->resolveUserId($result->creator_id);
            $this->resolveStatus();
            
            return true;                                                        
        } else { 
            return false; 
        }
    }
    
    public function resolveStatus(){
        switch ($this->status) {
                case 0:      $this->status_text = 'unenschuldigt'; //todo: resolve status over id (extra table?)
                    break;
                case 1:      $this->status_text = 'entschuldigt'; //todo: resolve status over id (extra table?)
                    break;
                default:
                    break;
            }
    }
    
    public function get($paginator = ''){
        $order_param = orderPaginator($paginator, array()); 
        $db = DB::prepare('SELECT SQL_CALC_FOUND_ROWS ub.id FROM user_absent AS ub
                                                WHERE ub.cb_id = ? '.$order_param );
                            $db->execute(array($this->cb_id));
       
        while($result = $db->fetchObject()) { 
            $this->load('id', $result->id, true);
            $entrys[]       = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        if ($paginator != ''){ 
             set_item_total($paginator); //set item total based on FOUND ROWS()
        }
        if (isset($entrys)){
            return $entrys;                    
        } else {
            return array();
        }
    } 

}