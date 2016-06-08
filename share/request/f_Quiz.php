<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_Quiz.php
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
$func       = $_GET['func'];
switch ($func) {
    case 'terminal_objective':  $objective_id   =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $objective_type = 0;
        break;
    case 'enabling_objective':  $objective_id   =  filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $objective_type = 1;                       
        break;

    default:
        break;
}

$list                 = new Question();
$list->objective_id   = $objective_id;
$list->objective_type = $objective_type;
$q_list               = $list->getQuestions('objective');
$content              = Render::quiz($q_list);
$html                 = Form::modal(array('title'     => 'Quiz',
                              'content'   => $content, 
                              'f_content' => ''));  

echo json_encode(array('html'=> $html));