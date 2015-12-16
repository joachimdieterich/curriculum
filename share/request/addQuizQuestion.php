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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

global $USER;
$USER       = $_SESSION['USER'];

//error_log('test: '.$_POST['desc']);
if(isset($_POST['tfDesc']) || isset($_POST['mcDesc'])){
        // add Question
        if ($_POST['tfDesc'] != ''){$question       = $_POST['tfDesc']; $type = 'tf'; $type_id = 0;}
        if ($_POST['mcDesc'] != ''){$question       = $_POST['mcDesc']; $type = 'mc'; $type_id = 1;}
        if ($_POST['saDesc'] != ''){$question       = $_POST['saDesc']; $type = 'sa'; $type_id = 2;}
        if ($_POST['ena_id'] != ''){
            $objective_id   = $_POST['ena_id']; $objective_type = 1;
        } else if ($_POST['ter_id'] != ''){
            $objective_id   = $_POST['ter_id']; $objective_type = 0;
        }
        
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