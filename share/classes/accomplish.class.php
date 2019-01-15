<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename accomplishe.class.php
* @copyright 2019 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.01.13 20:30
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

class Accomplish {
    public $id;
    public $reference_id;
    public $user_id;
    public $status_id;
    public $accomplished_time;
    public $creator_id;
    public $context_id;
    public $token;
    
    public function __construct() {
        
    }
    
    public function set($teacher) {
        global $USER;
   
        $db0    = DB::prepare('SELECT * FROM user_accomplished WHERE reference_id = ? AND user_id = ? AND context_id = ?');
        $db0->execute(array($this->reference_id, $this->user_id, $this->context_id));
        $result = $db0->fetchObject();
        if ($result){ //if entry exists   
            if ($teacher){
                $status_id = substr($result->status_id, 0,1).$this->status_id;
                $user_id = $USER->id;
            } else {
                $status_id = $this->status_id.substr($result->status_id, 1,1);
                $user_id = $result->creator_id; //Keep teachers ID
            } 
            $db = DB::prepare('UPDATE user_accomplished SET status_id = ?, accomplished_time = CURRENT_TIMESTAMP,  creator_id = ? WHERE reference_id = ? AND user_id = ? AND context_id = ?');
            if ($db->execute(array($status_id, $user_id, $this->reference_id, $this->user_id, $this->context_id))){
                return $status_id;
            }
        } else {
            if ($teacher){
                $status_id = 'x'.$this->status_id;
            } else {
                $status_id = $this->status_id.'x';
            }
            $db = DB::prepare('INSERT INTO user_accomplished (status_id, reference_id, user_id, creator_id, context_id,accomplished_time) VALUES (?,?,?,?,?,CURRENT_TIMESTAMP)');
            if ($db->execute(array($status_id, $this->reference_id, $this->user_id, $USER->id, $this->context_id))){
                return $status_id;
            }
        }
    }
    
    
}
