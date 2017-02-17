<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename comment.class.php
* @copyright 2017 joachimdieterich
* @author joachimdieterich
* @date 2017.01.24 15:58
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

class Comment {
    public $id;
    public $reference_id;
    public $context;
    public $context_id;
    public $parent_id; //parent comment_id
    public $text;
    public $creator_id;
    public $creation_time;
    public $likes;
    public $dislikes;
    public $status; 
    
    public $comment; // array of sub comment
   
    
    public function __construct($id = null) {
        if ($id != null){ 
            $this->id = $id; 
            $this->load();
        }
    }
    
    public function load($id = null){
        global $USER;
        if ($id == null){ $id = $this->id; }
        $db     = DB::prepare('SELECT cm.* FROM comments AS cm WHERE cm.id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key  = $value; 
            }
            return true;                                                        
        } else { 
            return false; 
        }
        
    }
    
    public function add(){
        global $USER;
        checkCapabilities('comment:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO comments (text,reference_id,context_id, parent_id, creator_id) VALUES (?,?,?,?,?)');
        if ($db->execute(array($this->text, $this->reference_id, $this->context_id, $this->parent_id, $USER->id))){
            return DB::lastInsertId();
        } else {
            return false;
        }
        
    }
   
    public function update(){
        global $USER;
        checkCapabilities('comment:update', $USER->role_id);
        $db = DB::prepare('UPDATE comments SET text = ? WHERE id = ? AND creator_id = ?');
        if ($db->execute(array($this->text, $this->id, $USER->id))){
            return true;
        } else { return false;}
    }
    
    public function delete(){
        global $USER;
        checkCapabilities('comment:delete', $USER->role_id);
        $db = DB::prepare('SELECT id FROM comments WHERE parent_id = ?');
        $db->execute(array($this->id));
        if($db->fetchColumn() > 0) { 
            $_SESSION['PAGE']->message[] = array('message' => 'Kommentar kann nicht gelöscht werden, da es bereits Antworten zum Kommentar gibt.', 'icon' => 'fa-exclamation-triangle text-danger');
        } else {
            $db = DB::prepare('DELETE FROM comments WHERE id = ?');
            return $db->execute(array($this->id));
        }
    }
    
    public function get($dependency, $id = false){
        global $USER;
        switch ($dependency) {
            case 'id':          $db = DB::prepare('SELECT cm.id FROM comments AS cm WHERE cm.id = ?');
                                $db->execute(array($id));
                break;
            case 'reference':   $db = DB::prepare('SELECT cm.id FROM comments AS cm, context AS co '
                                                . 'WHERE cm.reference_id = ? '
                                                . 'AND cm.context_id = co.context_id AND co.context = ? AND cm.parent_id IS NULL ORDER BY creation_time');
                                $db->execute(array($this->reference_id, $this->context));
            
            default:
                break;
        }
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load($result->id); 
            $this->comment = $this->getSubComment($this->id); 
            $r[]           = clone $this;
           
        } 
        
        return $r;     
    }
    
    public function set($dependency = 'likes', $id = null){
        if ($id == null){
            $id = $this->id;
        } 
        
        $db = DB::prepare('UPDATE comments SET '.$dependency.' = ? WHERE id = ?');
        if ($db->execute(array($this->$dependency, $id))){
            return true;
        } else { return false;}
        
    }
    
    public function getSubComment($parent_id){
        $db1 = DB::prepare('SELECT cm.* FROM comments AS cm WHERE cm.parent_id = ? AND cm.id != ?');
        $db1->execute(array($parent_id, $parent_id));
        $r  = array();
        while($result = $db1->fetchObject()) { 
            if (!empty($result->parent_id)){
                $result->comment = $this->getSubComment($result->id); 
                $r[]             = $result;
            }
        }
        return $r;
    }
}