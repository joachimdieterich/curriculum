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
    public $description; 
    public $grade_id;
    public $grade;
    public $curriculum_object;
    public $terminal_object;
    public $enabling_object;
    public $schooltype;
    public $content_object;
    public $context_id;
    public $file_context;
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
            switch ($this->context_id) {
                case $_SESSION['CONTEXT']['terminal_objective']->context_id:
                    $t                          = new TerminalObjective();
                    $t->id                      = $this->reference_id;
                    $t->load();
                    $this->terminal_object      = $t;
                    $c                          = new Curriculum();         
                    $c->id                      = $t->curriculum_id;
                    $c->load();
                    $this->curriculum_object    = $c;
                    $this->schooltype           = $_SESSION['SCHOOLTYPE'][$c->schooltype_id]->schooltype; 
                    $ct     = new Content();
                    $ct->get('reference', $this->id);
                    $this->content_object       = $ct;    
                    break;
                case $_SESSION['CONTEXT']['enabling_objective']->context_id:
                    $e                          = new EnablingObjective();
                    $e->id                      = $this->reference_id;
                    $e->load(); 
                    $this->enabling_object      = $e;
                    $t                          = new TerminalObjective();
                    $t->id                      = $e->terminal_objective_id;
                    $t->load();
                    $this->terminal_object      = $t;
                    $c                          = new Curriculum();         
                    $c->id                      = $t->curriculum_id;
                    $c->load();
                    $this->curriculum_object    = $c;
                    $this->schooltype           = $_SESSION['SCHOOLTYPE'][$c->schooltype_id]->schooltype; 
                    $ct     = new Content();
                    $ct->get('reference', $this->id);
                    $this->content_object       = $ct;    
                    break;

                default:
                    break;
            }
            if (isset($this->grade_id)){
                $this->grade = $_SESSION['GRADE'][$this->grade_id]->grade;
            }
            return true;                                                        
        } else { 
            return false; 
        }    
    }
    
    public function add(){
        global $USER;
        checkCapabilities('reference:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO reference (unique_id, grade_id, context_id, reference_id, creator_id) VALUES (UUID(),?,?,?,?)');
        if ($db->execute(array($this->grade_id, $this->target_context_id, $this->target_reference_id, $USER->id))){
            $this->id               = DB::lastInsertId();  
            $this->load();
            // Add content subscription to target reference
            $content                = new Content();
            $content->content       = $this->description;
            $content->context_id    = $_SESSION['CONTEXT']['reference']->context_id;
            $content->file_context  = $this->file_context;
            $content->reference_id  = $this->id;
            $content->add();
            // Add source reference
            $db = DB::prepare('INSERT INTO reference (unique_id, grade_id, context_id, reference_id, creator_id) VALUES (?,?,?,?,?)');
            $db->execute(array($this->unique_id,  $this->grade_id, $this->source_context_id, $this->source_reference_id, $USER->id));
            // Add content subscription to source reference
            $content->reference_id  = DB::lastInsertId();
            $content->addSubscription();
            
            return true;
        }
    }
   
    public function import(){
        global $USER;
        checkCapabilities('reference:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO reference (unique_id, grade_id, context_id, reference_id, creator_id) VALUES (?,?,?,?,?)');
        if ($db->execute(array($this->unique_id, $this->grade_id, $this->context_id, $this->reference_id, $USER->id))){
            return true;
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
        $db = DB::prepare('SELECT re.id FROM reference AS re WHERE re.context_id = ? AND re.'.$dependency.' = ?');
        $db->execute(array($context_id, $id));
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('id',        $result->id); 
            //$this->load('unique_id', $this->unique_id); //load entry with matching unique_id
            $r = array_merge((array)$r, (array)$this->getUniqueIDs());
            //$r[]  = clone $this;
        } 
        
        return $r;     
    }
    
    public function getUniqueIDs(){
        $db = DB::prepare('SELECT re.id FROM reference AS re WHERE re.unique_id = ?');
        $db->execute(array($this->unique_id));
        $current_obj_id = $this->reference_id;
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('id',        $result->id); 
            if ($this->reference_id == $current_obj_id){ continue; } // don't show reference entry of the current ena
            $r[]  = clone $this;
        } 
        
        return $r; 
    }  
}