<?php
/**
 * License class
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename license.class.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.04.08 12:37:00
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
class License {
    /**
     * ID 
     * @var int
     */
    public $id;
    /**
     * License 
     * @var string
     */
    public $license; 
    
    
    public function get($id = NULL){
        $license = array();
        if ($id != NULL){
            $db = DB::prepare('SELECT * FROM file_license WHERE id = ?');
            $db->execute(array($id));
        } else {
            $db = DB::prepare('SELECT * FROM file_license');
            $db->execute();
        }
            
        while($result = $db->fetchObject()) {
            $this->id         = $result->id;
            $this->license    = $result->license;
            $license[]          = clone $this;       

        
        }
        return $license;
    }

}