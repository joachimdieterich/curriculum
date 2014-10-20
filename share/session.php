<?php
/**
 *  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename session.php 
* @copyright  2013 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2013.03.08 13:26
* @license:  
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

/**
 * Setup global $USER 
 */
global $USER, $_SESSION, $TEMPLATE;  
$USER = new User();
if (isset($_SESSION['USER'])){                                                      //Get $USER Object if Session exists - for less DB traffic
   /** assign $_SESSION['USER'] to $TEMPLATE */
    foreach($_SESSION['USER'] as $key => $value){
    $TEMPLATE->assign('my_'.$key, $value);                      /**$TEMPLATE->assign('my_username',  $_SESSION['USER']->username);*/
    }       
    /** Get $USER from Session */
    $USER               =  $_SESSION['USER'];
    
    
} else {
    $USER->load('username', $_SESSION['username'],true);                               //Get $USER Object //Über DB funktion realisieren
    foreach($USER as $key => $value){
    $TEMPLATE->assign('my_'.$key, $value);
    }
    
     /** Store $USER and other Data in Session */
    $_SESSION['USER'] =  new stdClass();
    $_SESSION['USER'] =& $USER;
    
    $semester = new Semester();
    $_SESSION['SEMESTER'] = $semester->getMySemesters($USER->id);

 }
/**
 * $USER session functions 
 */
function session_reload_user(){
    global $USER, $TEMPLATE;
    
    $USER->load('username', $_SESSION['username'], true);
    $_SESSION['USER'] =& $USER;
    //$USER->load('username', $_SESSION['username'], true);                               //Get $USER Object //Über DB funktion realisieren
    
    foreach($USER as $key => $value){
    $TEMPLATE->assign('my_'.$key, $value);
    }
}
/**
 * Setup global $PAGE  
 */
global $PAGE;
/**
 * $PAGE = new stdClass() in index.php
 */
if (isset($_SESSION['PAGE'])){   
    $PAGE               =  $_SESSION['PAGE'];
    if ($PAGE->url != curPageURL()){                            //previous_url represents the last url
        $PAGE->previous_url = $PAGE->url;
        $PAGE->url          = curPageURL();
    }
    if ($PAGE->php != curPageName()){
        $PAGE->previous_php = $PAGE->php;
        $PAGE->php          = curPageName();
    }
    if ($PAGE->action != (isset($_GET['action']) && trim($_GET['action'] != '') ? $_GET['action'] : 'login')){                        //previous action represents the action parameter of the last url
        $PAGE->previous_action = $PAGE->action;
        $PAGE->action          = (isset($_GET['action']) && trim($_GET['action'] != '') ? $_GET['action'] : 'login');
    }
    $PAGE->message = null;
    $_SESSION['PAGE']   =& $PAGE;
    foreach($_SESSION['PAGE'] as $key => $value){
    $TEMPLATE->assign('page_'.$key, $value);                      /**$TEMPLATE->assign('my_username',  $_SESSION['USER']->username);*/
    }
} else {
    $PAGE->previous_url = 'null'; 
    $PAGE->url = curPageURL();
    $PAGE->previous_php = 'null'; 
    $PAGE->php = curPageName();
    $PAGE->previous_action = 'null'; 
    $PAGE->action = (isset($_GET['action']) && trim($_GET['action'] != '') ? $_GET['action'] : 'login');;
    $PAGE->browser = getagent();
    
    $_SESSION['PAGE'] =  new stdClass();
    $_SESSION['PAGE'] =& $PAGE;
}



/**
 * Setup global $INSTITUTION and the config data for this institution
 */
global $INSTITUTION;  
$INSTITUTION = new stdClass();
if (isset($_SESSION['INSTITUTION'])){
    $INSTITUTION             =  $_SESSION['INSTITUTION'];
    $_SESSION['INSTITUTION'] =& $INSTITUTION;
} else { 
    $institution = new Institution(); 
    $institution->loadConfig('user', $USER->id);
    
    /** Store $INSTITUTION in Session */
    $_SESSION['INSTITUTION'] =  new stdClass();
    $_SESSION['INSTITUTION'] =& $INSTITUTION;
}
/*
* SETUP CURRICULUM
*/
//$CURRICULUM = new stdClass();


/*
 * Print SESSION for debug
 */
//print_r($_SESSION);
?>