<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename groups.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
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
global $USER, $TEMPLATE, $PAGE;

$selectedCurriculum = (isset($_GET['curriculum']) && $_GET['curriculum'] != '' ? $_GET['curriculum'] : '_'); //'_' ist das Trennungszeichen 
$TEMPLATE->assign('selectedCurriculum', $selectedCurriculum);
$curriculum = new Curriculum();

if (isset($_GET['function'])) {
     switch ($_GET['function']) {       
        case "new":     checkCapabilities('groups:add',         $USER->role_id);    // USER berechtigt?
                        $TEMPLATE->assign('showForm',           true); 
                        $TEMPLATE->assign('g_grade_id',         null);              // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('g_semester_id',      null);              // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $TEMPLATE->assign('g_institution_id',   null);              // müssen gesetzt sein um unnötige if-Bedingungen zu vermeiden
                        $institution = new Institution();
                        $TEMPLATE->assign('myInstitutions',     $institution->getInstitutions('user', null, $USER->id));
            break;
        
        case "semester":$TEMPLATE->assign('new_semester_form',  true); 
        case "edit":    $TEMPLATE->assign('showForm',           true);
                        $TEMPLATE->assign('edit_group_form',    true);
                        $institution = new Institution();
                        $TEMPLATE->assign('myInstitutions',     $institution->getInstitutions('user', null, $USER->id));

                        $group       = new Group();
                        $group->id   = filter_input(INPUT_GET, 'group_id',              FILTER_VALIDATE_INT);
                        $group->load();
                        assign_to_template($group, 'g_');   
            break; 
        
        case "expel": 
        case "showCurriculum": 
                        $g_id                 = filter_input(INPUT_GET, 'group_id',     FILTER_VALIDATE_INT);
                        if ($_GET['function'] == "expel"){ // expel group with button
                            $group            = new Group();
                            $group->id        = $g_id;
                            $group->load();
                            $curriculum->id   = filter_input(INPUT_GET, 'curriculumID', FILTER_VALIDATE_INT);
                            if ($group->expel($USER->id, $curriculum->id)) {
                                $curriculum->load();
                                $PAGE->message[]    = 'Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich aus <strong>'.$curriculum->curriculum.'</strong> ausgeschrieben.';  
                            }
                        }
                        
                        $TEMPLATE->assign('showenroledCurriculum', true); 
                        $p_options = array('delete' => array('href'      => "index.php?action=group&function=expel&curriculumID=__id__&group_id=".$g_id, 
                                                            'capability'   => checkCapabilities('groups:expel', $USER->role_id, false)));
                        $p_config  = array('curriculum'  => 'Lehrplan', 
                                           'description' => 'Beschreibung', 
                                           'subject'     => 'Fach',
                                           'grade'       => 'Klassenstufe',
                                           'schooltype'  => 'Schultyp',
                                           'state'       => 'Bundesland/Region',
                                           'de'          => 'Land',
                                           'p_options'   => $p_options);
                        setPaginator('curriculumP', $TEMPLATE, $curriculum->getCurricula('group', $g_id), 'cu_val', 'index.php?action=group&function=showCurriculum&group_id='.$g_id, $p_config); //set Paginator    
                        
                        $TEMPLATE->assign('selected_group_id', $g_id);
            break;        

        default: break;
     }
}

