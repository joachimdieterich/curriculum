<?php
/**
* CourseBook
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename coursebook.class.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.07 10:48
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
class User_enrolments {

    public $id;
    public $user_id;
    public $context_id;
    public $reference_id;
    public $creator_id;
   
    public function add(){
        $db = DB::prepare('INSERT INTO user_enrolments(user_id, context_id, reference_id, creator_id) VALUES (?,?,?,?)');
        $db->execute(array($this->user_id, $this->context_id, $this->reference_id, $this->creator_id));
        $this->id = DB::lastInsertId();
    }
    
    public function load(){
        $db = DB::prepare('SELECT * FROM user_enrolments WHERE id = ?');
        $db->execute(array($this->id));
        foreach($db->fetchObject() AS $key=>$value){
            $this->$key = $value;
        }
    }
    
    
    
    public function delete(){
        $db = DB::prepare('DELETE FROM user_enrolments WHERE id = ?');
        return $db->execute(array($this->id));
    }
    
    
    
    public static function loadUserIdsByContextReference($context_id, $reference_id){
        $db = DB::prepare('SELECT user_id '
                . 'FROM user_enrolments '
                . 'WHERE context_id = ? '
                . 'AND reference_id = ?');
        $db->execute(array($context_id, $reference_id));
        $erg = array();
        while ($result = $db->fetchObject()){
            $erg[] = $result->user_id;
        }
        return $erg;
    }
    
}