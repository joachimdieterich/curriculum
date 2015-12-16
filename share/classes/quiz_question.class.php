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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
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
        $db     = DB::prepare('SELECT * FROM quiz_questions WHERE id = ?');
        $db->execute(array($this->id));
        $result = $db->fetchObject();        
        $this->id               = $result->id;
        $this->question         = $result->question;

        $a = new Answer();
        $a->question_id         = $this->id; 
        $this->answers          = $a->getAnswers();
        $this->type             = $result->type;
        $this->objective_type   = $result->objective_type;
        $this->objective_id     = $result->objective_id;
    }
    
    public function getQuestions($dependency = 'objective'){
        switch ($dependency) {
            case 'objective':   $db     = DB::prepare('SELECT * FROM quiz_questions WHERE objective_type = ? AND objective_id = ?');
                                $db->execute(array($this->objective_type, $this->objective_id));
                break;

            default:
                break;
        }
        
        while ($result = $db->fetchObject()) {
            $this->id               = $result->id;
            $this->question         = $result->question;
            
            $a = new Answer();
            $a->question_id         = $this->id; 
            $this->answers          = $a->getAnswers();
            $this->type             = $result->type;
            $this->objective_type   = $result->objective_type;
            $this->objective_id     = $result->objective_id;
            $questions[] = clone $this;
        }
        if (isset($questions)){
            return $questions;
        } else {
            return false;
        }
    }

    
 
}