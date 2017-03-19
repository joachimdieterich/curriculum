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

$html       = '';
$question   = new Question();
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
}
$i         = $question->countQuestions();
$enabling_objectives = new EnablingObjective();
if ( $question->objective_type == 1){ // Fall dass ein Thema mit einem Test versehen wird ist noch nicht fertig
    $enabling_objectives->id = $question->objective_id;
}
$percent   = round($c/$i*100);
if ($percent >= 90) {
    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, '11'); 
    $html .= '<p>Sie haben '.$c.' von '.$i.' Fragen ('. $percent .'%) richtig beantwortet. Das Ziel ... wurde auf grün gesetzt.</p>';        
} else if ($percent >= 50 AND $percent < 90 ) {
    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, '22');
    $html .=  '<p>Sie haben '.$c.' von '.$i.' Fragen ('. $percent .'%) richtig beantwortet. Das Ziel ... wurde auf orange gesetzt.</p>';
} else {
    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, '00'); 
    $html .=  '<p>Sie haben '.$c.' von '.$i.' Fragen ('. $percent .'%) richtig beantwortet. Das Ziel ... wurde auf rot gesetzt</p>';
}

$q_list    = $question->getQuestions('objective');
$html     .= Render::quiz($q_list, $_POST, true);
echo $html;
