<?php
/**
 * terminal objective class can add, update, delete and get data from curriculum db
 * 
 * @example
 * // Add new objective <br>
 * $new_objective = new Objective(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename objective.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 21:00
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
class TerminalObjective {
    /**
     * ID of terminal objective
     * @var int
     */
    public $id = null;
    /**
     * terminal objective
     * @var string 
     */
    public $terminal_objective = null;
    /**
     * Description of terinal objective
     * @var string
     */
    public $description = null; 
    /**
     * id of curriculum
     * @var int 
     */
    public $curriculum_id = null; 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id = null; 
    /**
     * repeat interval
     * @var int 
     */
    public $repeat_interval = null;
    /**
     * Position of enabling_objective within curriculum
     * @var type 
     */
    public $order_id = null; 
    /**
     * load enabling objectives of current terminal objective
     * @var array of objects
     */
    public $enabling_objectives = null; 

    /**
     * add objective
     * @return mixed 
     */
    public function add(){
        
        $query = sprintf("SELECT MAX(order_id) FROM terminalObjectives WHERE curriculum_id = '%s'",
                mysql_real_escape_string($this->curriculum_id));
        $result = mysql_query($query);
        list($max) = mysql_fetch_row($result);
        $this->order_id = $max+1;

            //object_to_array($this);
        $query = sprintf("INSERT INTO terminalObjectives 
                    (terminal_objective,description,curriculum_id,order_id) 
                    VALUES ('%s','%s','%s','%s')",
                    mysql_real_escape_string($this->terminal_objective),
                    mysql_real_escape_string($this->description),
                    mysql_real_escape_string($this->curriculum_id),
                    mysql_real_escape_string($this->order_id));
        return mysql_query($query);
    }
    
    public function order($direction = null){
        switch ($direction) {
            case 'down': if ($this->order_id == 1){
                            // order_id kann nicht kleiner sein
                            } else {
                                $query = sprintf("SELECT id FROM terminalObjectives 
                                                    WHERE curriculum_id = '%s' AND order_id = '%s'",
                                        mysql_real_escape_string($this->curriculum_id), 
                                        mysql_real_escape_string($this->order_id-1));
                                $result = mysql_query($query);
                                $replace_id = mysql_result($result, 0, "id"); 
                                $query = sprintf("UPDATE terminalObjectives SET order_id = '%s' WHERE id = '%s'", 
                                        mysql_real_escape_string($this->order_id),
                                        mysql_real_escape_string($replace_id));
                                mysql_query($query);
                                $query = sprintf("UPDATE terminalObjectives SET order_id = '%s' WHERE id = '%s'", 
                                        mysql_real_escape_string($this->order_id-1),
                                        mysql_real_escape_string($this->id));
                                mysql_query($query);
                            }

                break;
            case 'up':      $query = sprintf("SELECT MAX(order_id) FROM terminalObjectives WHERE curriculum_id = '%s'",
                                    mysql_real_escape_string($this->curriculum_id));
                            $result = mysql_query($query);
                            list($max) = mysql_fetch_row($result);
                            if ($this->order_id == $max){
                            // order_id darf nicht größer als maximale order_id sein
                            } else {
                                $query = sprintf("SELECT id FROM terminalObjectives 
                                                    WHERE curriculum_id = '%s' AND order_id = '%s'",
                                        mysql_real_escape_string($this->curriculum_id), 
                                        mysql_real_escape_string($this->order_id+1));
                                $result = mysql_query($query);
                                $replace_id = mysql_result($result, 0, "id"); 
                                
                                $query = sprintf("UPDATE terminalObjectives SET order_id = '%s' WHERE id = '%s'", 
                                        mysql_real_escape_string($this->order_id),
                                        mysql_real_escape_string($replace_id));
                                mysql_query($query);
                                $query = sprintf("UPDATE terminalObjectives SET order_id = '%s' WHERE id = '%s'", 
                                        mysql_real_escape_string($this->order_id+1),
                                        mysql_real_escape_string($this->id));
                                mysql_query($query);
                            }

                break;

            default:
                break;
        }
        
        
        
        
    }
    
    /**
     * Update objective
     * @return boolean 
     */
    public function update(){
        $query = sprintf("UPDATE terminalObjectives 
                    SET terminal_objective = '%s', description = '%s' 
                    WHERE id = '%s'",
                    mysql_real_escape_string($this->terminal_objective),
                    mysql_real_escape_string($this->description),
                    mysql_real_escape_string($this->id));
        return mysql_query($query);
    }
    
    /**
     * Delete terminal objective
     * @return boolean 
     */
    public function delete(){
        $query = sprintf("DELETE
                        FROM terminalObjectives 
                        WHERE id = '%s'",
                  mysql_real_escape_string($_GET['terminalObjectiveID']));
        return mysql_query($query);
    } 
    
    /**
     * Load objective
     */
    public function load(){
        $query = sprintf("SELECT * 
                            FROM terminalObjectives
                            WHERE id = '%s'",
                            mysql_real_escape_string($this->id));
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                $this->id                   = $row["id"];
                $this->terminal_objective   = $row["terminal_objective"];
                $this->description          = $row["description"];
                $this->curriculum_id        = $row["curriculum_id"];
                $this->order_id             = $row["order_id"];
                $this->repeat_interval      = $row["repeat_interval"];
                $this->creation_time        = $row["creation_time"];
                $this->creator_id           = $row["creator_id"];
            } 
        }    
    }
    /**
     * get objectives depending on dependency
     * @param string $dependency
     * @param int $id
     * @param boolean $load_enabling_objectives
     * @return array of TerminalObjective objects|boolean 
     */
    public function getObjectives($dependency = null, $id = null, $load_enabling_objectives = false) {
        switch ($dependency) {
            /*case 'curriculum':  $query = sprintf("SELECT * 
                                                    FROM terminalObjectives
                                                    WHERE curriculum_id = '%s' ORDER by id ASC, 'order' ASC",
                                                    mysql_real_escape_string($id));*/
            case 'curriculum':  $query = sprintf("SELECT * 
                                                    FROM terminalObjectives
                                                    WHERE curriculum_id = '%s' ORDER by curriculum_id ASC, order_id ASC, id ASC",
                                                    mysql_real_escape_string($id));
                break;

            default:
                break;
        }
        $result = mysql_query($query);
        if ($result && mysql_num_rows($result)) {
            while($row = mysql_fetch_assoc($result)) { 
                $this->id                   = $row["id"];
                $this->terminal_objective   = $row["terminal_objective"];
                $this->description          = $row["description"];
                $this->curriculum_id        = $row["curriculum_id"];
                $this->order_id             = $row["order_id"];
                $this->repeat_interval      = $row["repeat_interval"];
                $this->creation_time        = $row["creation_time"];
                $this->creator_id           = $row["creator_id"];   
                if ($load_enabling_objectives){
                    $enabling_objectives = new EnablingObjective();
                    $this->enabling_objectives = $enabling_objectives->getObjectives('terminal_objective', $this->id);
                }
                $objectives[]               = clone $this; 
                
            } 
        }  
        
        if (isset($objectives)){
            return $objectives;
        } else { return false;}
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $query = sprintf("UPDATE terminalObjectives SET creator_id = '%s'",
                                            mysql_real_escape_string($this->creator_id));
        return mysql_query($query);
    }
}
?>