<?php
/**
 * Context class
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename context.class.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.04.08 12:53:00
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
class Context {
    /**
     * ID 
     * @var int
     */
    public $id;
    /**
     * context 
     * @var string
     */
    public $context; 
    public $context_id; 
    
    public $description; 
    
    
    public function get($id = NULL){
        $context = array();
        if ($id != NULL){
            $db = DB::prepare('SELECT * FROM file_context WHERE id = ?');
            $db->execute(array($id));
        } else {
            $db = DB::prepare('SELECT * FROM file_context');
            $db->execute();   
        } 
        while($result = $db->fetchObject()) {
            //$values[] = array('value' => $result->id, 'label' => $result->description);
            $this->id          = $result->id;
            $this->context     = $result->context;
            $this->description = $result->description;
            $context[]         = clone $this;   
        }
        return $context;
        
    }
    
    public function resolve($dependency = 'context', $id){
        $db = DB::prepare('SELECT * FROM context WHERE '.$dependency.' = ?');
        $db->execute(array($id));
        $result = $db->fetchObject();
        
        if ($result){
            $this->id            = $result->id;
            $this->context       = $result->context;
            $this->context_id    = $result->context_id;
            $this->path          = $result->path;
            return true;
        } else {
            return false;
        }
    }
    

}