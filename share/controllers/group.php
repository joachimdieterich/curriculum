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

$TEMPLATE->assign('page_title', 'Lerngruppen');  
$TEMPLATE->assign('breadcrumb',  array('Lerngruppen' => 'index.php?action=group'));

$selectedCurriculum = (isset($_GET['curriculum']) && $_GET['curriculum'] != '' ? $_GET['curriculum'] : '_'); //'_' ist das Trennungszeichen 
$TEMPLATE->assign('selectedCurriculum', $selectedCurriculum);
$curriculum = new Curriculum();

if (isset($_GET['function'])) {
     switch ($_GET['function']) {       
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
                                $PAGE->message[] = array('message' => 'Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich aus <strong>'.$curriculum->curriculum.'</strong> ausgeschrieben.', 'icon' => 'fa-group text-success');
                                
                            }
                        }
                        
                        $TEMPLATE->assign('showenroledCurriculum', true); 
                        $p_options = array('delete' => array('href'      => "index.php?action=group&function=expel&curriculumID=__id__&group_id=".$g_id, 
                                                            'capability'   => checkCapabilities('groups:expel', $USER->role_id, false)));
                        $p_config  = array('id'         => 'checkbox',
                                           'curriculum'  => 'Lehrplan', 
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

if($_POST ){ 
    $group = new Group();
    switch ($_POST) {
        case isset($_POST['enrol']): 
        case isset($_POST['expel']):   foreach ($_POST['id'] as $check ) { 
                                            if ($check == "none" ) {   
                                                if (count($_POST['id']) == 1){  // Diese Abfrage ist wichtig, da sonst Meldungen doppelt ausgegeben werden. 
                                                    $PAGE->message[] = array('message' => 'Es muss mindestens eine Lerngruppe ausgewählt werden!', 'icon' => 'fa-group text-warning');
                                                }
                                            } else {
                                                $curriculum->id         = filter_input(INPUT_POST, 'curriculum', FILTER_VALIDATE_INT);
                                                $curriculum->load();
                                                $group->id = $check;
                                                $group->load();
                                                if (isset($_POST['enrol'])){
                                                    if($group->checkEnrolment($curriculum->id ) > 0) { 
                                                        $PAGE->message[] = array('message' => 'Die Lerngruppe <strong>'.$group->group.'</strong> ist bereits in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.', 'icon' => 'fa-group text-warning');
                                                    } else {
                                                        $group->enrol($USER->id, $curriculum->id );
                                                        $PAGE->message[] = array('message' => 'Die Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.', 'icon' => 'fa-group text-success');  
                                                    }   
                                                }
                                                if (isset($_POST['expel'])){
                                                    if ($group->expel($USER->id, $curriculum->id )) {
                                                        $PAGE->message[] = array('message' => 'Lerngruppe <strong>'.$group->group.'</strong> wurde erfolgreich aus <strong>'.$curriculum->curriculum.'</strong> ausgeschrieben.', 'icon' => 'fa-group text-success');
                                                    } else {
                                                        $PAGE->message[] = array('message' => 'Lerngruppe <strong>'.$group->group.'</strong> war nicht in <strong>'.$curriculum->curriculum.'</strong> eingeschrieben.', 'icon' => 'fa-group text-warning');
                                                    }
                                                }
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
                                     'capability'   => checkCapabilities('groups:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-minus'),
                   'cal'    => array('onclick'      => 'formloader(\'group\',\'semester\',__id__)',
                                     'capability'   => checkCapabilities('groups:changeSemester', $USER->role_id, false),
                                     'icon'         => 'fa fa-calendar'),
                   'edit'   => array('onclick'      => 'formloader(\'group\',\'edit\',__id__)',
                                     'capability'   => checkCapabilities('groups:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit'),
                   'list'    => array('href'        => "index.php?action=group&function=showCurriculum&group_id=__id__", 
                                      'capability'  => checkCapabilities('groups:showCurriculumEnrolments', $USER->role_id, false),
                                      'icon'        => 'fa fa-list'));
$p_config =   array('id'         => 'checkbox',
                    'group'        => 'Lerngruppen',          //ändern auf groups in Object, da db Eintrag groups ist und die Suche so nicht funktioniert
                    'grade'         => '(Klassen)stufe',  
                    'description'   => 'Beschreibung', 
                    'semester'      => 'Lernzeitraum',
                    'institution'   => 'Institution / Schule',
                    'creation_time' => 'Erstellungsdatum',
                    'username'      => 'Erstellt von',
                    'p_options'     => $p_options);
setPaginator('groupP', $TEMPLATE, $groups->getGroups('group', $USER->id,'groupP'), 'gp_val', 'index.php?action=group', $p_config); //set Paginator