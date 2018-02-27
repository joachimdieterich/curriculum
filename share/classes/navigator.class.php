<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename navigator.class.php
* @copyright 2018 joachimdieterich
* @author joachimdieterich
* @date 2018.02.07 10:41
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

class Navigator {
    public $na_id;
    public $na_title;
    public $na_context_id;
    public $na_reference_id;
    public $na_creation_time;
    public $na_creator_id;
    public $na_file_id;
    
    public $nv_id;
    public $nv_navigator_id;
    
    public $nb_id;
    public $nb_context_id;
    public $nb_reference_id;
    public $nb_position;
    public $nb_width_class;
    public $nb_target_context_id;
    public $nb_target;
    
    
    
    public function __construct() {
       
    }
    
    /*public function add(){
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
    }*/
    
    public function load($dependency = 'navigator_block', $id = null){
        if ($id == null){ $id = $this->id; }
        switch ($dependency) {
            case 'navigator_block':  $db     = DB::prepare('SELECT na.*, nv.*, nb.* FROM navigator AS na, navigator_view AS nv, navigator_block AS nb 
                                                                WHERE nb.nb_id = ? AND na.na_id = nv.nv_navigator_id AND nv.nv_id = nb.nb_navigator_view_id');
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


                break;

            default:
                break;
        }
    }
    
    public function get($navigator_view = false){
        
        $db = DB::prepare('SELECT nb_id FROM navigator_block WHERE nb_navigator_view_id = ?');
        $db->execute(array($navigator_view));
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('navigator_block', $result->nb_id); 
            $r[]  = clone $this;
        } 

        return $r;     
    }
    
    public function getBreadcrumbs($navigator_view_id){
        $b = array();
        do {
            $navigator_view_id = $this->getParentView($navigator_view_id);
            if ($navigator_view_id != false){
                $b[] = clone $this;
            }
        } while ($navigator_view_id != false);
        return $b;
    }
    
    public function getParentView($navigator_view_id){
        $db = DB::prepare('SELECT nb.nb_navigator_view_id, nv.nv_title FROM navigator_block AS nb, navigator_view AS nv 
                            WHERE nb.nb_target = ? AND nb.nb_target_context_id = ? AND nv.nv_id = nb.nb_navigator_view_id');
        $db->execute(array($navigator_view_id, $_SESSION['CONTEXT']['navigator_view']->context_id));
        
        $result = $db->fetchObject();
        //error_log(json_encode($result).' '.$navigator_view_id.' '.$_SESSION['CONTEXT']['navigator_view']->context_id);
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return $result->nb_navigator_view_id;                                                        
        } else { 
            return false; 
        }
    }
    
    public function getChildren($navigator_view_id){
        $b = array();
        do {
            $navigator_view_id = $this->getChildrenBlock($navigator_view_id);
            //error_log($navigator_view_id);
            if ($navigator_view_id != false){
                $b[] = clone $this;
            }
        } while ($navigator_view_id != false);
        return $b;
    }
    
    public function getChildrenBlock($navigator_view_id){
        $db = DB::prepare('SELECT nb.* FROM navigator_block AS nb WHERE nb.nb_navigator_view_id = ? AND nb.nb_target_context_id = ?');
        $db->execute(array($navigator_view_id, $_SESSION['CONTEXT']['navigator_view']->context_id));
        
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            return $result->nb_target;                                                        
        } else { 
            return false; 
        }
        
    }
}