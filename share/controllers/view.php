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
$function = '';
if ($_GET){ 
    switch ($_GET) {
        case isset($_GET['group']): $PAGE->group = $_GET['group'];
                                    $TEMPLATE->assign('page_group',     $PAGE->group);
                                    $group = new Group(); 
                                    $group->id = $_GET['group'];
                                    $group->load(); 
                                    $TEMPLATE->assign('group',     $group);
        case isset($_GET['curriculum']): $PAGE->curriculum = $_GET['curriculum'];
                                    $TEMPLATE->assign('page_curriculum',     $PAGE->curriculum);
            break;
        
        default:
            break;
    }
}

if ($_POST){
    switch ($_POST) {      
        case isset($_POST['add_terminal_objective']):
        case isset($_POST['update_terminal_objective']): $gump = new Gump();    /* Validation */
                                        $terminal_objective = new TerminalObjective();
                                        $terminal_objective->description        = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW); //--> to get html  // security???                       
                                        
                                        $_POST = $gump->sanitize($_POST);       //sanitize $_POST
                                        $terminal_objective->curriculum_id      = $_POST['curriculum_id'];
                                        $terminal_objective->terminal_objective = $_POST['terminal_objective'];
                                        $terminal_objective->color              = $_POST['color'];
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
                                                $ter_id = $terminal_objective->id;
                                            } else {
                                                $ter_id = $terminal_objective->add();   
                                            }
                                            $TEMPLATE->assign('scrollto', 'ter_'.$ter_id); //erspart das scrollen
                                            $curriculum = $_POST['curriculum_id'];
                                        }
                                        if (filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW)){
                                            $omega = new Omega();
                                            $omega->setReference('terminal_objective', $ter_id, filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW));
                                        }
            break;
        
        case isset($_POST['add_enabling_objective']):
        case isset($_POST['update_enabling_objective']): $gump = new Gump();        /* Validation */
                                        $enabling_objective = new EnablingObjective();
                                        $enabling_objective->description            = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);   //--> to get html  // security???                       
                                        $_POST = $gump->sanitize($_POST);           //sanitize $_POST
                                        if (isset($_POST['repeat']) AND $_POST['repeat'] != ''){
                                                    $str_interval = $enabling_objective->getRepeatInterval($_POST['rep_interval']);//aus $_POST['rep_interval'] Tage ermitteln --> über repeat_interval table
                                            } else {
                                                    $str_interval = -1;
                                            }
                                        $enabling_objective->enabling_objective     = $_POST['enabling_objective'];
                                        //$enabling_objective->description            = $_POST['description']; s. o. 
                                        $enabling_objective->terminal_objective_id  = $_POST['terminal_objective_id'];
                                        $enabling_objective->curriculum_id          = $_POST['curriculum_id'];
                                        $enabling_objective->repeat_interval        = $str_interval;
                                        $enabling_objective->creator_id             = $USER->id;
                                        
                                        $gump->validation_rules(array(
                                        'curriculum_id'             => 'required',
                                        'enabling_objective'        => 'required'
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
                                                $enabling_objective->id             = $_POST['id'];
                                                $enabling_objective->update();
                                                $ena_id = $enabling_objective->id; 
                                            } else {
                                                $ena_id = $enabling_objective->add(); 
                                            }
                                            $TEMPLATE->assign('scrollto', 'ena_'.$ena_id); //erspart das scrollen
                                            $curriculum = $_POST['curriculum_id'];
                                        }
                                        $omega = new Omega();
                                        if (filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW)){
                                            $omega->setReference('enabling_objective', $ena_id, filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW));
                                        } else {
                                            $omega->setReference('enabling_objective', $ena_id, ''); // damit update übernommen wird
                                        }
            break;
        case isset($_POST['add_badge']):
        case isset($_POST['update_badge']): $gump = new Gump();        /* Validation */
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

            break;
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
        break;

    default:                $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('course', $PAGE->curriculum, $PAGE->group));
        break;
}

$files = new File(); 
$TEMPLATE->assign('solutions', $files->getSolutions('course', $USER->id, $PAGE->curriculum));  // load solutions