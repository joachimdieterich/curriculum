<?php
/**
* Rolle hinzufügen, bearbeiten, löschen, überprüfen... 
* 
* @package core
* @filename capability.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.10.06 18:02
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


class Capability {
    /**
     * id
     * @var int
     */
    public $id;
    /**
     * name of capability
     * @var string
     */
    public $capability; 
    /**
     * description of capability
     * @var string
     */
    public $description;                
    /**
     * type of capability
     * @var string
     */
    public $type; 
    /**
     * name of component which uses this capability (e.g. config, menu, ...)
     * @var string
     */
    public $component; 
   /**
    * role_id
    * @var int 
    */
    public $role_id;    
    /**
     * constructor capability class
     */
    
    public function __construct(){
        
    }
    
    /**
     * add capability to db
     * @return boolean
     */
    public function add(){
        $db = DB::prepare('INSERT INTO capabilities (capability, description, type, component) VALUES (?,?,?,?)'); 
        return $db->execute(array($this->capability, $this->description, $this->type, $this->component));
    }
    
    /**
     * delete capability from db
     * @param string $capability
     * @return boolean 
     */
    public function delete($capability = null){
        $db = DB::prepare('DELETE FROM capabilities WHERE capability = ? '); 
        return $db->execute(array($capability));
    }
    
    /**
     * update capability in db
     * @param string $capability
     * @return boolean
     */
    public function update($capability = null){    
        
    }   
    
    /**
     * checks capability 
     * @return boolean 
     */
    public function checkCapability(){
        $db = DB::prepare('SELECT permission FROM role_capabilities WHERE capability = ? AND role_id = ?'); 
        $db->execute(array($this->capability, $this->role_id));
        $result = $db->fetchObject();
        if ($result) {
             $perminssion = $result->permission; 
        } else {
             $perminssion = false; 
        }
        return $perminssion;
    }
    /**
     * returns all capabilities for a role_id
     * @param int $role_id
     * @return array 
     */
    public function getCapabilities($role_id){
        $db = DB::prepare('SELECT DISTINCT ca.*, rca.permission, rca.creator_id FROM capabilities AS ca
                            LEFT JOIN role_capabilities as rca ON rca.capability = ca.capability
                            AND rca.role_id = ? ORDER BY ca.capability '); 
        $db->execute(array($role_id));
        
        while($result = $db->fetchObject()) {  
            $capabilities[] = $result; 
        }
        
        if (isset($capabilities)){
            return $capabilities;
        } else {
            return false;
        }   
    } 
}
?>