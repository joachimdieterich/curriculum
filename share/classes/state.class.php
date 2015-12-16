<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename state.class.php 
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
class State extends Country { 
    /**
     * states id
     * @var id 
     */
    public $id; 
    /**
     * state name
     * @var string
     */
    public $state; 
    /**
     * country code
     * @var string
     */
    public $country_code; 
       
    /**
     * get states depending on country code
     * @param string $dependency
     * @param int $id
     * @return array of objects|boolean 
     */
    public function getStates($dependency = null, $id = null) {
        switch ($dependency) {
            case null:      $db = DB::prepare('SELECT * FROM state WHERE country_code = ? ORDER BY state ASC');
                            $db->execute(array($this->code)); 
                break;
            
            case 'profile': $db = DB::prepare('SELECT sta.* FROM state AS sta, countries AS cou 
                                            WHERE sta.country_code = cou.code AND cou.id = ? ORDER BY sta.state ASC');
                            $db->execute(array($id));
                break;
            default: break;
        }
       
        while($result = $db->fetchObject()) { 
            $this->id            = $result->id;
            $this->state         = $result->state;
            $this->country_code  = $result->country_code;
            $states[]            = clone $this; 
        } 
        if (isset($states)){
            return $states;
        } else {
            $this->id            = '1';
            $this->state         = '---';
            $this->country_code  = $this->code;
            $states[]            = clone $this;
            return  $states; 
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