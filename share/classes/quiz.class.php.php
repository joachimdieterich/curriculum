<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename quiz_question.class.php
 * @copyright 2015 joachimdieterich
 * @author joachimdieterich
 * @date 2013.12.04 19:33
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

class Quiz {
    /**
     * ID of quiz
     * @var int
     */
    public $id; 
    /**
     * Name of question
     * @var string
     */
    public $question;
    public $type;
    public $objective_type;
    public $objective_id;
    /** 
     * Timestamp of creation
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of creator
     * @var int
     */
    public $creator_id; 

    public function evaluate(){
        
    }
    
 
}