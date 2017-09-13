<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename reference.class.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.09.09 16:08
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

class Reference {
    public $id;
    public $unique_id;
    public $context_id;
    public $reference_id;
    public $creation_time;
    public $creator_id;
    
    public $target_context_id;          // only needed to add reference
    public $target_reference_id;        // only needed to add reference
    public $source_context_id;          // only needed to add reference
    public $source_reference_id;        // only needed to add reference
    

    public function __construct($id = null) {
        if ($id != null){ 
            $this->id = $id; 
            $this->load();
        }
    }
    
    public function load($dependency = 'id', $id = null){
        if ($id == null){ $id = $this->id; }
        switch ($dependency) {
            case 'unique_id':  $db     = DB::prepare('SELECT re.* FROM reference AS re WHERE re.'.$dependency.' = ? AND re.reference_id <> ?');
                               $db->execute(array($id, $this->reference_id));
                break;

            default:           $db     = DB::prepare('SELECT re.* FROM reference AS re WHERE re.'.$dependency.' = ?');
                               $db->execute(array($id));
                break;
        }
       
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
        checkCapabilities('reference:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO reference (unique_id,context_id,reference_id, creator_id) VALUES (UUID(),?,?,?)');
        if ($db->execute(array($this->target_context_id, $this->target_reference_id, $USER->id))){
            $this->id = DB::lastInsertId();  
            $this->load();
            $db = DB::prepare('INSERT INTO reference (unique_id,context_id,reference_id, creator_id) VALUES (?,?,?,?)');
            return $db->execute(array($this->unique_id, $this->source_context_id, $this->source_reference_id, $USER->id));
        }
    }
   
    
    public function update(){
        global $USER;
        checkCapabilities('reference:update', $USER->role_id);
        //* not yet implemented *//
    }
    
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('reference:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'reference.class.php', dirname(__FILE__), 'Delete reference: '.$this->unique_id.', curriculum_id: '.$this->curriculum_id.', creator_id: '.$this->creator_id);
        $db = DB::prepare('DELETE FROM reference WHERE id = ?');
        return $db->execute(array($this->id));
    }
    
    public function get($dependency, $context_id, $id){
        global $USER;
        $db = DB::prepare('SELECT re.id FROM reference AS re WHERE re.context_id = ? AND re.'.$dependency.' = ?');
        $db->execute(array($context_id, $id));
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('id',        $result->id); 
            $this->load('unique_id', $this->unique_id); //load entry with matching unique_id
            $r[]  = clone $this;
        } 
        
        return $r;     
    }
}