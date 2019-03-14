<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename subject.class.php
* @copyright 2013 joachimdieterich
* @author joachimdieterich
* @date 2013.05.11 20:50
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
class Subject {
    /**
     * ID of Subject
     * @var int 
     */
    public $id; 
    /**
     * Subject title
     * @var string 
     */
    public $subject;
    /**
     * Subject shortcut
     * @var string
     */
    public $subject_short; 
    /**
     * Subject description
     * @var string
     */
    public $description; 
    /**
     * ID of institution to which subject belongs to
     * @var type 
     */
    public $institution_id; 
    /**
     * Timestamp when Subject was created
     * @var timestamp 
     */
    public $creation_timestamp; 
    /**
     * ID of User who created this subject
     * @var int
     */
    public $creator_id; 
    
    /**
     * Get all available subjects of current Institution
     * @return array of objects 
     */
    public function getSubjects($paginator =''){
        global $USER;
        $order_param    = orderPaginator($paginator, array('id' => 'sub',
                                                           'subject' => 'sub',
                                                        'description'    => 'sub',
                                                        'subject_short'  => 'sub',
                                                        'institution'    => 'ins')); 
       
        $subjects       = array();
       
        if(checkCapabilities('curriculum:addglobalentries', $USER->role_id, false)){ // set for global ADMIN!
            $db         = DB::prepare('SELECT SQL_CALC_FOUND_ROWS sub.*, ins.institution, sco.schooltype
                                       FROM subjects AS sub
                                       LEFT JOIN schooltype AS sco ON (sub.schooltype_id = sco.id)
                                       LEFT JOIN institution AS ins ON (sub.institution_id = ins.id)
                                       WHERE (sub.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?)
                                       OR sub.institution_id = 0 ) '.$order_param);
            $db->execute(array($USER->id));
        } else {
            $db         = DB::prepare('SELECT SQL_CALC_FOUND_ROWS sub.*, ins.institution, sco.schooltype
                                       FROM subjects AS sub
                                       LEFT JOIN schooltype AS sco ON (sub.schooltype_id = sco.id)
                                       LEFT JOIN institution AS ins ON (sub.institution_id = ins.id)
                                       WHERE (sub.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?)
                                       OR (sub.institution_id = 0 AND (sub.schooltype_id = ?))) '.$order_param);
            $db->execute(array($USER->id, $USER->institution->schooltype_id));
        }
        
        while($result = $db->fetchObject()) { 
                $this->id                   = $result->id;
                $this->subject              = $result->subject;
                $this->subject_short        = $result->subject_short;
                $this->description          = $result->description;
                $this->creation_timestamp   = $result->creation_time;
                $this->creator_id           = $result->creator_id;
                $this->institution_id       = $result->institution_id;
                $this->schooltype           = $result->schooltype;
                $this->schooltype_id        = $result->schooltype_id;
                if ($this->schooltype == "") {
                    $this->institution      = $result->institution;
                } else {
                    $this->institution      = $result->institution.' ('.$this->schooltype.')';
                }
                $subjects[] = clone $this;
        } 
        
        if ($paginator != ''){ 
             set_item_total($paginator); //set item total based on FOUND ROWS()
        }
        
        if (isset($subjects)) {    
            return $subjects;
        } else {return $result;}
    }
    
    /**
     * Add Subject
     * @return boolean 
     */
    public function add(){
        global $USER;
        checkCapabilities('subject:add', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM subjects WHERE UPPER(subject) = UPPER(?) AND institution_id = ?');
        $db->execute(array($this->subject, $this->institution_id));
        if($db->fetchColumn() >= 1) { 
            return 'Diesen Fächernamen gibt es bereits.';
        } else {
            $db = DB::prepare('INSERT INTO subjects (subject,subject_short,description,creator_id,institution_id) VALUES (?,?,?,?,?)');
            return $db->execute(array($this->subject, $this->subject_short, $this->description, $USER->id, $this->institution_id));
        }
    }
    
    /**
     * Update current subject
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('subject:update', $USER->role_id);
        $db     = DB::prepare('SELECT institution_id FROM subjects WHERE id = ?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();
        if ($result->institution_id == 0){
            $_SESSION['PAGE']->message[] = array('message' => 'Globale Fächer können nicht bearbeitet werden.', 'icon' => 'fa fa-language text-warning');
            return false;
        } else {
            $db = DB::prepare('UPDATE subjects  SET subject = ?, subject_short = ?, description = ?, institution_id = ? WHERE id = ?');
            return $db->execute(array($this->subject, $this->subject_short, $this->description, $this->institution_id, $this->id));
        }
    }
    /**
     * Delete current subject
     * @return boolean 
     */
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('subject:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'subject.class.php', dirname(__FILE__), 'Delete subject: '.$this->subject.', institution_id: '.$this->institution_id);
        $db = DB::prepare('SELECT id FROM curriculum WHERE subject_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false;
        } else { //delete only, if no curriculum with subject exists
            $db = DB::prepare('DELETE FROM subjects WHERE id = ? AND id <> 0');
            if ($db->execute(array($this->id))){
                $_SESSION['PAGE']->message[] = array('message' => 'Fach wurde erfolgreich gelöscht.', 'icon' => 'fa fa-language text-success');
                return true;
            } else {
                $_SESSION['PAGE']->message[] = array('message' => 'Sie dürfen keine globalen Fächer löschen.', 'icon' => 'fa fa-language text-warning');
                return false;
            }
        }
    }

    /**
     * Load a subject 
     */
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT * FROM subjects WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result = $db->fetchObject();
        if ($result){
            $this->id             = $result->id;
            $this->subject        = $result->subject;
            $this->subject_short  = $result->subject_short;
            $this->description    = $result->description;
            $this->institution_id = $result->institution_id;
            return true;                                                        // wichtig! f. loadImportFormData
        } else { 
            return false; 
        }
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE subjects SET institution_id = ?, creator_id = ?');
        return $db->execute(array($this->institution_id, $this->creator_id));
    }
}
