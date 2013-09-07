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

require_once('../share/setup.php');
require_once('../share/function.php');      //php-Funktionen implementieren

global $CFG, $PAGE, $TEMPLATE, $LOG;

/**
 * Create LOG Object 
 */
$LOG = new Log();

/**
* Setup PAGE 
*/
$PAGE = new stdClass();

/**
 *  set PAGE defaults (used when no param is given, see below)
 */
$PAGE->controller   = 'default';
$PAGE->action       = 'login';
$PAGE->curriculum   = 'none';
$PAGE->login        = 'none';

/**
 *  get PAGE parameters 
 */
$PAGE->controller = (isset($_GET['controller']) && trim($_GET['controller'] != '') ? $_GET['controller'] : $PAGE->controller);
$PAGE->action     = (isset($_GET['action']) && trim($_GET['action'] != '') ? $_GET['action'] : $PAGE->action);
$PAGE->curriculum = (isset($_GET['curriculum']) && trim($_GET['curriculum'] != '') ? $_GET['curriculum'] : $PAGE->curriculum);
$PAGE->login      = (isset($_GET['login']) && trim($_GET['login'] != '') ? $_GET['login'] : $PAGE->login);


if ($PAGE->action  != 'login' OR $PAGE->action  != 'register' OR $PAGE->action  != 'install') {
    //check ob eingeloggt oder timeout --> muss ganz oben stehen bleiben
    
    if($PAGE->action  != 'register' AND $PAGE->action  != 'install') {
        if (isset($_SESSION['username'])) {
            include ('../share/session.php');       // first build session, then do the login-check!
            include ('../share/login-check.php'); //checkt ob man eingeloggt ist
                if ($_SESSION['authenticated']) {
                $TEMPLATE->assign('loginname', $_SESSION['username']);
                //object_to_array($USER);
                }
            } else {
                $PAGE->message[] = 'Sie sind nicht angemeldet.';
                $PAGE->action  = 'login';
            }   
    } else {    //register and install
        $TEMPLATE->assign('loginname', '');
        $TEMPLATE->assign('role_id', -1);
    }  
}

           
/**
 * avoid double requests
 */
detect_reload();  

/**
 * Check if user has permission to see page 
 */
$PAGE->action  = pagePermissions($PAGE->action );   

/**
 * load controller 
 */
$PAGE->controller = $CFG->controllers_root.'/'.$PAGE->action .'.php';
if (file_exists($PAGE->controller)) {
    require_once($PAGE->controller);
}

/*
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
//$TEMPLATE->assign('curriculum', $PAGE->curriculum ); --> generates problems

/**
 *  load and render template
 */
try {   
    $TEMPLATE->display((isset($TEMPLATE_prefix) ? $TEMPLATE_prefix : '').$PAGE->action .'.tpl');
}

catch (SmartyException $e) {    
    $TEMPLATE->display($CFG->smarty_template_dir.'error-404.tpl');
}

catch (Exception $e) {
    $TEMPLATE->display($CFG->smarty_template_dir.'error-500.tpl');
}
?>