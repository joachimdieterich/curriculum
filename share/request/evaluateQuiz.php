<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename evaluateQuiz.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.12.06 15:13
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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER       = $_SESSION['USER'];

$html = '';
$question   = new Question();
$i          = 0; 
$c          = 0;

foreach($_POST as $key =>$value){
    $question->id        = $key;
    $question->load();
    //echo 'key: ',$key,' value: ',$value;
    
    switch ($question->type) {
        case 0:         //echo '<br>c answer: ',$question->answers[0]->correct;
                        if ($question->answers[0]->correct == $value){  $c++; } 
            break;
        case 1:         $quiz_answer  = new Answer();
                        $quiz_answer->question_id = $question->id;
                        $quiz_answer->load('correct');
                        if ($quiz_answer->id == $value){  $c++; } 
            break;
        case 2:         if ($question->answers[0]->answer == $value){  $c++; } 
            break;

        default:
            break;
    }
    $i++;
}
$enabling_objectives = new EnablingObjective();
if ( $question->objective_type == 1){ // Fall dass ein Thema mit einem Test versehen wird ist noch nicht fertig
    $enabling_objectives->id = $question->objective_id;
}
$percent = round($c/$i*100);
if ($percent >= 90) {
    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, 1); 
    $html .= '<p>Sie haben '.$c.' von '.$i.' Fragen ('. $percent .'%) richtig beantwortet. Das Ziel ... wurde auf grün gesetzt.</p>';        
} else if ($percent >= 50 AND $percent < 90 ) {
    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, 2);
    $html .=  '<p>Sie haben '.$c.' von '.$i.' Fragen ('. $percent .'%) richtig beantwortet. Das Ziel ... wurde auf orange gesetzt.</p>';
} else {
    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, 0); 
    $html .=  '<p>Sie haben '.$c.' von '.$i.' Fragen ('. $percent .'%) richtig beantwortet. Das Ziel ... wurde auf rot gesetzt</p>';
}

$q_list               = $question->getQuestions('objective');

$html .= Render::quiz($q_list, $_POST, true);
echo $html;

//object_to_array($_POST);