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

class Question {
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
    public $answers; 
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

    public function load(){
        $db                     = DB::prepare('SELECT * FROM quiz_questions WHERE id = ?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();        
        $this->id               = $result->id;
        $this->question         = $result->question;
        $a                      = new Answer();
        $a->question_id         = $this->id; 
        $this->answers          = $a->getAnswers();
        $this->type             = $result->type;
        $this->objective_type   = $result->objective_type;
        $this->objective_id     = $result->objective_id;
    }
    
    public function getQuestions($dependency = 'objective'){
        switch ($dependency) {
            case 'objective':   $db = DB::prepare('SELECT * FROM quiz_questions WHERE objective_type = ? AND objective_id = ?');
                                $db->execute(array($this->objective_type, $this->objective_id));
                break;

            default:
                break;
        }
        
        while ($result = $db->fetchObject()) {
            $this->id               = $result->id;
            $this->question         = $result->question;
            $a                      = new Answer();
            $a->question_id         = $this->id; 
            $this->answers          = $a->getAnswers();
            $this->type             = $result->type;
            $this->objective_type   = $result->objective_type;
            $this->objective_id     = $result->objective_id;
            $questions[]            = clone $this;
        }
        if (isset($questions)){
            return $questions;
        } else {
            return false;
        }
    }
    
    public function countQuestions($dependency = 'objective'){
        switch ($dependency) {
            case 'objective':   $db = DB::prepare('SELECT count(id) FROM quiz_questions WHERE objective_type = ? AND objective_id = ?');
                                $db->execute(array($this->objective_type, $this->objective_id));
                break;

            default:
                break;
        }
        
        $max = $db->fetchColumn();
        return $max;
    }
    
}