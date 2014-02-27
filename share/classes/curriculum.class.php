<?php
/**
 * Group object can add, update, delete and get data from curriculum db
 * 
 * @example
 * // Add new curriculum <br>
 * $new_curriculum = new Curriculum(); <br>
 * 
 * @abstract This file is part of curriculum - http://www.joachimdieterich.de
 * @package core
 * @filename curriculum.class.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.06.08 15:53
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
class Curriculum {
    /**
     * ID of curriculum
     * @var int
     */
    public $id = null;
    /**
     * combined id | curriculumID_groupID
     * @var string 
     */
    public $id_grid = null;
    /**
     * Name of curriculum
     * @var string
     */
    public $curriculum = null; 
    /**
     * Description of curriculum
     * @var string
     */
    public $description = null; 
    /**
     * id of grade
     * @var int 
     */
    public $grade_id = 8; //??? std vars setzten --> am besten über constructor
    /**
     * name of grade
     * @var string
     */
    public $grade = null;
    /**
     * id of subject
     * @var int
     */
    public $subject_id = 1; 
    /**
     * name of subject
     * @var type 
     */
    public $subject = null; 
    /**
     * id of schooltype
     * @var int
     */ 
    public $schooltype_id = 1; 
    /**
     * id of state
     * @var int
     */
    public $state_id = 11; 
    /**
     * id of country
     * @var int
     */
    public $country_id = 56; 
    /**
     * id of icon
     * @var int
     */
    public $icon_id = null; 
 
    /**
     * Timestamp when Grade was created
     * @var timestamp
     */
    public $creation_time = null; 
    /**
     * ID of User who created this Grade
     * @var int
     */
    public $creator_id =null; 
    
    public $language_code = null; 
    /**
     * array which holds terminal objectives of this curriculum
     * @var type 
     */
    public $terminal_objectives = null; 
    /**
     * add curriculum to db
     * @return mixed 
     */
    public function add(){
        $db = DB::prepare('SELECT COUNT(id) FROM curriculum WHERE UPPER(curriculum) = UPPER(?)');
        $db->execute(array($this->curriculum));
        if($db->fetchColumn() >= 1) { 
            return 'Diesen Lehrplan gibt es bereits.';
        } else {
            $db = DB::prepare('INSERT INTO curriculum (curriculum, description, grade_id, subject_id, schooltype_id, state_id, icon_id, country_id, creator_id) 
                                                  VALUES (?,?,?,?,?,?,?,?,?)');
            return $db->execute(array($this->curriculum, $this->description, $this->grade_id, $this->subject_id, $this->schooltype_id, $this->state_id, $this->icon_id, $this->country_id, $this->creator_id));
        }
    }
    
    /**
     * Update curriculum in db
     * @return boolean 
     */
    public function update(){
        $db = DB::prepare('UPDATE curriculum 
                SET curriculum = ?, description = ?, grade_id = ?, subject_id = ?, 
                schooltype_id = ?, state_id = ?, icon_id = ?, country_id = ?, creator_id = ?
                WHERE id = ?');
        return $db->execute(array($this->curriculum, $this->description, $this->grade_id, $this->subject_id, $this->schooltype_id, $this->state_id, $this->icon_id, $this->country_id, $this->creator_id ,$this->id));
    }
    
    /**
     * Delete curriculum from db
     * @return mixed 
     */
    public function delete(){
         $db = DB::prepare('DELETE FROM curriculum WHERE id=?');
        return $db->execute(array($this->id));
    } 
    
    /**
     * load curriculum depending on id 
     * if load_terminal_objectives == true -> get Objectives
     * @param type $load_terminal_objectives 
     */
    public function load($load_terminal_objectives = false){
        $db = DB::prepare('SELECT cu.*, co.code, su.subject 
                            FROM curriculum AS cu, countries AS co, subjects AS su 
                            WHERE cu.country_id = co.id 
                            AND cu.subject_id = su.id
                            AND cu.id=?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        $this->curriculum       = $result->curriculum;
        $this->description      = $result->description;
        $this->grade_id         = $result->grade_id;
        $this->subject_id       = $result->subject_id;
        $this->subject          = $result->subject;
        $this->schooltype_id    = $result->schooltype_id;
        $this->state_id         = $result->state_id;
        $this->icon_id          = $result->icon_id;
        $this->country_id       = $result->country_id;
        $this->language_code    = $result->code;
        $this->creation_time    = $result->creation_time;
        $this->creator_id       = $result->creator_id;
        if ($load_terminal_objectives){
            $terminal_objectives = new TerminalObjective();
            $this->terminal_objectives = $terminal_objectives->getObjectives('curriculum', $this->id, true);
        }
        
    }
    /**
     * get curriulum depending on dependency
     * @param string $dependency
     * @param int $id
     * @return array of curriculum objects  
     */
    public function getCurricula($dependency = null, $id = null){
        switch ($dependency) {
            case 'group':   $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, fl.filename, su.subject, 
                                            gr.grade, sc.schooltype, st.state, co.de
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, 
                                            files AS fl, subjects AS su, grade AS gr, schooltype AS sc,
                                            state AS st, countries AS co
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id AND cu.grade_id = gr.id AND cu.subject_id = su.id
                                            AND cu.schooltype_id = sc.id AND cu.state_id = st.id AND cu.country_id = co.id
                                            AND ce.group_id = ? AND ce.status = 1
                                            ORDER BY cu.curriculum ASC');
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) { 
                                    $curriculum[] = $result; 
                            }         
                break;
            case 'creator': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gr.grade  
                                                FROM curriculum AS cu, grade AS gr
                                                WHERE cu.creator_id = ? AND gr.id = cu.grade_id');
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) {
                                        $curriculum[] = $result; 
                            }
                break; 
            /*case 'teacher': $db = DB::prepare('SELECT cu.id, cu.curriculum, cu.description, gp.groups, gp.id AS gpid, fl.filename
                                            FROM curriculum AS cu, curriculum_enrolments AS ce, groups AS gp, files AS fl
                                            WHERE cu.id = ce.curriculum_id
                                            AND cu.icon_id = fl.id
                                            AND gp.id = ce.group_id
                                            AND ce.group_id = ANY (SELECT id FROM groups 
                                                                WHERE institution_id = ANY (SELECT institution_id FROM institution_enrolments 
                                                                                                WHERE user_id = ?))
                                            ORDER BY gp.groups, cu.curriculum ASC');
                            $db->execute(array($id));
                            $counter = 0;
                            while($result = $db->fetchObject()) { 
                                    $curriculum[$counter]["id"]         = $result->id;
                                    $curriculum[$counter]["id_clsid"]   = $result->id.'_'.$result->gpid;
                                    $curriculum[$counter]["curriculum"] = $result->curriculum;
                                    $curriculum[$counter]["description"]= $result->description;
                                    $curriculum[$counter]["groups"]     = $result->groups;
                                    $curriculum[$counter]["filename"]   = $result->filename;
                                    $curriculum[$counter]["coursename"] = $result->groups.' | '.$result->curriculum.' | '.$result->description; 
                            $counter++;
                            }
                break; */ //not used
            case 'user':    $db = DB::prepare('SELECT cu.*, co.de, st.state, sc.schooltype, gr.grade, su.subject, fl.filename  
                                            FROM curriculum AS cu, countries AS co, state AS st, schooltype AS sc, grade AS gr, subjects AS su, files AS fl
                                            WHERE  cu.country_id = co.id AND cu.state_id = st.id 
                                            AND cu.schooltype_id = sc.id AND cu.grade_id = gr.id 
                                            AND cu.subject_id = su.id AND cu.creator_id = ?
                                            AND cu.icon_id = fl.id');
                            $db->execute(array($id));
                            while($result = $db->fetchObject()) {
                                $curriculum[] = $result; //result Data wird an setPaginator vergeben
                            } 
                break; 
            default:    
                break;
        }
        
        if (isset($curriculum)){
            return $curriculum;    
        } 
   }
   
   /**
    * Check if there are active enrolments - used by deleteCurriculum in request.php
    * @return boolean 
    */
   public function getCurriculumEnrolments(){
       $db = DB::prepare('SELECT id FROM curriculum_enrolments WHERE curriculum_id = ? AND status = 1');
       $db->execute(array($this->id));
       if($db->fetchColumn() >= 1) { return true;} else {return false;}
   }
   
   /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE curriculum SET creator_id = ?');
        if ($db->execute(array($this->creator_id))){
            DB::prepare('UPDATE curriculum_enrolments SET creator_id = ?');
            return $db->execute(array($this->creator_id));
        }
    }
}
?>