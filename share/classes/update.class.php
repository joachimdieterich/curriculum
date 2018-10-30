<?php
/**
* Update class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename update.class.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.09.10 16:025
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
class Update {
    public $id;
    public $filename;
    public $description;
    public $status;
    public $log;
    public $timestamp;
    public $timestamp_installed;
    public $user_id;
    public $user;
    
    
    public function add(){
        global $USER;
        $db = DB::prepare('INSERT INTO updates (filename,description,status,timestamp,user_id) VALUES (?,?,?,NOW(),?)');
        $db->execute(array($this->filename, $this->description, $this->status, $USER->id));
    }
    
    public function doUpdate(){
        global $USER;
        $db = DB::prepare('UPDATE updates SET status = ?, log = ?, timestamp_installed = NOW(), user_id = ? WHERE filename = ?');
        return $db->execute(array($this->status, $this->log, $USER->id, $this->filename));
    }
    
    public function load($dependency, $reference){
        $db     = DB::prepare('SELECT ud.* FROM updates AS ud WHERE '.$dependency.' = ?');
        $db->execute(array($reference));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key  = $value; 
            }
            $u = new User();
            $this->user = $u->resolveUserId($this->user_id);
            return true;                                                        
        } else { 
            return false; 
        }
        
    }
    
    public function get(){
        $db = DB::prepare('SELECT ud.id FROM updates AS ud');
        $db->execute(array());
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('id',        $result->id); 
            $r[]  = clone $this;
        } 
        
        return $r;     
    }
    
    

}