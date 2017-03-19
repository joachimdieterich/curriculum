<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename semester.class.php
* @copyright 2013 joachimdieterich
* @author joachimdieterich
* @date 2013.05.14 11:05
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

class Semester {
    /**
     * ID of semester
     * @var int
     */
    public $id; 
    /**
     * Name of semester
     * @var string
     */
    public $semester; 
    /**
     * Description of description
     * @var string
     */
    public $description; 
    /**
     * ID of current institution
     * @var int
     */
    public $institution_id; 
    public $institution; 
    /**
     * Timestamp of semester begin
     * @var timestamp
     */
    public $begin; 
    /**
     * Timestamp of semester end
     * @var timestamp
     */
    public $end; 
    public $timerange;
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
    /**
     * Username of creator
     * @var string 
     */
    public $creator_username; 
    
    
    public function __construct($id = '') {
        if ($id != ''){
            $this->id = $id;
            $this->load();
        }
    }
    
    /**
     * Get Semesterlist of current institution
     * @return \Semester 
     */
    public function getSemesters($dependency = 'all', $id = null, $paginator = ''){
        global $USER;
        $order_param = orderPaginator($paginator, array('semester'      => 'se',
                                                        'description'   => 'se', 
                                                        'begin'         => 'se', 
                                                        'end'           => 'se', 
                                                        'creation_time' => 'se', 
                                                        'institution'   => 'ins')); 
        $semesters = array();
        switch ($dependency) {
            case 'all': $db = DB::prepare('SELECT se.*, us.username, ins.institution
                           FROM semester AS se, users AS us, institution AS ins
                           WHERE se.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?) 
                           AND se.creator_id = us.id AND se.institution_id = ins.id '.$order_param);
                        $db->execute(array($USER->id));
                break;
            case 'institution': $db = DB::prepare('SELECT se.*, us.username, ins.institution
                                    FROM semester AS se, users AS us, institution AS ins
                                    WHERE se.institution_id = ?
                                    AND se.creator_id = us.id AND se.institution_id = ins.id '.$order_param);
                                $db->execute(array($id));
                break;
            default:
                break;
        }
        
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->semester            = $result->semester;
                $this->description         = $result->description;
                $this->institution         = $result->institution;
                $this->begin               = $result->begin;
                $this->end                 = $result->end;
                $this->timerange           = date('d.m.Y G:i', strtotime($this->begin)) .' - '. date('d.m.Y G:i', strtotime($result->end));
                $this->creation_time       = $result->creation_time;
                $this->creator_id          = $result->creator_id;
                $this->creator_username    = $result->username;
                $semesters[] = clone $this;
        } 

        return $semesters;     
    }
    
   
    public function getMySemesters($user_id){
        $semesters = array();
        $db = DB::prepare('SELECT DISTINCT se.*, us.username, ins.institution
                           FROM semester AS se, users AS us, groups AS gr, groups_enrolments AS ge, institution_enrolments AS ie, institution AS ins
                           WHERE gr.semester_id = se.id AND gr.id = ge.group_id AND us.id = ge.user_id AND ie.user_id = ge.user_id 
                           AND gr.institution_id = ie.institution_id AND ie.status = 1 AND ge.status = 1
                           AND ins.id = ie.institution_id AND ge.user_id = ?');
        $db->execute(array($user_id));
        while($result = $db->fetchObject()) { 
                $this->id                  = $result->id;
                $this->semester            = $result->semester;
                $this->institution         = $result->institution;
                $this->description         = $result->description;
                $this->begin               = $result->begin;
                $this->end                 = $result->end;
                $this->timerange           = date('d.m.Y G:i', strtotime($this->begin)) .' - '. date('d.m.Y G:i', strtotime($result->end));
                $this->creation_time       = $result->creation_time;
                $this->creator_id          = $result->creator_id;
                $this->creator_username    = $result->username;
                $semesters[] = clone $this;
        } 
        return $semesters;
    }
    
    /**
     * Add a new semester to db
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('semester:add', $USER->role_id);
        list ($this->begin, $this->end) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->begin = date('Y-m-d G:i:s', strtotime($this->begin));
        $this->end   = date('Y-m-d G:i:s', strtotime($this->end));
        $db = DB::prepare('SELECT COUNT(id) FROM semester WHERE UPPER(semester) = UPPER(?) AND institution_id = ?');
        $db->execute(array($this->semester, $this->institution_id));
        if($db->fetchColumn() >= 1) { 
            return 'Diesen Lernzeitraum gibt es bereits.';
        } else {
            $db = DB::prepare('INSERT INTO semester (semester,description,begin,end,creation_time,creator_id,institution_id)
                                            VALUES (?,?,?,?,NOW(),?,?)');
            return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $USER->id, $this->institution_id));	
        }   
    }
    
    /**
     * Update semester 
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('semester:update', $USER->role_id);
        list ($this->begin, $this->end) = explode(' - ',$this->timerange); // copy timestart and timeend from timerage
        $this->begin = date('Y-m-d G:i:s', strtotime($this->begin));
        $this->end   = date('Y-m-d G:i:s', strtotime($this->end));
        $db = DB::prepare('UPDATE semester SET semester = ?, description = ?, begin = ?, end = ?, institution_id = ? WHERE id = ?');
        return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $this->institution_id, $this->id));
    }
    
    /**
     * Delete current semester
     * @return boolean 
     */
    public function delete(){
        global $USER, $LOG;
        checkCapabilities('semester:delete', $USER->role_id);
        $this->load();
        $LOG->add($USER->id, 'semester.class.php', dirname(__FILE__), 'Delete semester: '.$this->semester.', institution_id: '.$this->institution_id);
        $db     = DB::prepare('SELECT id FROM groups WHERE semester_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false; 
        } else {
            $db = DB::prepare('DELETE FROM semester WHERE id = ?');
            return $db->execute(array($this->id));
        }
    }
    
    /**
     * Load semester 
     */
    public function load(){
        $db = DB::prepare('SELECT * FROM semester WHERE id = ?');
        $db->execute(array($this->id));
        $result                  = $db->fetchObject();
        $this->id                = $result->id;
        $this->semester          = $result->semester;
        $this->description       = $result->description;
        $this->begin             = $result->begin;
        $this->end               = $result->end;
        $this->timerange         = date('d.m.Y G:i', strtotime($this->begin)) .' - '. date('d.m.Y G:i', strtotime($result->end));
        $this->institution_id    = $result->institution_id;
    }
    
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE semester SET institution_id = ?, creator_id = ?');
        return $db->execute(array($this->institution_id, $this->creator_id));
    }   
}