<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename showQuiz.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.12.03 19:37
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
if (filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT)) {
    $objective_id   =  filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT);
    $objective_type = 1;
} else {
    $ter_id         =  filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT);
    $objective_type = 0;
}
$list                 = new Question();
$list->objective_id   = $objective_id;
$list->objective_type = $objective_type;
$q_list               = $list->getQuestions('objective');

echo '<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">Quiz</div>
      <div id="popupcontent" class="scroll"><div id="ajax_quiz_form_result">';

Render::quiz($q_list);

echo '</div><div class="materialseperator"></div><div class="space-top"></div>';
/*echo '<input type="submit" name="Submit" value="Fenster schließen" onclick="closePopup()"/>';*/
echo '</div></div>';