if($_POST){ 
    $group = new Group();
    switch ($_POST) {
        case isset($_POST['enrole']): 
        case isset($_POST['expel']):   foreach ($_POST['id'] as $check ) { 
                                            if ($check == "none" ) {   
                                                if (count($_POST['id']) == 1){  // Diese Abfrage ist wichtig, da sonst Meldungen doppelt ausgegeben werden. 
                                                    $PAGE->message[]    = 'Es muss mindestens eine Lerngruppe ausgewählt werden!'; 
                                                }
                                            } else {
                                                $curriculum->id         = filter_input(INPUT_POST, 'curriculum', FILTER_VALIDATE_INT);
                                                $curriculum->load();
                                                $group->id = $check;
                                                $group->load();
                                                if (isset($_POST['enrole'])){
                                                    if($group->checkEnrolment($curriculum->id ) > 0) { 
                                                        $PAGE->message[] = 'Die Lerngruppe <strong>'.$group->group.'</strong> ist bereits in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.';
                                                    } else {
                                                        $group->enrol($USER->id, $curriculum->id );
                                                        $PAGE->message[] = 'Die Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.';  
                                                    }   
                                                }
                                                if (isset($_POST['expel'])){
                                                    if ($group->expel($USER->id, $curriculum->id )) {
                                                        $PAGE->message[] = 'Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich aus <strong>'.$curriculum->curriculum.'</strong> ausgeschrieben.';  
                                                    } else {
                                                        $PAGE->message[] = 'Lerngruppe <strong>'.$group->group.'</strong> war nicht in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.';  
                                                    }
                                                }
                                            }        
                                        }
            break;
          
        case isset($_POST['add']):
        case isset($_POST['update']):
        case isset($_POST['change']):   if (isset($_POST['g_id'])){
                                            $group->id         = filter_input(INPUT_POST, 'g_id',           FILTER_VALIDATE_INT);
                                        }
                                        $group->group          = filter_input(INPUT_POST, 'g_group',        FILTER_SANITIZE_STRING);
                                        $group->description    = filter_input(INPUT_POST, 'g_description',  FILTER_SANITIZE_STRING);
                                        $group->grade_id       = filter_input(INPUT_POST, 'grade',          FILTER_VALIDATE_INT);
                                        $group->semester_id    = filter_input(INPUT_POST, 'semester',       FILTER_VALIDATE_INT);
                                        $group->creator_id     = $USER->id;  
                                        $group->institution_id = filter_input(INPUT_POST, 'institution',    FILTER_VALIDATE_INT);

                                        $gump = new Gump();                 /* Validation */
                                        $_POST = $gump->sanitize($_POST);   //sanitize $_POST
                                        $gump->validation_rules(array(
                                        'g_group'        => 'required',
                                        'g_description'  => 'required',
                                        'grade'          => 'required',
                                        'semester'       => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);

                                        if($validated_data === false) {/* validation failed */
                                            assign_to_template($group,      'g_'); 
                                            $TEMPLATE->assign('v_error',    $gump->get_readable_errors());     
                                            $TEMPLATE->assign('showForm',   true); 
                                            if (isset($_POST['update'])){
                                                $TEMPLATE->assign('edit_group_form', true);
                                            }
                                        } else {/* validation successful */
                                            if (isset($_POST['add']))   { $group->add(); }
                                            if (isset($_POST['update'])){ $group->update(); }       
                                            if (isset($_POST['change'])){
                                                if ($group->add('semester')) { //assume group members
                                                   if (isset($_POST['assumeUsers'])){ $group->changeSemester(); } 
                                                }
                                                $PAGE->message[] = 'Bei der Klasse <strong>('.$group->group.')</strong> wurde der Lernzeitraum erfolgreich geändert!';
                                            }       
                                        }  
            break;
                                           
        default: break;      
    }
    session_reload_user(); // --> get the changes immediately   
}
/*******************************************************************************
 * END POST / GET 
 */

$TEMPLATE->assign('page_title', 'Lerngruppen verwalten');  

$curricula                  = new Curriculum();                             //Load curricula
$result                     = $curricula->getCurricula('user', $USER->id); 
$TEMPLATE->assign('curriculum_list', $result);

$grades = new Grade();                                                      //Load Grades
$grades->institution_id     = $USER->institutions; 
$TEMPLATE->assign('grade', $grades->getGrades());

$semesters                  = new Semester();                               //Load Semesters
$semesters->institution_id  = $USER->institutions; 
$TEMPLATE->assign('semester', $semesters->getSemesters());

$groups                     = new Group(); 
$p_options = array('delete' => array('onclick'      => "del('group',__id__, $USER->id);", 
                                     'capability'   => checkCapabilities('groups:delete', $USER->role_id, false)),
                   'cal'    => array('href'         => "index.php?action=group&function=semester&group_id=__id__", 
                                     'capability'   => checkCapabilities('groups:changeSemester', $USER->role_id, false)),
                   'edit'   => array('href'         => 'index.php?action=group&function=edit&group_id=__id__'),
                                     'capability'   => checkCapabilities('groups:update', $USER->role_id, false),
                   'list'    => array('href'        => "index.php?action=group&function=showCurriculum&group_id=__id__", 
                                      'capability'  => checkCapabilities('groups:showCurriculumEnrolments', $USER->role_id, false)));
$p_config =   array('groups'        => 'Lerngruppen', 
                    'grade'         => '(Klassen)stufe',
                    'description'   => 'Beschreibung', 
                    'semester'      => 'Lernzeitraum',
                    'institution'   => 'Institution / Schule',
                    'creation_time' => 'Erstellungsdatum',
                    'username'      => 'Erstellt von',
                    'p_options'     => $p_options);
setPaginator('groupP', $TEMPLATE, $groups->getGroups('group', $USER->id,'groupP'), 'gp_val', 'index.php?action=group', $p_config); //set Paginator