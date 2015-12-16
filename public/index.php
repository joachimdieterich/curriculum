<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename index.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.08 13:26
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful,but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
include('../share/setup.php');
global $CFG, $PAGE, $TEMPLATE, $USER;

try { // Error handling
    $PAGE           = new stdClass();
    $PAGE->action   = filter_input(INPUT_GET, 'action', FILTER_UNSAFE_RAW);
    if (!$PAGE->action) { $PAGE->action = 'login'; }
 
 switch ($PAGE->action) {
     case 'login':  
     case 'install':  
     case 'criteria':  //wichtig für Badges-Info ohne Login
         break;
     
     default:       if(filter_input(INPUT_POST, 'mySemester', FILTER_VALIDATE_INT)){                                          // Lernzeitraum wurde gewechselt --> vor session.php damit Änderungen übernommen werden
                        $_SESSION['USER']->semester_id    = filter_input(INPUT_POST, 'mySemester', FILTER_VALIDATE_INT);      // Neuer Lernzeitraum übernehmen
                        $TEMPLATE->assign('my_semester_id', $_SESSION['USER']->semester_id); 
                        $change_semester      = new Semester($_SESSION['USER']->semester_id);
                        $us = new User();                                                                                     // $USER hier noch nicht verfügbar
                        $us->id = $_SESSION['USER']->id;
                        $us->setSemester($_SESSION['USER']->semester_id);
                        $_SESSION['USER'] = NULL;                                                                             // Beim Wechsel des Lerzeitraumes muss Session neu geladen werden, damit die entsprechende Rolle geladen wird.
                    }   
                    require ('../share/session.php');                                                           // Erst Session aufbauen damit $USER verfügbar ist, dann login-check!
                    require ('../share/login-check.php');                                                       // Check ob Session abgelaufen ist
                    
                    $TEMPLATE->assign('mySemester', $_SESSION['SEMESTER']);                                     // ARRAY mit Lernzeiträumen in die der USER eingeschrieben ist.
                    if (isset($_SESSION['username'])){
                        $TEMPLATE->assign('loginname', $_SESSION['username']);                                      
                    }
                    $TEMPLATE->assign('stat_users_online', $USER->usersOnline($USER->institutions));  
                    $statistics = new Statistic();
                    $TEMPLATE->assign('stat_acc_all',       $statistics->getAccomplishedObjectives('all'));  
                    $TEMPLATE->assign('stat_acc_today',     $statistics->getAccomplishedObjectives('today'));  
                    $TEMPLATE->assign('stat_users_today',     $statistics->getUsersOnline('today'));  
                    detect_reload();   
                    
         break;
 }
    /**
     * Sortierung der Paginatoren
     */
    if (filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'sort', FILTER_UNSAFE_RAW) && filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW)){
        SmartyPaginate::setSort(filter_input(INPUT_GET, 'order', FILTER_UNSAFE_RAW),filter_input(INPUT_GET, 'sort', FILTER_UNSAFE_RAW), filter_input(INPUT_GET, 'paginator', FILTER_UNSAFE_RAW));
    }

    /**
    * load controller 
    */ 
    $PAGE->controller = $CFG->controllers_root.'/'.$PAGE->action .'.php';
    if (file_exists($PAGE->controller)) {                                               
        include($PAGE->controller);  
        $TEMPLATE->assign('page_name',  $PAGE->action );
        
        if (isset($PAGE->message)){/* Systemnachrichten */
            $TEMPLATE->assign('page_message_count', count($PAGE->message));
            $TEMPLATE->assign('page_message',       $PAGE->message);
        } 
    } else { throw new CurriculumException($PAGE->action .'.php nicht vorhanden.'); }
 } catch (CurriculumException $e){ // CurriculumException im controller
        $TEMPLATE->assign('prev_page_name',         $PAGE->action);  
        $TEMPLATE->assign('curriculum_exception',   $e);  
        $PAGE->action = 'error';      
} 
//object_to_array($USER);
//object_to_array($CFG);
/**
 *  load and render template
 */
try {   
    $TEMPLATE->display((isset($TEMPLATE_prefix) ? $TEMPLATE_prefix : '').$PAGE->action .'.tpl');
} catch (CurriculumException $e){   // wenn CurriculumException erst im Template geworfen wird.
        echo '<p>'.$e.'</p>';       // Ausgabe des Fehlers
} catch (SmartyException $e) {    
    $TEMPLATE->display($CFG->smarty_template_dir.'error-404.tpl');
} catch (Exception $e) {
    $TEMPLATE->display($CFG->smarty_template_dir.'error-500.tpl');
} 
  