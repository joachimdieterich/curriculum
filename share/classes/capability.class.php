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
    public $id                          = null;
    /**
     * name of capability
     * @var string
     */
    public $capability                  = null; 
    /**
     * description of capability
     * @var string
     */
    public $description                 = null;                
    /**
     * type of capability
     * @var string
     */
    public $type                        = null; 
    /**
     * name of component which uses this capability (e.g. config, menu, ...)
     * @var string
     */
    public $component                   = null; 
    
   /**
    * role_id
    * @var int 
    */
    public $role_id                     = null; 
    
 
    /**
     * constructor capability class
     * @param string $dependency
     * @param int $id 
     */
    public function __construct(){
        
    }
    
    /**
     * add capability to db
     * @param string $dependency
     * @param int $id
     * @return boolean
     */
    public function add(){
            $query = sprintf("INSERT INTO capabilities (capability, description, type, component) 
                                    VALUES ('%s','%s','%s','%s')",
                                    mysql_real_escape_string($this->capability),
                                    mysql_real_escape_string($this->description),
                                    mysql_real_escape_string($this->type),
                                    mysql_real_escape_string($this->component)
                                    );
            return mysql_query($query);  
    }
    
    /**
     * delete capability from db
     * @param string $capability
     * @return boolean 
     */
    public function delete($capability = null){
        $query = sprintf("DELETE FROM capabilities WHERE capability = '%s'",
                                mysql_real_escape_string($capability));
        return mysql_query($query); 
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
        $query = sprintf("SELECT permission FROM role_capabilities WHERE capability = '%s' AND role_id = '%s'",
                                mysql_real_escape_string($this->capability),
                                mysql_real_escape_string($this->role_id));
        $result = mysql_query($query); 
        if ($result && mysql_num_rows($result)){
            $perminssion = mysql_result($result, 0, "permission"); 
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
        $query = sprintf("SELECT DISTINCT ca.*, rca.permission, rca.creator_id FROM capabilities AS ca
                            LEFT JOIN role_capabilities as rca ON rca.capability = ca.capability
                            AND rca.role_id = '%s' ORDER BY ca.capability",
                                mysql_real_escape_string($role_id));
        $result = mysql_query($query); 
        if ($result && mysql_num_rows($result)){
            while($row = mysql_fetch_assoc($result)) {  
                $capabilities[] = $row; 
            }
        } else {
            $capabilities = false; 
        }
        
        return $capabilities;
    }
    
}
?>