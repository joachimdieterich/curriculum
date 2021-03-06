<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename help.class.php
* @copyright 2016 joachimdieterich
* @author joachimdieterich
* @date 2016.09.30 15:21
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

class Help {
    public $id;
    public $title;
    public $description;
    public $category;
    public $file_id;
    
    public function __construct($id = null) {
        if ($id != null){ 
            $this->id = $id; 
            $this->load();
        }
    }
    
    public function add(){
        global $USER;
        checkCapabilities('help:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO help (title,description,category,file_id) VALUES (?,?,?,?)');
        return $db->execute(array($this->title, $this->description, $this->category, $this->file_id));
    }
    
    public function update(){
        global $USER;
        checkCapabilities('help:update', $USER->role_id);
        $db = DB::prepare('UPDATE help SET title = ?,description = ?,category = ?,file_id = ? WHERE id = ?');
        return $db->execute(array($this->title, $this->description, $this->category, $this->file_id, $this->id));
    }
    
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('help:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'help.class.php', dirname(__FILE__), 'Delete helpfile: '.$this->title.', file_id: '.$this->file_id);
        $db = DB::prepare('DELETE FROM help WHERE id = ?');
        return $db->execute(array($this->id));
    }
    
    public function load($id = null){
        if ($id == null){ $id = $this->id; }
        $db     = DB::prepare('SELECT he.* FROM help AS he 
                                       WHERE id = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return true;                                                        
        } else { 
            return false; 
        }
        
    }
    
    public function get($search = false){
        global $USER;
        if ($search){
            $db = DB::prepare('SELECT he.id FROM help AS he 
                                            WHERE he.title LIKE ? OR he.description LIKE ? OR he.category LIKE ?
                                            ORDER BY he.title');
            $db->execute(array('%'.$search.'%', '%'.$search.'%', '%'.$search.'%'));
        } else {
            $db = DB::prepare('SELECT he.id FROM help AS he ORDER BY he.title');
            $db->execute();
        }
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load($result->id); 
            $r[]  = clone $this;
        } 

        return $r;     
    }
}