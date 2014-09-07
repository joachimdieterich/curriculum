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
try { // Error handling
    
    require_once('../share/setup.php');
    require_once('../share/function.php');      //php-Funktionen implementieren

    global $CFG, $PAGE, $TEMPLATE, $LOG, $USER;

    /**
    * Create LOG Object 
    */
    $LOG = new Log();

    /**
    * Setup PAGE 
    */
    $PAGE = new stdClass();

    /**
    *  get PAGE parameters 
    */
    $PAGE->controller = (isset($_GET['controller']) && trim($_GET['controller'] != '')  ? $_GET['controller'] : 'default');
    $PAGE->action     = (isset($_GET['action']) && trim($_GET['action'] != '')          ? $_GET['action'] : 'login');
    $PAGE->curriculum = (isset($_GET['curriculum']) && trim($_GET['curriculum'] != '')  ? $_GET['curriculum'] : 'none');
    $PAGE->login      = (isset($_GET['login']) && trim($_GET['login'] != '')            ? $_GET['login'] : 'none');


    if ($PAGE->action  != 'login' OR $PAGE->action  != 'register' OR $PAGE->action  != 'install') {
        //check ob eingeloggt oder timeout --> muss ganz oben stehen bleiben
        if($PAGE->action  != 'register' AND $PAGE->action  != 'install') {
            if (isset($_SESSION['username'])) {
                include ('../share/session.php');       // first build session, then do the login-check!
                include ('../share/login-check.php');   //checkt ob man eingeloggt ist
                    if ($_SESSION['authenticated']){   
                    $TEMPLATE->assign('loginname', $_SESSION['username']);
                    $TEMPLATE->assign('stat_users_Online', $USER->usersOnline($USER->institutions));
                    $TEMPLATE->assign('mySemester', $_SESSION['SEMESTER']);
                    //object_to_array($_SESSION['SEMESTER']);
                        if(isset($_POST['mySemester'])){
                            $USER->semester = $_POST['mySemester'];
                            $TEMPLATE->assign('my_semester', $USER->semester);
                            $PAGE->action = 'dashboard';   
                        }
                    }
                } else {
                    $PAGE->message[] = 'Sie sind nicht angemeldet.';
                    $PAGE->action  = 'login';
                }   
        } else {    //register and install
            $TEMPLATE->assign('loginname', '');
        }  
    }

    /**
    * avoid double requests
    */
    detect_reload();  

    /**
    * load controller 
    */ 
    $PAGE->controller = $CFG->controllers_root.'/'.$PAGE->action .'.php';
    if (file_exists($PAGE->controller)) { //Curriculum Exception Check
    require_once($PAGE->controller);  
    } else {
        throw new CurriculumException($PAGE->action .'.php nicht vorhanden.');
    }
} catch (CurriculumException $e){
        $TEMPLATE->assign('prev_page_name', $PAGE->action);  
        $TEMPLATE->assign('curriculum_exception', $e);  
        $PAGE->action = 'error';   
        
}

/**
 *  Message
 */
if (isset($PAGE->message)){
    $TEMPLATE->assign('page_message_count', count($PAGE->message));
    $TEMPLATE->assign('page_message', $PAGE->message);
} else {
    $TEMPLATE->assign('page_message', null);
}

/**
 * debug mode   
 */
if ($CFG->debug){
    $TEMPLATE->assign('debug', true);               // not used yet
}

/**
 * assign TEMPLATE variables 
 */
$TEMPLATE->assign('page_name',  $PAGE->action );

/**
 *  load and render template
 */
try {   
    $TEMPLATE->display((isset($TEMPLATE_prefix) ? $TEMPLATE_prefix : '').$PAGE->action .'.tpl');
} catch (SmartyException $e) {    
    $TEMPLATE->display($CFG->smarty_template_dir.'error-404.tpl');
} catch (Exception $e) {
    $TEMPLATE->display($CFG->smarty_template_dir.'error-500.tpl');
}
?>