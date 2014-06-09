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
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $PAGE, $TEMPLATE;
$function = '';

$TEMPLATE->assign('page_group',     $PAGE->group);
if ($_GET){ 
    
    switch ($_GET) {
        case isset($_GET['group']): $PAGE->group = $_GET['group'];
                                    $group = new Group(); 
                                    $group->id = $_GET['group'];
                                    $group->load(); 
                                    $TEMPLATE->assign('group',     $group);
        case isset($_GET['curriculum']): $PAGE->curriculum = $_GET['curriculum'];
            break;
        
        default:
            break;
    }
}


if ($_POST){
    switch ($_POST) {
        case isset($_POST['add_terminal_objective']):
        case isset($_POST['update_terminal_objective']): $gump = new Gump(); /* Validation */
                                        $terminal_objective = new TerminalObjective();
                                        $terminal_objective->curriculum_id     = $_POST['curriculum_id'];
                                        $terminal_objective->terminal_objective = $_POST['terminal_objective'];
                                        $terminal_objective->description        = $_POST['description'];
                                        $terminal_objective->creator_id         = $USER->id;
                                        $gump->validation_rules(array(
                                        'terminal_objective'         => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                            foreach($terminal_objective as $key => $value){
                                            $TEMPLATE->assign($key, $value);
                                            } 
                                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                            $TEMPLATE->assign('editObjective', true); 
                                        } else {
                                            if (isset($_POST['update_terminal_objective'])){
                                                $terminal_objective->id                 = $_POST['id'];
                                                $terminal_objective->update();  
                                            } else {
                                                $terminal_objective->add();
                                                //???$TEMPLATE->assign('scrollto', $terminal_objective->terminal_objective); //damit man nicht jedes mal scrollen muss = sprungmarke
                                            }
                                            $curriculum = $_POST['curriculum_id'];
                                        }
            break;
        
        case isset($_POST['add_enabling_objective']):
        case isset($_POST['update_enabling_objective']): $gump = new Gump(); /* Validation */
                                        $enabling_objective = new EnablingObjective();
                                        if (isset($_POST['repeat']) AND $_POST['repeat'] != ''){
                                                    $str_interval = $enabling_objective->getRepeatInterval($_POST['rep_interval']);//aus $_POST['rep_interval'] Tage ermitteln --> über repeat_interval table
                                            } else {
                                                    $str_interval = -1;
                                            }
                                        $enabling_objective->enabling_objective     = $_POST['enabling_objective'];
                                        $enabling_objective->description            = $_POST['description'];
                                        $enabling_objective->terminal_objective_id  = $_POST['terminal_objective_id'];
                                        $enabling_objective->curriculum_id          = $_POST['curriculum_id'];
                                        $enabling_objective->repeat_interval        = $str_interval;
                                        $enabling_objective->creator_id             = $USER->id;
                                        
                                        $gump->validation_rules(array(
                                        'curriculum_id'             => 'required',
                                        'enabling_objective'         => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                            foreach($enabling_objective as $key => $value){
                                            $TEMPLATE->assign($key, $value);
                                            } 
                                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                            $TEMPLATE->assign('editObjective', true); 
                                        } else {  
                                            if (isset($_POST['update_enabling_objective'])){
                                                $enabling_objective->id                     = $_POST['id'];
                                                $enabling_objective->update(); 
                                            } else {
                                                $enabling_objective->add();
                                                $TEMPLATE->assign('scrollto', $enabling_objective->terminal_objective_id); //damit man nicht jedes mal scrollen muss = sprungmarke
                                            }
                                            $curriculum = $_POST['curriculum_id'];
                                        }
            break;
        case isset($_POST['deleteMaterial']):   $file = new File(); 
                                                $file->id = $_POST['id'];
                                                $file->delete();
            
            break;
        default:
            break;
    }
    
    $TEMPLATE->assign('page_message', $PAGE->message);
}

if ((isset($_GET['function']) AND $_GET['function'] == 'addObjectives') || (isset($_POST['function']) AND $_POST['function'] == 'addObjectives')) {
    $function = 'addObjectives';
    $TEMPLATE->assign('showaddObjectives', true); //blendet die addButtons ein
}
/******************************************************************************
 * END POST / GET
 */

$courses = new Course(); // Load course

//capabilies
$TEMPLATE->assign('file_solutionUpload', checkCapabilities('file:solutionUpload', $USER->role_id), false);  
$TEMPLATE->assign('file_loadMaterial', checkCapabilities('file:loadMaterial', $USER->role_id), false);  

//load terminal objectives
$terminal_objectives = new TerminalObjective();
$TEMPLATE->assign('terminal_objectives', $terminal_objectives->getObjectives('curriculum', $PAGE->curriculum));

//load enabling objectives
$enabling_objectives = new EnablingObjective();
$enabling_objectives->curriculum_id = $PAGE->curriculum;
switch ($function) {
    case 'addObjectives':   $TEMPLATE->assign('course', $courses->getCourse('course', $PAGE->curriculum)); 
                            $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('curriculum', $PAGE->curriculum));
        break;

    default:                $TEMPLATE->assign('course', $courses->getCourse('course', $PAGE->curriculum));  
                            $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('course', $PAGE->curriculum, $PAGE->group));
        break;
}

// load solutions
$files = new File(); 
$TEMPLATE->assign('solutions', $files->getSolutions('course', $PAGE->curriculum, $USER->id));  
?>