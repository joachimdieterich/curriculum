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
global $USER, $TEMPLATE, $PAGE;

$TEMPLATE->assign('page_title', 'Lerngruppen');  
$TEMPLATE->assign('breadcrumb',  array('Lerngruppen' => 'index.php?action=group'));

$selectedCurriculum = (isset($_GET['curriculum']) && $_GET['curriculum'] != '' ? $_GET['curriculum'] : '_'); //'_' ist das Trennungszeichen 
$TEMPLATE->assign('selectedCurriculum', $selectedCurriculum);
$curriculum = new Curriculum();

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
                                                $cur_array = $_POST['curriculum'];
                                                foreach($cur_array as $c) {
                                                    $curriculum->id         = $c;
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
                                        }
            break;
                                           
        default: break;      
    }
    //session_reload_user(); // --> get the changes immediately  
    
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
$p_options = array('delete' => array('onclick'      => "del('group',__id__);", 
                                     'capability'   => checkCapabilities('groups:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-minus', 
                                     'tooltip'      => 'löschen'),
                   'cal'    => array('onclick'      => 'formloader(\'group\',\'semester\',__id__)',
                                     'capability'   => checkCapabilities('groups:changeSemester', $USER->role_id, false),
                                     'icon'         => 'fa fa-calendar',
                                     'tooltip'      => 'Lernzeitraum ändern'),
                   'edit'   => array('onclick'      => 'formloader(\'group\',\'edit\',__id__)',
                                     'capability'   => checkCapabilities('groups:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit',
                                     'tooltip'      => 'bearbeiten'),
                   'list'    => array('onclick'     => "formloader('preview_group', 'full', __id__)", 
                                      'capability'  => checkCapabilities('groups:showCurriculumEnrolments', $USER->role_id, false),
                                      'icon'        => 'fa fa-list-alt',
                                      'tooltip'     => 'Überblick'));
$p_config =   array('id'            => 'checkbox',
                    'group'         => 'Lerngruppen',          //ändern auf groups in Object, da db Eintrag groups ist und die Suche so nicht funktioniert
                    'grade'         => '(Klassen)stufe',  
                    'description'   => 'Beschreibung', 
                    'semester'      => 'Lernzeitraum',
                    'institution'   => 'Institution / Schule',
                    'creation_time' => 'Erstellungsdatum',
                    'username'      => 'Erstellt von',
                    'p_options'     => $p_options);
setPaginator('groupP', $TEMPLATE, $groups->getGroups('group', $USER->id,'groupP'), 'gp_val', 'index.php?action=group', $p_config); //set Paginator