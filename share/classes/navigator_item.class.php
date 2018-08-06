<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename navigator_item.class.php
* @copyright 2018 joachimdieterich
* @author joachimdieterich
* @date 2018.07.31 11:20
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

class Navigator_item extends Navigator_view {
    
    public $nb_id;
    public $nb_title;
    public $nb_description;
    public $nb_context_id;
    public $nb_reference_id;
    public $nb_position;
    public $nb_width_class;
    public $nb_target_context_id;
    public $nb_target_id;
    public $nb_file_id;
    public $nb_visible;
    
    
    
    public function __construct() {
       
    }
    
    public function load($dependency = 'navigator_block', $id = null){
        //if ($id == null){ $id = $this->id; } //doesn't work 
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
    
    /**
     * Update current navigator_item
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('navigator:update', $USER->role_id);
        $db = DB::prepare('UPDATE navigator_block SET nb_title = ?, nb_description = ?, nb_navigator_view_id = ?, nb_context_id = ?, 
                            nb_reference_id = ?, nb_position = ?, nb_width_class = ?, nb_target_context_id = ?,
                            nb_target_id = ?, nb_file_id = ?, nb_visible = ? WHERE nb_id = ?');
        return $db->execute(array($this->nb_title, $this->nb_description, $this->nb_navigator_view_id, $this->nb_context_id, 
            $this->nb_reference_id, $this->nb_position, $this->nb_width_class, $this->nb_target_context_id,
            $this->nb_target_id, $this->nb_file_id, $this->nb_visible, $this->nb_id));
    }
    
    public function get($navigator_view = false){
        
        $db = DB::prepare('SELECT nb_id FROM navigator_block WHERE nb_navigator_view_id = ? ORDER BY nb_title');
        $db->execute(array($navigator_view));
        
        $r  = array();
        while($result = $db->fetchObject()) { 
            $this->load('navigator_block', $result->nb_id); 
            $r[]  = clone $this;
        } 

        return $r;     
    }
    
}