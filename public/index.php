<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename index.php
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
include('../share/setup.php');
global $CFG, $PAGE, $TEMPLATE, $USER;

try { // Error handling
    $PAGE           = new stdClass();
    $PAGE->action   = filter_input(INPUT_GET, 'action', FILTER_UNSAFE_RAW);
    if (!$PAGE->action) { $PAGE->action = 'login'; $_SESSION['lock'] = false;}
    switch ($PAGE->action) {                                  
        case 'login':  $TEMPLATE->assign('page_action',      'login');                                      
        //case 'install':  
            break;

        default:   require ('../share/session.php');                                                           // Erst Session aufbauen damit $USER verfügbar ist, dann login-check!
                   require ('../share/login-check.php');                                                       // Check ob Session abgelaufen ist

                   $TEMPLATE->assign('mySemester',         $_SESSION['SEMESTER']);                                     // ARRAY mit Lernzeiträumen in die der USER eingeschrieben ist.
                   if (isset($_SESSION['username'])){
                       $TEMPLATE->assign('loginname',      $_SESSION['username']);                                      
                   }
                   $TEMPLATE->assign('stat_users_online',  $USER->usersOnline($USER->institutions));  
                   $statistics = new Statistic();
                   $TEMPLATE->assign('stat_acc_all',       $statistics->getAccomplishedObjectives('all'));  
                   $TEMPLATE->assign('stat_acc_today',     $statistics->getAccomplishedObjectives('today'));  
                   $TEMPLATE->assign('stat_users_today',   $statistics->getUsersOnline('today'));  
                   detect_reload();   
            break;
    }

    /** highlight */
    if (isset($_SESSION['highlight'])){
        $TEMPLATE->assign('highlight', $_SESSION['highlight']);
    } 
    
    /**Load new Messages */
    if (isset($USER)){
        if (checkCapabilities('menu:readMessages', $USER->role_id, false)){
            $mail = new Mailbox();
            $mail->loadNewMessages($USER->id);
            if (isset($mail->inbox)){
                $TEMPLATE->assign('mails', $mail->inbox);
                /* Load recent Mails for Sidebar */
                /*$recent_mails = new Mail();   
                $TEMPLATE->assign('recent_mails', $recent_mails->loadCorrespondence(5, $USER->id, $USER->id, 'recent')); */
            }    
        }   
        
        if (isset($_SESSION['PAGE']->print)){
            $pdf = new Pdf();
            $pdf->content = $_SESSION['PAGE']->print->content;
            $pdf->filename = 'print.pdf';
            $pdf->generate(); 
            unset($_SESSION['PAGE']->print);
        }
        
    }

    $PAGE->controller = $CFG->controllers_root.'/'.$PAGE->action .'.php';       //load controller 
   
    if (file_exists($PAGE->controller)) {   
        include($PAGE->controller);  
        $TEMPLATE->assign('page_name',  $PAGE->action );
        if (isset($PAGE->message)){/* Systemnachrichten */
            $TEMPLATE->assign('page_message_count', count($PAGE->message));
            $TEMPLATE->assign('page_message',       $PAGE->message);
            if ($PAGE->action == 'login'){
                $_SESSION['PAGE'] = new stdClass();
                $_SESSION['PAGE']->message = null;  //reset to prevent multiple notifications
            }
        } 
    } else {  
        $TEMPLATE->assign('page_name',         $PAGE->action);  
        $PAGE->action = 'error-404';
        throw new CurriculumException($PAGE->action .'.php nicht vorhanden.'); 
    }
    
 } catch (CurriculumException $e){ // CurriculumException im controller
    if ($PAGE->action != 'error-404'){
        $TEMPLATE->assign('page_name',         $PAGE->action);  
        $PAGE->action = 'error-403';
    }
    $TEMPLATE->assign('curriculum_exception',   $e);  
} 

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