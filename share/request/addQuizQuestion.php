<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename addQuizQuestion.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.12.04 07:59
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER       = $_SESSION['USER'];

//error_log('test: '.$_POST['desc']);
if(isset($_POST['tfDesc']) || isset($_POST['mcDesc'])){
        // add Question
        if ($_POST['tfDesc'] != ''){$question       = $_POST['tfDesc']; $type = 'tf'; $type_id = 0;}
        if ($_POST['mcDesc'] != ''){$question       = $_POST['mcDesc']; $type = 'mc'; $type_id = 1;}
        if ($_POST['saDesc'] != ''){$question       = $_POST['saDesc']; $type = 'sa'; $type_id = 2;}
        $objective_id   = $_POST['objective_id']; 
        $objective_type = $_POST['objective_type']; 
        
        
        $q      = DB::prepare('INSERT INTO quiz_questions (question, type, objective_id, objective_type) VALUES (?,?,?,?)');
        $q->execute(array($question, $type_id, $objective_id, $objective_type));
        $db     = DB::prepare('SELECT MAX(id) as max FROM quiz_questions');
        $db->execute();
        $result = $db->fetchObject();
        $qID    = $result->max; 
	
        $a1 = $type.'answer1';
        switch ($type) {
            /* Type Wahr / Falsch */
            case 'tf':
            case 'mc':  $next = false;
                        if (isset($_POST[$a1])){ $next = true; $c = 1; }                      
                        while ($next) {
                            $answer      = $type.'answer'.$c;
                            $c++;
                            $next_answer = $type.'answer'.$c;
                            if($_POST['iscorrect'] == $answer){
                                $correct = 1;
                            } else {
                                $correct = 0;
                            }
                            if (isset($_POST[$next_answer]) ){ 
                                if ($_POST[$next_answer] != '') { $next = true; } else {$next = false;} // falls nächste Antwort leer
                            } else { $next = false; } 
                            $a           = DB::prepare("INSERT INTO quiz_answers (question_id, answer, correct) VALUES (?, ?, ?)");
                            $a->execute(array($qID, $_POST[$answer], $correct));	  
                        }
                break;
            case 'sa':      $a           = DB::prepare("INSERT INTO quiz_answers (question_id, answer, correct) VALUES (?, ?, ?)");
                            $a->execute(array($qID, $_POST['saanswer1'], 1));
            default:
                break;
        }
        
        $list                 = new Question();
        $list->objective_id   = $objective_id;
        $list->objective_type = $objective_type;
        $q_list               = $list->getQuestions('objective');
        echo '<table id="contenttable"> <tr id="contenttablehead">'
                . '<td>Frage</td>'
                . '<td>Fragetyp</td>'
                . '<td>Lernziel_Typ</td>'
                . '<td>Lernziel_ID</td></tr>';
               
        foreach ($q_list as $item) {
            echo '<tr class="contenttablerow" id="q'.$item->question.'">';
            echo '<td>'.$item->question.'</td>';
            echo '<td>'.$item->type.'</td>';
            echo '<td>'.$item->objective_type.'</td>';
            echo '<td>'.$item->objective_id.' <td><tr> ';
        } 
        echo '</table>';
}