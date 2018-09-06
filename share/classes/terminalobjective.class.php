<?php
/**
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename terminalbjective.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.06.11 21:00
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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
    public $type;
    /**
     * type of terminalobjective
     * @return string;
     */
    
    public function add(){
        global $USER;
        checkCapabilities('objectives:addTerminalObjective', $USER->role_id);
        $db = DB::prepare('SELECT MAX(order_id) as max FROM terminalObjectives WHERE curriculum_id = ?');
        $db->execute(array($this->curriculum_id));
        $result = $db->fetchObject();
        $this->order_id = $result->max+1;
        $db = DB::prepare('INSERT INTO terminalObjectives (terminal_objective,description,curriculum_id,color,order_id,creator_id, type_id) 
                    VALUES (?,?,?,?,?,?,?)');
        $db->execute(array($this->terminal_objective, $this->description, $this->curriculum_id, $this->color, $this->order_id, $USER->id, $this->type_id));

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
        $db = DB::prepare('UPDATE terminalObjectives SET terminal_objective = ?, description = ?, color = ?, type_id = ? WHERE id = ?');
        return $db->execute(array($this->terminal_objective, $this->description, $this->color, $this->type_id, $this->id));
    }
    
    /**
     * Delete terminal objective
     * @return boolean 
     */
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('objectives:deleteTerminalObjectives', $USER->role_id);
        // load objective to recalc order_id
        $this->load();
        $LOG->add($USER->id, 'terminalobjective.class.php', dirname(__FILE__), 'Delete terminalobjective: '.$this->terminal_objective.', curriculum_id: '.$this->curriculum_id);
        $db = DB::prepare('UPDATE terminalObjectives SET order_id = order_id - 1 WHERE curriculum_id = ? AND order_id > ?');
        if ($db->execute(array($this->curriculum_id, $this->order_id))) {
            $db01 = DB::prepare('DELETE FROM terminalObjectives WHERE id = ?');
            if ($db01->execute(array($this->id))){
                $db02 = DB::prepare('DELETE FROM enablingObjectives WHERE terminal_objective_id = ?');
                return $db02->execute(array($this->id));
            }
        } else {
            return false;
        }
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
                $this->type_id              = $result->type_id;
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
    public function getObjectives($dependency = null, $id = null, $load_enabling_objectives = false, $reference_ter_ids = null,  $reference_ena_ids = null) {
        global $CFG;
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
                                    $this->type_id              = $result->type_id;
                                    if ($load_enabling_objectives){
                                        $enabling_objectives = new EnablingObjective();
                                        $this->enabling_objectives = $enabling_objectives->getObjectives('terminal_objective', $this->id);
                                    }
                                    /* Check if Material or external Reference is set */
                                    $db_02 = DB::prepare('SELECT COUNT(*) AS MAX FROM files WHERE ter_id = ? AND ISNULL(ena_id) AND context_id = 2');
                                    $db_02->execute(array($result->id));
                                    $res_02 = $db_02->fetchObject();
                                    $this->files['local']       = $res_02->MAX;
                                    if (isset($CFG->repository)){ // prüfen, ob Repository Plugin vorhanden ist.
                                        $this->files['repository'] = $CFG->repository->count(0,$result->id);
                                    } 
                                    if (isset($CFG->settings->webservice)){ // prüfen, ob webservice Plugin vorhanden ist.
                                        $ws     = get_plugin('webservice', $CFG->settings->webservice);
                                        $this->files['webservice']  = '';//$ws->count($_SESSION['CONTEXT']['terminal_objective']->id,$result->id);
                                    }
                                    /* Check if references are available for this terminal objective*/
                                    if (is_array($reference_ter_ids)){ //check if view mode == reference_view
                                        $db_04c       = DB::prepare('SELECT COUNT(*) AS MAX FROM reference WHERE context_id = ? AND reference_id IN ('.implode(",", $reference_ter_ids).') AND unique_id IN (SELECT unique_id FROM reference WHERE context_id = ? AND reference_id = ?)');
                                        $db_04c->execute(array( $_SESSION['CONTEXT']['terminal_objective']->context_id, $_SESSION['CONTEXT']['terminal_objective']->context_id, $result->id));
                                        $res_04c = $db_04c->fetchObject();
                                        $this->files['references']  = $res_04c->MAX;
                                        if ($res_04c->MAX == 0){ // only check ena ids if nothing found yet
                                            $db_04d       = DB::prepare('SELECT COUNT(*) AS MAX FROM reference WHERE context_id = ? AND reference_id IN ('.implode(",", $reference_ena_ids).') AND unique_id IN (SELECT unique_id FROM reference WHERE context_id = ? AND reference_id = ?)');
                                            $db_04d->execute(array( $_SESSION['CONTEXT']['enabling_objective']->context_id, $_SESSION['CONTEXT']['terminal_objective']->context_id, $result->id));
                                            $res_04d = $db_04d->fetchObject();
                                            $this->files['references']  += $res_04d->MAX;
                                        }
                                    } else {
                                        $db_04       = DB::prepare('SELECT COUNT(*) AS MAX FROM reference WHERE context_id = ? AND reference_id = ?');
                                        $db_04->execute(array( $_SESSION['CONTEXT']['terminal_objective']->context_id, $result->id));
                                        $res_04 = $db_04->fetchObject();
                                        $this->files['references']  = $res_04->MAX;
                                    }
                                    
                                    $objectives[]               = clone $this; 
                                }
                                break;
            case 'certificate': 
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
                                    $this->type_id              = $result->type_id;
                                    $objectives[]               = clone $this; 
                                }
                                break;
            
            default:            break;
        }
         
        if (isset($objectives)){
            return $objectives;
        } else { return false;}
    }
    
    public function getType(){
        $types = array();

        $db    = DB::prepare('SELECT * FROM objective_type AS ot ORDER BY ot.id;');
        $db -> execute();

        while ($result = $db->fetchObject()){
            $this->id       = $result->id;
            $this->type     = $result->type;
            $types[]        = clone $this;
        }

        if (isset($types)){
            return $types;
        } else {
            return $result;
        }
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