<?php
/**
* Grade object can add, update, delete and get data from grade db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename grade.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.10 10:58
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
class Grade {
    /**
     * ID of Grade
     * @var int
     */
    public $id;
    /**
     * Name of Grade
     * @var string
     */
    public $grade; 
    /**
     * Description of Grade
     * @var string
     */
    public $description; 
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
     * ID of institution to which Grade belongs to
     * @var int
     */
    public $institution_id; 
    public $institution; 
   
    
    /**
     * add grade
     * @return mixed 
     */
    public function add(){
        global $USER;
        checkCapabilities('grade:add', $USER->role_id);
        $db = DB::prepare('INSERT INTO grade (grade,description,creator_id,institution_id) VALUES (?,?,?,?)');
        return $db->execute(array($this->grade, $this->description, $USER->id, $this->institution_id));
    }
    
    /**
     * Update grade
     * @return boolean 
     */
    public function update(){
        global $USER;
        checkCapabilities('grade:update', $USER->role_id);
        $db = DB::prepare('UPDATE grade SET grade = ?, description = ? WHERE id = ?');
        return $db->execute(array($this->grade, $this->description, $this->id));
    }
    
    /**
     * delete grade
     * @global object $USER
     * @param int $creator_id
     * @return boolean 
     */
    public function delete(){
        global $USER;
        checkCapabilities('grade:delete', $USER->role_id);
        $db = DB::prepare('SELECT id FROM curriculum WHERE grade_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){ //endroled !
            return false;
        } else {
            $db = DB::prepare('DELETE FROM grade WHERE id = ?');
            return $db->execute(array($this->id));
        } 
    } 
    
    /**
     * Load Grade with id $this->id 
     */
    public function load($dependency = 'id', $value = null){
        if (isset($value)){ $v = $value; } else { $v = $this->id; }
        $db = DB::prepare('SELECT * FROM grade WHERE '.$dependency.' = ?');
        $db->execute(array($v));
        $result = $db->fetchObject();
        if ($result){
            foreach ($result as $key => $value) {
                $this->$key          = $value; 
            }
            return true;                                                        // wichtig! f. loadImportFormData
        } else { 
            return false; 
        }
    }
    
    /**
     * Get all availible Grades of current institution
     * @return array of Grade objects 
     */
    public function getGrades($dependency = 'all', $id = null, $paginator = '' ){
        global $USER;
        $order_param = orderPaginator($paginator, array('grade'         => 'gr',
                                                        'description'   => 'gr',
                                                        'institution'   => 'ins')); 
        $grades = array();                      //Array of grades
        switch ($dependency) {
            case 'all': $db = DB::prepare('SELECT gr.*, ins.institution 
                                            FROM grade AS gr, institution AS ins 
                                            WHERE gr.institution_id = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?) 
                                            AND gr.institution_id = ins.id '.$order_param );
                        $db->execute(array($USER->id));
                break;
            case 'institution': $db = DB::prepare('SELECT gr.*, ins.institution 
                                                    FROM grade AS gr, institution AS ins 
                                                    WHERE gr.institution_id = ? 
                                                    AND gr.institution_id = ins.id '.$order_param );
                        $db->execute(array($id));
            default:
                break;
        }      
        
        while($result = $db->fetchObject()) { 
            foreach ($result as $key => $value) {
                $this->$key = $value; 
            }
            $grades[]       = clone $this;        //it has to be clone, to get the object and not the reference
        } 
        return $grades;
    }
    
    /**
    * function used during the install process to set up creator id to new admin
    * @return boolean
    */
    public function dedicate(){ // only use during install
        $db = DB::prepare('UPDATE grade SET institution_id = ?, creator_id = ?');        
        return $db->execute(array($this->institution_id, $this->creator_id));
    }
}