<?php
/**
* Book Class
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename book.class.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.20.26 13:57
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
class Book {

    public $id;
    public $title; 
    public $description; 
    public $timecreated; 
    public $creator_id; 
    public $creator; 
    public $content_id;
    public $context_id;
    public $file_context;
    
   
    public function add(){
        global $USER;
        $db = DB::prepare('INSERT INTO book (title,description,timecreated,creator_id) VALUES (?,?,NOW(),?)');
        if($db->execute(array($this->title, $this->description, $USER->id))){
            $this->id = DB::lastInsertId(); 
            return $this->id;
        } else {
            return false;
        }
    }
    
    public function update(){
        global $USER;
        $db = DB::prepare('UPDATE book SET title = ?, description = ? WHERE id = ?');
        return $db->execute(array($this->title, $this->description, $this->id));
    }
    
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('book:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'book.class.php', dirname(__FILE__), 'Delete book: '.$this->title.', creator_id: '.$this->creator_id);
        $db = DB::prepare('DELETE FROM book WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT bo.* FROM book AS bo WHERE bo.'.$dependency.' = ?');
        $db->execute(array($v));
        $result     = $db->fetchObject();
        $user       = new User();
        
        if ($result){
            $this->id            = $result->id;
            $this->title         = $result->title;
            $this->description   = $result->description;
            $this->timecreated   = $result->timecreated;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            return true;                                                        
        } else { 
            return false; 
        }
    }
       
    public function get($dependency = 'navigator', $id = null, $order = "ORDER by bo.timecreated ASC"){
        if ($id == null){
            $id = $this->id;
        }
        $entrys = array();                      //Array of content
         
        switch ($dependency) {
            default:         $db = DB::prepare('SELECT bo.*, cts.context_id, cts.content_id, cts.file_context FROM book AS bo, content_subscriptions AS cts, context AS co
                                                        WHERE co.context = "'.$dependency.'"
                                                        AND co.context_id = cts.context_id
                                                        AND cts.reference_id = ?
                                                        AND cts.reference_id = bo.id '.$order);
                            $db->execute(array($id));
                break;
        }
        
        $user       = new User();
        while($result = $db->fetchObject()) { 
            $this->id            = $result->id;
            $this->title         = $result->title;
            $this->description   = $result->description;
            $this->timecreated   = $result->timecreated;
            $this->creator_id    = $result->creator_id;
            $this->creator       = $user->resolveUserId($result->creator_id);
            $this->context_id    = $result->context_id;
            $this->content_id    = $result->content_id;
            $this->file_context  = $result->file_context;
            //$this->load();
            $entrys[]            = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        
        return $entrys;
    }
    
    public function addSubscription(){
        global $USER;
        checkCapabilities('content:add', $USER->role_id);
        $db     = DB::prepare('SELECT COUNT(id) FROM content_subscriptions WHERE content_id = ? AND context_id = ? AND file_context = ? AND reference_id = ?');
        $db->execute(array($this->id, $this->context_id, $this->file_context, $this->reference_id));
        $count  = $db->fetchColumn();
        if ($count > 0){
            $_SESSION['PAGE']->message[] = array('message' => 'Referenz ist bereits verknüpft', 'icon' => 'fa-link text-warning');
            return false;
        } else {
            $db = DB::prepare('INSERT INTO content_subscriptions (content_id,context_id,file_context,reference_id,timecreated,status,creator_id) VALUES (?,?,?,?,NOW(),?,?)');
            return $db->execute(array($this->id, $this->context_id, $this->file_context, $this->reference_id,1,$USER->id));
        } 
    }
   
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE book SET  creator_id = ?');        
        
        return $db->execute(array($this->creator_id));
    }
}