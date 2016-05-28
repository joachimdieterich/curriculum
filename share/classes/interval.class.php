<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename interval.class.php
 * @copyright 2016 joachimdieterich
 * @author joachimdieterich
 * @date 2016.04.18 11:05
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */

class Interval {
    /**
     * ID of interval
     * @var int
     */
    public $id; 
    /**
     * interval in days
     * @var int
     */
    public $repeat_interval; 
    /**
     * Description of interval
     * @var string
     */
    public $description; 
    
    
    
    public function __construct($id = '') {
        if ($id != ''){
            $this->id = $id;
            $this->load();
        }
    }
    
    
    
    /**
     * Load semester 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM repeat_interval WHERE id = ?');
        $db->execute(array($this->id));
        $result                  = $db->fetchObject();
        $this->id                = $result->id;
        $this->repeat_interval   = $result->repeat_interval;
        $this->description       = $result->description;
    }
    
    /**
     * 
     * @global object $USER
     * @return \Interval
     */
    public function getIntervals(){
        global $USER;
       
        $intervals = array();
        $db = DB::prepare('SELECT * FROM repeat_interval');
        $db->execute();
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->repeat_interval     = $result->repeat_interval;
                $this->description         = $result->description;

                $intervals[] = clone $this;
        } 

        return $intervals;     
    }
      
}