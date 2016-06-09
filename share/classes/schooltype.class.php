<?php
/**
* schooltype class add, update, delete and get data from schooltype db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename schooltype.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.09 20:45
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
class Schooltype {
    /**
     * id of Schooltype
     * @var int
     */
    public $id;
    /**
     * Name of schooltype
     * @var string
     */
    public $schooltype; 
    /**
     * Description of schooltype
     * @var string
     */
    public $description; 
    /**
     * id of country
     * @var int 
     */
    public $country_id; 
    /**
     * id of state
     * @var int 
     */
    public $state_id; 
    /**
     * timestamp when schooltype was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this schooltype
     * @var int
     */
    public $creator_id; 
    
    /**
     * add schooltype
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('schooltype:add', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM schooltype WHERE schooltype = ?');                      
        $db->execute(array($this->schooltype));
        if($db->fetchColumn() >= 1) { 
            return false;
        } else {
            $db     = DB::prepare('INSERT INTO schooltype (schooltype, description, country_id, state_id, creator_id) 
                                   VALUES (?,?,?,?,?)');
            
            if ($db->execute(array($this->schooltype, $this->description, $this->country_id, $this->state_id, $this->creator_id))){
                return DB::lastInsertId();
            } else { return false;} 
        }
    }
    
    /**
     * Update schooltype
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('schooltype:update', $USER->role_id);
        $db = DB::prepare('UPDATE schooltype SET schooltype = ?, description = ?, country_id = ?, state_id = ?,creator_id = ? WHERE id = ?');
        return $db->execute(array($this->schooltype, $this->description, $this->country_id, $this->state_id, $this->creator_id, $this->id));
    }
    
    /**
     * Delete schooltype
     * @return mixed 
     */
    public function delete(){
        global $USER;
        checkCapabilities('schooltype:delete', $USER->role_id);
        $db     = DB::prepare('SELECT id FROM curriculum WHERE schooltype_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false;
        } else {
            $db = DB::prepare('DELETE FROM schooltype WHERE id = ?');
            return $db->execute(array($this->id));
        } 
    } 
    
    /**
     * Load schooltype with id $this->id 
     */
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db     = DB::prepare('SELECT * FROM schooltype WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result = $db->fetchObject();
        if ($result){
            $this->id               = $result->id;
            $this->schooltype       = $result->schooltype;
            $this->description      = $result->description;
            $this->country_id       = $result->country_id;
            $this->state_id         = $result->state_id;
            $this->creation_time    = $result->creation_time;
            $this->creator_id       = $result->creator_id;
            return true;                                                        // wichtig! f. loadImportFormData
        } else { 
            return false; 
        }
    }
    
    /**
     * Get all availible schooltypes 
     * @return array of schooltype objects 
     */
    public function getSchooltypes(){
        $schooltypes = array();                      //Array of schooltypes
        $db          = DB::prepare('SELECT * FROM schooltype');
        $db->execute();
        while($result = $db->fetchObject()) { 
            $this->id               = $result->id;
            $this->schooltype       = $result->schooltype;
            $this->description      = $result->description;
            $this->country_id       = $result->country_id;
            $this->state_id         = $result->state_id;
            $this->creation_time    = $result->creation_time;
            $this->creator_id       = $result->creator_id;
            $schooltypes[]          = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        return $schooltypes;
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