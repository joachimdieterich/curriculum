<?php
/**
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename EnablingObjective.class.php
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
class ObjectiveSubscription {
    /**
     * ID of enabling objective
     * @var int
     */
    public $id;
    /**
     * ID of the type of the referencing context
     * @var int
     */
    public $context_id;
    /**
     * ID of the referencing source
     * @var int
     */
    public $source_id;
    /**
     * ID of the type from the object that should be referenced
     * @var int
     */
    public $objective_context_id;
    /**
     * ID of the object that should be referenced
     * @var int
     */
    public $reference_id;
    /**
     * ID of the creator of the subscription
     * @var int
     */
    public $creator_id;
    /**
     * Timestamp when the subscription was created
     * @var int
     */
    public $timecreated;
    /**
     * Timestamp whe the subscription was last modified
     * @var int
     */
    public $timemodified;
    
    
                    
    /**
     * add objective
     * @return mixed 
     */
    public function add(){
        global $USER;
        #TODO: checkCapabilities('objectives:addEnablingObjective', $USER->role_id);
        $db = DB::prepare('INSERT INTO objective_subscription 
                    (context_id, source_id, objective_context_id, reference_id, creator_id) 
                    VALUES (?,?,?,?,?)');        
        $db->execute(array($this->context_id, $this->source_id, $this->objective_context_id, $this->reference_id, $USER->id));
        return DB::lastInsertId(); //returns id 
    }
    
    /**
     * Update objective
     * @return boolean 
     */
    public function update(){
        global $USER;
        #TODO: checkCapabilities('objectives:updateEnablingObjectives', $USER->role_id);
        $db = DB::prepare('UPDATE objective_subscription SET context_id = ?, '
                . 'source_id = ?, objective_context_id = ?, reference_id = ?, creator_id = ?'
                . 'WHERE id = ?');
        $db->execute(array($this->context_id, $this->source_id, $this->objective_context_id, $this->reference_id,$this->creator_id));
    }
    
    /**
     * delete enabling objective
     * @return boolean 
     */
    public function delete(){
        global $USER, $LOG;
        #checkCapabilities('objectives:deleteEnablingObjectives', $USER->role_id);
        // load objective to recalc order_id when deleting objective
        $this->load();
        $LOG->add($USER->id, 'objectivesubscription', dirname(__FILE__), 'Delete objectiveSubscription: '.$this);
        $db = DB::prepare('DELETE FROM objectivesubscription WHERE id = ?');
        if ($db->execute(array($this->id))) {
            return True;
        } else {
            return false;
        }
        
    } 
    
    /**
     * Load enabling objectives from db 
     */
    public function load(){
        global $CFG;
        $db = DB::prepare('SELECT * FROM objective_subscription WHERE id = ?');
        $db->execute(array($this->id));
        if($result = $db->fetchObject()) { 
            $this->context_id = $result->context_id;
            $this->creator_id = $result->creator_id;
            $this->objective_context_id = $result->objective_context_id;
            $this->reference_id = $result->reference_id;
            $this->source_id = $result->source_id;
            $this->timecreated = $result->timecreated;
            $this->timemodified = $result->timemodified;
            return true;
        }else{
            return false;
        } 
    }
    
    public static function getSubscriptionIds($context_id, $source_id, $objective_context_id){
        $db = DB::prepare('SELECT id '
                . 'FROM objective_subscription AS os '
                . 'WHERE os.context_id = ? '
                . 'AND os.source_id = ? '
                . 'AND os.objective_context_id = ?');
        $db->execute(array($context_id, $source_id, $objective_context_id));
        $result = array();
        while($result = $db->fetchObject()){
            $result[] = $result->id;
        }
        return $result;
    }
    
    public static function deleteAllObjectiveSubscriptionsByContextSource($context_id, $source_id, $creator_id){
        $db = DB::prepare('DELETE FROM objective_subscription '
                . 'WHERE context_id = ? '
                . 'AND source_id = ? '
                . 'AND creator_id = ?');
        return $db->execute(array($context_id, $source_id, $creator_id));
    }
    
    public static function deleteByContextSourceObjectivecontextReferenceCreator($context_id, $source_id, $objectiveContext_id, $reference_id, $creator_id){
        $db = DB::prepare('DELETE FROM objective_subscription '
                . 'WHERE context_id = ? '
                . 'AND source_id = ? '
                . 'AND objective_context_id = ? '
                . 'AND reference_id = ? '
                . 'AND creator_id = ?');
        return $db->execute(array($context_id, $source_id, $objectiveContext_id, $reference_id, $creator_id));

    }
}