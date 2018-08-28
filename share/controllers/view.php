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

global $CFG, $USER, $PAGE, $TEMPLATE, $INSTITUTION;
$TEMPLATE->assign('breadcrumb',  array('Lehrplan' => 'index.php?action=view'));
$function = '';
$TEMPLATE->assign('page_group',     ''); //prevent error log
$TEMPLATE->assign('needed_curriculum_list', false);  //set to avoid error.log
$c        = new Curriculum();
if ($_GET){ 
    switch ($_GET) {
        case isset($_GET['group']):         $PAGE->group = $_GET['group'];
                                            $TEMPLATE->assign('page_group',     $PAGE->group);
                                            $group       = new Group(); 
                                            $group->id   = $_GET['group'];
                                            $group->load(); 
                                            $TEMPLATE->assign('group',     $group);
                                            /* Testing config_curriculum - get relevant curricula for selecting reference view*/
                                            $c->id       = $_GET['curriculum_id'];
                                            $needed_curriculum_list  = $c->loadConfig();
                                           
                                            $TEMPLATE->assign('needed_curriculum_list', $needed_curriculum_list);  
                                            $reference_curriculum_list  = $c->getCurricula('group',$PAGE->group);
                                            $TEMPLATE->assign('reference_curriculum_list', $reference_curriculum_list);   
                                            if (filter_input(INPUT_GET, 'reference_view', FILTER_VALIDATE_INT)){
                                                $reference_curriculum_id = filter_input(INPUT_GET, 'reference_view', FILTER_VALIDATE_INT);
                                                $TEMPLATE->assign('selected_curriculum_id', $reference_curriculum_id);  
                                            } else {
                                                $TEMPLATE->assign('selected_curriculum_id', false);   
                                            }
                                              
        case isset($_GET['curriculum_id']): $PAGE->curriculum = $_GET['curriculum_id'];
                                            $TEMPLATE->assign('page_curriculum',     $PAGE->curriculum);   
                                            $cur        = new Curriculum();
                                            $cur->id    = $_GET['curriculum_id'];
                                            $cur->load();
                                            
            break;
        
        default:
            break;
    }
}


if ((isset($_GET['function']) AND $_GET['function'] == 'addObjectives')) {
    if (checkCapabilities('curriculum:update', $USER->role_id, false) AND ($cur->creator_id == $USER->id)){ //only edit if capability is set or user == owner
        $function = 'addObjectives';
        $TEMPLATE->assign('showaddObjectives', true); //blendet die addButtons ein
    } else {
        $PAGE->message[] = array('message' => 'Lehrplan kann nur vom Ersteller editiert werden. ', 'icon' => 'fa fa-th text-warning');// Schließen und speichern 
    }
}

/******************************************************************************
 * END POST / GET
 */
Statistic::setStatistics($_SESSION['CONTEXT']['curriculum']->context_id, $PAGE->curriculum); //click counter
$courses = new Course(); // Load course

$terminal_objectives = new TerminalObjective();                                     //load terminal objectives
$enabling_objectives = new EnablingObjective(); 
if (isset($reference_curriculum_id)){
    $ter_ids = $c->getFieldArray($reference_curriculum_id, 'terminal_objectives');
    $ena_ids = $c->getFieldArray($reference_curriculum_id, 'enabling_objectives');
    $ct_ids  = $c->getFieldArray($PAGE->curriculum, 'curriculum_content');
    //error_log(json_encode($ter_ids).json_encode($ena_ids).json_encode($ct_ids));
    $quote   = new Quote(); 
    $TEMPLATE->assign('curriculum_content_references', $quote->get('curriculum_content', $ct_ids, $ter_ids, $ena_ids)); // load quote references
    $ter_objects = $terminal_objectives->getObjectives('curriculum', $PAGE->curriculum, false, $ter_ids, $ena_ids);
    $TEMPLATE->assign('terminal_objectives', $ter_objects);
    $_SESSION['PAGE']->s_key   = 'curriculum_id';
    $_SESSION['PAGE']->s_value = $reference_curriculum_id;
    $TEMPLATE->assign('reference_view', true);
} else {
    $ter_objects = $terminal_objectives->getObjectives('curriculum', $PAGE->curriculum  /*false*/);  // default -> false: only load terminal objectives
    $TEMPLATE->assign('terminal_objectives', $ter_objects);
    $TEMPLATE->assign('reference_view', false);
}
$types = new TerminalObjective();
$ter_obj_given_type_ids = array_unique(array_map(function($e) { return is_object($e) ? $e->type_id : $e['type_id'];}, $ter_objects));//Fix for php version < 7
//$TEMPLATE->assign('ter_obj_given_type_ids', array_unique(array_column($ter_objects, 'type_id')))array column for objects available since php version 7

$TEMPLATE->assign('ter_obj_given_type_ids', $ter_obj_given_type_ids); 
$TEMPLATE->assign('ter_obj_type_id', $types->getType());

