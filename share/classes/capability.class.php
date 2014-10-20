<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename capability.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.10.06 18:02
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
                            AND rca.role_id = ? ORDER BY ca.capability'); 
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