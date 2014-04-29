<?php
/**
 * schooltype object can add, update, delete and get data from schooltype db
 * 
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename schooltype.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.09 20:45
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or     
 * (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful,       
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
 * GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
class Schooltype {
    /**
     * id of Schooltype
     * @var int
     */
    public $id = null;
    /**
     * Name of schooltype
     * @var string
     */
    public $schooltype = null; 
    /**
     * Description of schooltype
     * @var string
     */
    public $description = null; 
    /**
     * id of country
     * @var int 
     */
    public $country_id = null; 
    /**
     * id of state
     * @var int 
     */
    public $state_id = null; 
    /**
     * timestamp when schooltype was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this schooltype
     * @var int
     */
    public $creator_id =null; 

   
    
    /**
     * add schooltype
     * @return mixed 
     */
    public function add(){
        global $USER;
        if (checkCapabilities('schooltype:add', $USER->role_id)){
            $db = DB::prepare('SELECT COUNT(id) FROM schooltype WHERE schooltype = ?');                      
            $db->execute(array($this->schooltype));
            if($db->fetchColumn() >= 1) { 
                return false;
            } else {
                $db = DB::prepare('INSERT INTO schooltype (schooltype, description, country_id, state_id, creator_id) 
                                    VALUES (?,?,?,?,?)');
                $result = $db->execute(array($this->schooltype, $this->description, $this->country_id, $this->state_id, $this->creator_id));

                if ($result){
                    return DB::lastInsertId();
                } else return false; 

            }
        }
    }
    
    /**
     * Update schooltype
     * @return boolean 
     */
    public function update(){
        global $USER;
        if (checkCapabilities('schooltype:update', $USER->role_id)){
            $db = DB::prepare('UPDATE schooltype SET schooltype = ?, description = ?, country_id = ?, state_id = ?,creator_id = ? WHERE id = ?');
            return $db->execute(array($this->schooltype, $this->description, $this->country_id, $this->state_id, $this->creator_id, $this->id));
        }
    }
    
    /**
     * Delete schooltype
     * @return mixed 
     */
    public function delete(){
        global $USER;
        if (checkCapabilities('schooltype:delete', $USER->role_id)){
            $db = DB::prepare('SELECT id FROM curriculum WHERE schooltype_id = ?');
            $db->execute(array($this->id));
            $result = $db->fetchObject();
            if ($result){
                return false;
            } else {
                $db = DB::prepare('DELETE FROM schooltype WHERE id = ?');
                return $db->execute(array($this->id));
            } 
        }
    } 
    
    /**
     * Load schooltype with id $this->id 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM schooltype WHERE id= ?');
        $db->execute(array($this->id));
        
        $result = $db->fetchObject();
        $this->schooltype       = $result->schooltype;
        $this->description      = $result->description;
        $this->country_id       = $result->country_id;
        $this->state_id         = $result->state_id;
        $this->creation_time    = $result->creation_time;
        $this->creator_id       = $result->creator_id;
    }
    
    /**
     * Get all availible schooltypes 
     * @return array of schooltype objects 
     */
    public function getSchooltypes(){
        $schooltypes = array();                      //Array of schooltypes
        $db = DB::prepare('SELECT * FROM schooltype');
        $db->execute();
            while($result = $db->fetchObject()) { 
                    $this->id               = $result->id;
                    $this->schooltype       = $result->schooltype;
                    $this->description      = $result->description;
                    $this->country_id       = $result->country_id;
                    $this->state_id         = $result->state_id;
                    $this->creation_time    = $result->creation_time;
                    $this->creator_id       = $result->creator_id;
                    $schooltypes[] = clone $this;        //it has to be clone, to get the object and not the reference
            } 
        if (isset($schooltypes)) {   
            return $schooltypes;
        } else {return false;}
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE schooltype SET creator_id = ?');
        return $db->execute(array($this->creator_id));
    }
}
?>