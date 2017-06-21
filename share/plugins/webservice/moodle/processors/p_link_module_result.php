<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* Processor
* @package plugin
* @filename p_link_module_result.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.06.09 11:20
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
$base_url  = '../../../../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include($base_url.'login-check.php');  //check login status and reset idletimer
global $USER, $CFG, $PAGE;
$USER   = $_SESSION['USER'];
$func   = filter_input(INPUT_GET, 'func', FILTER_SANITIZE_STRING);

switch ($func) {
    case "enabling_objective":  $ws          = get_plugin('webservice', $CFG->settings->webservice);
                                $link_module = $ws->link_module_results($_SESSION['CONTEXT'][$func]->id, filter_input(INPUT_GET, 'val', FILTER_SANITIZE_STRING));
                                $moodle_user = json_decode($ws->core_user_get_users('username', $USER->username));
                                $config_data = json_decode($link_module->config_data);
                                //error_log(json_encode($config_data));
                                $params      = array('courseid' => $config_data->moodle_course_id, 'component' => 'mod_quiz', 'activityid' => $config_data->moodle_module_id, 'userids' => array($moodle_user->users[0]->id));
                                $grades      = json_decode($ws->call('core_grades_get_grades', $params));
                                //error_log($ws->call('core_grades_get_grades', $params)); 
                                
                                $enabling_objectives        = new EnablingObjective();
                                $enabling_objectives->id    = filter_input(INPUT_GET, 'val', FILTER_SANITIZE_STRING); 
                                $enabling_objectives->load();
                                if ($grades->items[0]->grades[0]->grade > 0){
                                    $users_result = ($grades->items[0]->grademax*100)/$grades->items[0]->grades[0]->grade;
                                } else {
                                    $users_result = 0;
                                }
                                if ($config_data->moodle_percent <= $users_result){
                                    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, '11');
                                    $_SESSION['PAGE']->message[] = array('message' => 'Sie haben die Aktivität <strong>'.$grades->items[0]->name.'</strong> mit <strong>'.$users_result.'%</strong> abgeschlossen. Lernziel / Kompetenz wurde erfolgreich erreicht.', 'icon' => 'fa fa-check-circle-o text-success');// Schließen und speichern
                                } else {
                                    $enabling_objectives->setAccomplishedStatus('quiz', $USER->id, $USER->id, '00');
                                    $_SESSION['PAGE']->message[] = array('message' => 'Sie haben die Aktivität <strong>'.$grades->items[0]->name.'</strong> mit <strong>'.$users_result.'%</strong> abgeschlossen. Das Lernziel / Kompetenz wird bei '.$config_data->moodle_percent.'% abgeschlossen.', 'icon' => 'fa fa-check-circle-o text-danger');// Schließen und speichern
                                }
        break;
                            
    default: break;
}