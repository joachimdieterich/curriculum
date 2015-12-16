<?php
/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename country.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.24 11:36
 * @license
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */

class Country {   
    /**
     * id of country, default null
     * @var int 
     */
    public $id;
    /**
     * internation country code
     * @var string 
     */
    public $code = null;
    /**
     * english country name
     * @var string 
     */
    public $en; 
    /**
     * german country name
     * @var string
     */
    public $de; 

    
    /**
     * load countries
     * @param int $id
     * @return array 
     */
    public function load($id) {
        $countries = array();
        $db = DB::prepare('SELECT * FROM countries WHERE id = ? ORDER BY de ASC');
        $db->execute(array($id));
        while($result = $db->fetchObject()) { 
            $this->id    = $result->id;   
            $this->code  = $result->code;   
            $this->en    = $result->en;   
            $this->de    = $result->de; 
            $countries[] = clone $this; 
        } 
        
        return $countries;
    }
    
    /**
     * returns array of countries
     * @return array 
     */
    public function getCountries(){
        $countries = array();
        $db = DB::prepare('SELECT * FROM countries ORDER BY de ASC');
        $db->execute();
        while($result = $db->fetchObject()) { 
            $this->id    = $result->id;   
            $this->code  = $result->code;   
            $this->en    = $result->en;   
            $this->de    = $result->de; 
            $countries[] = clone $this; 
        }
        
        return $countries;
    }    
}