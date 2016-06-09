<?php
/**
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename quiz_answer.class.php
* @copyright 2015 joachimdieterich
* @author joachimdieterich
* @date 2013.12.05 00:28
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

class Answer {
    /**
     * ID of quiz
     * @var int
     */
    public $id; 
    public $answer;
    public $correct;
    public $question_id;
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

    
    
    public function __construct($id = '') {
        if ($id != ''){
            $this->id = $id;
            $this->load();
        }
    }
    
    public function load($dependency = 'id'){
        switch ($dependency) {
            case 'id':      $db = DB::prepare('SELECT * FROM quiz_answers WHERE id = ?');
                            $db->execute(array($this->id));
                break;
            case 'correct': $db = DB::prepare('SELECT * FROM quiz_answers WHERE question_id = ? AND correct = 1');
                            $db->execute(array($this->question_id));
                break;

            default:
                break;
        }
        
        $result = $db->fetchObject();
        $this->id                = $result->id;
        $this->answer            = $result->answer;
        $this->correct           = $result->correct;
        $this->question_id       = $result->question_id;
    }


    public function getAnswers(){
        $db           = DB::prepare('SELECT * FROM quiz_answers WHERE question_id = ?');
        $db->execute(array($this->question_id));
        while($result = $db->fetchObject()) { 
                $this->id                = $result->id;
                $this->answer            = $result->answer;
                $this->correct           = $result->correct;
                $this->question_id       = $result->question_id;
                $answers[]               = clone $this;
        }
        return $answers;
    }
    
    /**
     * Add a new semester to db
     * @return mixed 
     */
    /*public function add(){
        global $USER;
        checkCapabilities('semester:add', $USER->role_id);
        $db = DB::prepare('SELECT COUNT(id) FROM semester WHERE UPPER(semester) = UPPER(?) AND institution_id = ?');
        $db->execute(array($this->semester, $this->institution_id));
        if($db->fetchColumn() >= 1) { 
            return 'Diesen Lernzeitraum gibt es bereits.';
        } else {
            $db = DB::prepare('INSERT INTO semester (semester,description,begin,end,creation_time,creator_id,institution_id)
                                            VALUES (?,?,?,?,NOW(),?,?)');
            return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $this->creator_id, $this->institution_id));	
        }   
    }*/
    
    /**
     * Update semester 
     * @return boolean 
     */
    /*public function update(){
        global $USER;
        checkCapabilities('semester:update', $USER->role_id);
        $db = DB::prepare('UPDATE semester SET semester = ?, description = ?, begin = ?, end = ? WHERE id = ?');
        return $db->execute(array($this->semester, $this->description, $this->begin, $this->end, $this->id));
    }*/
    
    /**
     * Delete current semester
     * @return boolean 
     */
    /*public function delete(){
        global $USER;
        checkCapabilities('semester:delete', $USER->role_id);
        $db     = DB::prepare('SELECT id FROM groups WHERE semester_id = ?');
        $db->execute(array($this->id));
        if ($db->fetchObject()){
            return false; 
        } else {
            $db = DB::prepare('DELETE FROM semester WHERE id = ?');
            return $db->execute(array($this->id));
        }
    }*/
    
    /**
     * Load semester 
     */
    /*public function load(){
        $db = DB::prepare('SELECT * FROM quiz WHERE id = ?');
        $db->execute(array($this->id));
        $result                  = $db->fetchObject();
        $this->id                = $result->id;
        $this->semester          = $result->semester;
        $this->description       = $result->description;
        $this->begin             = $result->begin;
        $this->end               = $result->end;
        $this->institution_id    = $result->institution_id;
    }*/
    
 
}