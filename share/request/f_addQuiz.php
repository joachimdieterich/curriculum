<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_addQuiz.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.12.03 19:46
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
$content       = '';
$func       = $_GET['func'];
switch ($func) {
    case 'terminal_objective':  $objective_id   =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $objective_type  = 0;
        break;
    case 'enabling_objective':  $objective_id   =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $objective_type  = 1;                       
        break;

    default:
        break;
}

$content .= '<div class=   "messageboxClose" onclick="closePopup();"></div><div class="contentheader">Quiz erstellen</div>
      <div id="popupcontent" class="scroll">';

$content .= '<div><p><div id="ajax_form_result">';
// show existing questions
$list                 = new Question();
$list->objective_id   = $objective_id;
$list->objective_type = $objective_type;
$q_list               = $list->getQuestions('objective');
if ($q_list){
    $content .= '<table id="contenttable"> <tr id="contenttablehead">'
            . '<td>Frage</td>'
            . '<td>Fragetyp</td>'
            . '<td>Lernziel_Typ</td>'
            . '<td>Lernziel_ID</td></tr>';

    foreach ($q_list as $item) {
        $content .= '<tr class="contenttablerow" id="q'.$item->question.'">';
        $content .= '<td>'.$item->question.'</td>';
        $content .= '<td>'.$item->type.'</td>';
        $content .= '<td>'.$item->objective_type.'</td>';
        $content .= '<td>'.$item->objective_id.' <td><tr> ';
    } 
    $content .= '</table>';
} else {
    $content .= '<p>Es wurden noch keine Testfragen angelegt</p>';
}
$content .= '</div></p><br>

<button onClick="toggle([\'tf\'], [\'mc\', \'sa\']);">True/False</button>
<button onClick="toggle([\'mc\'], [\'tf\', \'sa\']);">Multiple Choice</button>
<button onClick="toggle([\'sa\'], [\'tf\', \'mc\']);">Kurzantwort</button></div><bR>    
    <form id="ajax_form" action="" name="addQuestion" method="post">
        <input type="hidden" id="objective_id" name="objective_id" value="'.$objective_id.'">
        <input type="hidden" id="objective_type" name="objective_type" value="'.$objective_type.'">
        <div class="hidden" id="tf">
        <h3>Wahr oder Falsch</h3><br>
        <p><input type="text" id="tfDesc" name="tfDesc"></input></p><br>
        <strong>Ist die Antwort wahr oder falsch?</strong><br>
            <input type="hidden" id="tfanswer1" name="tfanswer1" value="">
            <label style="cursor:pointer;"><input style="width:40px;" type="radio" name="iscorrect" value="tfanswer1">Wahr</label><br> 
            <input type="hidden" id="tfanswer2" name="tfanswer2" value="">
            <label style="cursor:pointer;"><input style="width:40px;" type="radio" name="iscorrect" value="tfanswer2">Falsch</label><br>
        <input type="hidden" value="tf" name="type"><br>
        <input value="Frage zum Quiz hinzufügen" onclick="sendForm(\'ajax_form\', \'addQuizQuestion.php\')">
        </div>

        <div class="hidden" id="mc">
        <h3>Multiple Choice Quiz</h3><br>
        <p><strong>Fragetext</strong></p>
        <p><input type="text" id="mcDesc" name="mcDesc"></input></p><br>
        <p><strong>Antwort 1</strong></p>
        <input type="text" id="mcanswer1" name="mcanswer1">
        <label style="cursor:pointer; color:#06F;"><input style="width:40px;" type="radio" name="iscorrect" value="mcanswer1">Richtige Antwort? </label><br>
        <p><strong>Antwort 2</strong></p>
        <input type="text" id="mcanswer2" name="mcanswer2">
        <label style="cursor:pointer; color:#06F;"><input style="width:40px;" type="radio" name="iscorrect" value="mcanswer2">Richtige Antwort?</label><br>
        <p><strong>Antwort 3</strong></p>
        <input type="text" id="mcanswer3" name="mcanswer3">
        <label style="cursor:pointer; color:#06F;"><input style="width:40px;" type="radio" name="iscorrect" value="mcanswer3">Richtige Antwort?</label><br>
        <p><strong>Antwort 4</strong></p>
        <input type="text" id="mcanswer4" name="mcanswer4">
        <label style="cursor:pointer; color:#06F;"><input style="width:40px;" type="radio" name="iscorrect" value="mcanswer4">Richtige Antwort?</label><br>
        <input type="hidden" value="mc" name="type"><br>
        <input value="Frage zum Quiz hinzufügen" onclick="sendForm(\'ajax_form\', \'addQuizQuestion.php\')">
        </div>

        <div class="hidden" id="sa">
        <h3>Kurzantwort</h3><br>
        <p><strong>Fragetext</strong></p>
        <p><input type="text" id="saDesc" name="saDesc"></input></p><br>
        <p><strong>Antwort</strong></p>
        <input type="text" id="saanswer1" name="saanswer1"><br><br>
        <input type="hidden" value="sa" name="type">
        <input value="Frage zum Quiz hinzufügen" onclick="sendForm(\'ajax_form\', \'addQuizQuestion.php\')">
        </div><br>

    </form>' ;
$content .= '</p></div></div>';
$html      = Form::modal(array('title'     => 'Quiz hinzufügen',
                              'content'   => $content, 
                              'f_content' => ''));  

echo json_encode(array('html'=> $html));