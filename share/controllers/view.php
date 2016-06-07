<?php
/** 
* This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename view.php
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2013.03.08 13:26
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

global $CFG, $USER, $PAGE, $TEMPLATE, $INSTITUTION;
$TEMPLATE->assign('breadcrumb',  array('Lehrplan' => 'index.php?action=view'));
$function = '';
if ($_GET){ 
    switch ($_GET) {
        case isset($_GET['group']): $PAGE->group = $_GET['group'];
                                    $TEMPLATE->assign('page_group',     $PAGE->group);
                                    $group = new Group(); 
                                    $group->id = $_GET['group'];
                                    $group->load(); 
                                    $TEMPLATE->assign('group',     $group);
        case isset($_GET['curriculum_id']): $PAGE->curriculum = $_GET['curriculum_id'];
                                    $TEMPLATE->assign('page_curriculum',     $PAGE->curriculum);
                                    
            break;
        
        default:
            break;
    }
}

if ($_POST){
    foreach ($_POST as $key => $value) {
                error_log($key.': '.$value);
            }
            
    if (isset($_POST['terminal_objective_id']) AND !isset($_POST['enabling_objective_id'])){ $func = 'update_terminal_objective'; } else {$func = 'add_terminal_objective';}
    
    switch ($func) {   
        /*case isset($_POST['add_badge']):
        case isset($_POST['update_badge']): $gump = new Gump();        
                                            $_POST = $gump->sanitize($_POST);           //sanitize $_POST
                                            $badge = new Badges();
                                            
                                            $link = new File();             //load Filename --> besser direkt Link vom uploadframe übergeben lassn (format 2 --> funktioniert im moment nicht richtig)
                                            $link->id = $_POST['badge_image'];
                                            $link->load();
                                            $badge->imageUrl            = $CFG->access_file_url.$link->full_path;
                                            $badge->name                = $_POST['badge_name'];
                                            $badge->type                = $_POST['badge_type'];
                                            $badge->earnerDescription   = $_POST['badge_description'];
                                            $badge->consumerDescription = $_POST['badge_description'];
                                            $badge->criteriaUrl         = $CFG->request_url.'index.php?action=criteria&t='.$_POST['terminal_objective_id'].'&e='.$_POST['enabling_objective_id'];
                                            $badge->criteria            = array (
                                                                          "description" => $_POST['badge_criteria'],
                                                                          "required" => 1,
                                                                          "note" => "",
                                                                            );
                                            $badge->unique              = false;
                                            object_to_array($badge);
                                            $badge->add($_POST['terminal_objective_id'], $_POST['enabling_objective_id'],$_POST['badge_criteria']);

            break;*/
        case isset($_POST['deleteMaterial']):   $file = new File(); 
                                                $file->id = $_POST['id'];
                                                $file->delete();
            break; 
        default:
            break;
    }
}

if ((isset($_GET['function']) AND $_GET['function'] == 'addObjectives') || (isset($_POST['function']) AND $_POST['function'] == 'addObjectives')) {
    $function = 'addObjectives';
    $TEMPLATE->assign('showaddObjectives', true); //blendet die addButtons ein
}
/******************************************************************************
 * END POST / GET
 */

$courses = new Course(); // Load course

$terminal_objectives = new TerminalObjective();                                     //load terminal objectives
$TEMPLATE->assign('terminal_objectives', $terminal_objectives->getObjectives('curriculum', $PAGE->curriculum));

$enabling_objectives = new EnablingObjective();                                     //load enabling objectives
$enabling_objectives->curriculum_id = $PAGE->curriculum;
$TEMPLATE->assign('course', $courses->getCourse('course', $PAGE->curriculum)); 
switch ($function) {
    case 'addObjectives':   $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('curriculum', $PAGE->curriculum));
                            $TEMPLATE->assign('page_title', 'Lehrplaninhalt bearbeiten'); 
        break;

    default:                $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('course', $PAGE->curriculum, $PAGE->group));
                            $TEMPLATE->assign('page_title', 'Lehrplan'); 
        break;
}

$files = new File(); 
$TEMPLATE->assign('solutions', $files->getSolutions('course', $USER->id, $PAGE->curriculum));  // load solutions