$enabling_objectives->curriculum_id = $PAGE->curriculum;

/* curriculum content*/
$content         = new Content();
$content_entries = $content->get('curriculum', $enabling_objectives->curriculum_id);
$TEMPLATE->assign('cur_content', array('label'=>'Digitalisierte Texte des Lehrplans', 'entrys'=> $content_entries));

$course                 = $courses->getCourse('course', $PAGE->curriculum);
$TEMPLATE->assign('course', $course); 
if ($course[0]->icon_id > 0){
  $TEMPLATE->assign('page_bg_file_id', $course[0]->icon_id);
}
$c_menu_array               = array();
$content_menu_obj           = new stdClass();
switch ($function) {
    case 'addObjectives':   $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('curriculum', $PAGE->curriculum));
                            $TEMPLATE->assign('page_title', 'Lehrplaninhalt bearbeiten'); 
                            //splitbutton
                            
                            $content_menu_obj->onclick  = "formloader('content', 'new', null,{'context_id':'2', 'reference_id':'{$PAGE->curriculum}'});";
                            $content_menu_obj->title    = '<i class="fa fa-plus"></i> Neuen Hinweis erstellen';
                            $c_menu_array[]             = clone $content_menu_obj;
                            $content_menu_obj->onclick  = "formloader('content_subscribe','curriculum',null,{'context_id':'2', 'reference_id':'{$PAGE->curriculum}'});";
                            $content_menu_obj->title    = '<i class="fa fa-link"></i>Hinweise aus anderem Lehrplan übernehmen';
                            $c_menu_array[]             = clone $content_menu_obj;
                            $content_menu_obj->onclick  = "formloader('description','curriculum','{$PAGE->curriculum}');";
                            $content_menu_obj->title    = '<i class="fa fa-info" style="padding-right:5px;"></i> Information zum Lehrplan';
                            $c_menu_array[]             = clone $content_menu_obj;
                            if (checkCapabilities('curriculum:update', $USER->role_id, false)){
                                $content_menu_obj->onclick  = "formloader('curriculum','edit','{$PAGE->curriculum}');";
                                $content_menu_obj->title    = '<i class="fa fa-edit" style="padding-right:5px;"></i> Lehrplaneigenschaften bearbeiten';
                                $c_menu_array[]             = clone $content_menu_obj;
                            }
                            
                            
                            
                            $content_menu = array('type' => 'menu', 'label' => '<i class="fa fa-caret-down"></i>', 'entrys' => $c_menu_array);
                            $TEMPLATE->assign('content_menu', $content_menu); 
        break;

    default:                if (checkCapabilities('curriculum:update', $USER->role_id, false) AND $cur->creator_id == $USER->id){
                                $content_menu_obj->onclick  = "location.href='index.php?action=view&function=addObjectives&curriculum_id={$PAGE->curriculum}'";
                                $content_menu_obj->title    = '<i class="fa fa-edit" style="padding-right:5px;"></i> Lehrplan bearbeiten';
                                $c_menu_array[]             = clone $content_menu_obj;
                                $content_menu = array('type' => 'menu', 'label' => '<i class="fa fa-caret-down"></i>', 'entrys' => $c_menu_array);
                                $TEMPLATE->assign('content_menu', $content_menu); 
                            }
                            
                            if (isset($reference_curriculum_id)){
                                $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('course', $PAGE->curriculum, $PAGE->group, $ter_ids, $ena_ids));
                            } else {
                                $TEMPLATE->assign('enabledObjectives', $enabling_objectives->getObjectives('course', $PAGE->curriculum, $PAGE->group));
                            }
                            
                            $TEMPLATE->assign('page_title', 'Lehrplan'); 
        break;
}

$files = new File(); 
$TEMPLATE->assign('solutions', $files->getSolutions('course', $USER->id, $PAGE->curriculum));  // load solutions
/* curriculum files */
$cur_files =  $files->getFiles('curriculum', $PAGE->curriculum, '', array('cur'=> true));  // load cur_files
$TEMPLATE->assign('cur_files', array('label'=>'Dateien zum Lehrplan', 'entrys'=> $cur_files, 'type' => 'file'));

/* curriculum glossar */
$glossar = $content->get('glossar', $enabling_objectives->curriculum_id , 'ORDER by ct.title ASC');
$TEMPLATE->assign('glossar_content', array('label'=>'Glossar', 'entrys'=> $glossar));
if (!empty($glossar)){    
    foreach($glossar as $gl){
        $term_def[] = array('title' => $gl->title, 'content' => $gl->content); 
    }
    $TEMPLATE->assign('glossar_json',json_encode($term_def)); //
} 

if (isset($_SESSION['anchor'])){
    $TEMPLATE->assign('anchor',$_SESSION['anchor']);
    //error_log($_SESSION['anchor']);
    $_SESSION['anchor'] = null;
}