<?php
if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename country.class.php 
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.24 11:36
 * @license 
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

class State extends Country { 
    /**
     * states id
     * @var id 
     */
    public $id = null; 
    /**
     * state name
     * @var string
     */
    public $state = null; 
    /**
     * country code
     * @var string
     */
    public $country_code = null; 
    
    
    /**
     * load States 
     */
    public function getStates($dependency = null, $id = null) {
        switch ($dependency) {
            case null: $query = sprintf("SELECT * FROM state WHERE country_code = '%s' ORDER BY state ASC",
                                      mysql_real_escape_string(parent::$this->code));        
                break;
            
            case 'profile': $query = sprintf("SELECT sta.* FROM state AS sta, countries AS cou 
                                            WHERE sta.country_code = cou.code 
                                            AND cou.id = '%s' ORDER BY sta.state ASC",
                                      mysql_real_escape_string($id));

            default:
                break;
        }
        $result  = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
                        while($row = mysql_fetch_assoc($result)) { 
                            $this->id            = $row["id"];
                            $this->state         = $row["state"];
                            $this->country_code  = $row["country_code"];
                            $states[] = clone $this; 
                        } 
                        } else { // no states for current country available
                            $this->id            = '-1';
                            $this->state         = '---';
                            $this->country_code  = parent::$this->code;
                            $states[] = clone $this;
                        }
        if (isset($states)){
            return $states;
        } else {
            return false; 
        }
    }
    
    /**
     * class constructor
     * @param int $country_id , default null
     */
    public function __construct($country_id = null){
        if ($country_id != null){
            parent::load($country_id);
        }
    }
} 
?>