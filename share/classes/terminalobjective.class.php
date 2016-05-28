<?php
/**
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename terminalbjective.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.11 21:00
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
class TerminalObjective {
    /**
     * ID of terminal objective
     * @var int
     */
    public $id;
    /**
     * terminal objective
     * @var string 
     */
    public $terminal_objective;
    /**
     * Description of terinal objective
     * @var string
     */
    public $description; 
    /**
     * id of curriculum
     * @var int 
     */
    public $curriculum_id; 
    /**
     * Hex color code #123456
     * @var string
     */
    public $color; 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id; 
    /**
     * repeat interval
     * @var int 
     */
    public $repeat_interval;
    /**
     * Position of enabling_objective within curriculum
     * @var type 
     */
    public $order_id; 
    /**
     * load enabling objectives of current terminal objective
     * @var array of objects
     */
    public $enabling_objectives; 
    public $files;
    /**
     * add objective
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('objectives:addTerminalObjective', $USER->role_id);
        $db = DB::prepare('SELECT MAX(order_id) as max FROM terminalObjectives WHERE curriculum_id = ?');
        $db->execute(array($this->curriculum_id));
        $result = $db->fetchObject();
        $this->order_id = $result->max+1;
        $db = DB::prepare('INSERT INTO terminalObjectives (terminal_objective,description,curriculum_id,color,order_id,creator_id) 
                    VALUES (?,?,?,?,?,?)');
        $db->execute(array($this->terminal_objective, $this->description, $this->curriculum_id, $this->color, $this->order_id, $this->creator_id));

        return DB::lastInsertId(); //returns id    
    }
    
    public function order($direction = null){
        switch ($direction) {
            case 'down': if ($this->order_id == 1){
                            // order_id kann nicht kleiner sein
                            } else {
                                $db = DB::prepare('SELECT id FROM terminalObjectives 
                                                    WHERE curriculum_id = ? AND order_id = ?');
                                $db->execute(array($this->curriculum_id, ($this->order_id-1)));
                                $result = $db->fetchObject();
                                $replace_id = $result->id; 
                                $db = DB::prepare('UPDATE terminalObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array($this->order_id, $replace_id));
                                $db = DB::prepare('UPDATE terminalObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array(($this->order_id-1), $this->id));
                            }
                break;
            case 'up':      $db = DB::prepare('SELECT MAX(order_id) as max FROM terminalObjectives WHERE curriculum_id = ?');
                            $db->execute(array($this->curriculum_id));
                            $result = $db->fetchObject();
                            if ($this->order_id == $result->max){
                            // order_id darf nicht größer als maximale order_id sein
                            } else {
                                $db = DB::prepare('SELECT id FROM terminalObjectives 
                                                    WHERE curriculum_id = ? AND order_id = ?');
                                $db->execute(array($this->curriculum_id, ($this->order_id+1)));
                                $result = $db->fetchObject();
                                $replace_id = $result->id;
                                $db = DB::prepare('UPDATE terminalObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array($this->order_id, $replace_id));
                                $db = DB::prepare('UPDATE terminalObjectives SET order_id = ? WHERE id = ?');
                                $db->execute(array(($this->order_id+1), $this->id));
                            }
                break;

            default:  break;
        }   
    }
    
    /**
     * Update objective
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('objectives:updateTerminalObjectives', $USER->role_id);
        $db = DB::prepare('UPDATE terminalObjectives SET terminal_objective = ?, description = ?, color = ? WHERE id = ?');
        return $db->execute(array($this->terminal_objective, $this->description, $this->color, $this->id));
    }
    
    /**
     * Delete terminal objective
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('objectives:deleteTerminalObjectives', $USER->role_id);
        $db = DB::prepare('DELETE FROM terminalObjectives WHERE id = ?');
        return $db->execute(array($this->id));
    } 
    
    /**
     * Load objective
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM terminalObjectives WHERE id = ?');
        if ($db->execute(array($this->id))) {
            while($result = $db->fetchObject()) { 
                $this->id                   = $result->id;
                $this->terminal_objective   = $result->terminal_objective;
                $this->description          = $result->description;
                $this->curriculum_id        = $result->curriculum_id;
                $this->color                = $result->color;
                $this->order_id             = $result->order_id;
                $this->repeat_interval      = $result->repeat_interval;
                $this->creation_time        = $result->creation_time;
                $this->creator_id           = $result->creator_id;
            } 
        }    
    }
    /**
     * get objectives on dependency
     * @param string $dependency
     * @param int $id
     * @param boolean $load_enabling_objectives
     * @return array of TerminalObjective objects|boolean 
     */
    public function getObjectives($dependency = null, $id = null, $load_enabling_objectives = false) {
        switch ($dependency) {
            case 'curriculum':  $files = new File(); 
                                $db = DB::prepare('SELECT * FROM terminalObjectives
                                                    WHERE curriculum_id = ? ORDER by curriculum_id ASC, order_id ASC, id ASC');
                                $db->execute(array($id));  
                                while($result = $db->fetchObject()) { 
                                    $this->id                   = $result->id;
                                    $this->terminal_objective   = $result->terminal_objective;
                                    $this->description          = $result->description;
                                    $this->curriculum_id        = $result->curriculum_id;
                                    $this->color                = $result->color;
                                    $this->order_id             = $result->order_id;
                                    $this->repeat_interval      = $result->repeat_interval;
                                    $this->creation_time        = $result->creation_time;
                                    $this->creator_id           = $result->creator_id;
                                    $this->files                = $files->getFiles('terminal_objective', $this->id, 'default', true); // nicht benötigt --> viel bessere performance
                                    if ($load_enabling_objectives){
                                        $enabling_objectives = new EnablingObjective();
                                        $this->enabling_objectives = $enabling_objectives->getObjectives('terminal_objective', $this->id);
                                    }
                                    $objectives[]               = clone $this; 
                                }
                                break;
            default:            break;
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
        $db = DB::prepare('UPDATE terminalObjectives SET creator_id = ?');
        return $db->execute(array($this->creator_id));
    }
}