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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/

/**
 * Setup global $USER 
 */
global $CFG, $USER, $_SESSION, $PAGE, $INSTITUTION, $TEMPLATE;
$USER = new User();

if (isset($_SESSION['USER'])){                                                  // Wenn $USER Object uin Session existiert diesen übernehmen --> weniger db Traffic
    assign_to_template($_SESSION['USER'],'my_');                                // $_SESSION['USER'] im $TEMPLATE verfügbar machen
    $USER                   =  $_SESSION['USER'];
    $institution = new Institution();
    $CFG->timeout = $institution->getTimeout($USER->institution_id);
    $TEMPLATE->assign('global_timeout', $CFG->timeout);
} else {                                                                        // ... anderenfalls $USER aus db laden
    $_SESSION['USER']       =  new stdClass();  
    session_reload_user();
 }
/**
 * reload user
 */
function session_reload_user(){
    global $USER, $CFG, $TEMPLATE;
    
    $USER->load('username', $_SESSION['username'], true);                       // Benutzer aus DB laden
    $USER->password         = '';                                               // Passwort aus Session löschen
    $_SESSION['USER']       =& $USER;
    assign_to_template($_SESSION['USER'],'my_');                                
    
    $semester = new Semester();                                                 // akt. Semester /Lernzeitraum  laden
    $_SESSION['SEMESTER']   = $semester->getMySemesters($USER->id);             // .todo. akt. Semester der Institution laden, da sonst bei neu angelegten Benutzern semester_id evtl. nicht stimmt (wenn Benutzer in anderer Institution angelegt wurden)
    
    $institution = new Institution();   
    $CFG->timeout = $institution->getTimeout($USER->institution_id);            // Set timeout based on Institution
    $TEMPLATE->assign('global_timeout', $CFG->timeout);
}
/**
 * Setup global $PAGE  
 * $PAGE = new stdClass() in index.php
 */
if (isset($_SESSION['PAGE'])){ 
    $PAGE                       =  $_SESSION['PAGE'];
    $PAGE->curriculum           = '';                                           // HACK to deselect Menu-Item
    if ($PAGE->url             != curPageURL()){                                //previous_url represents the last url
        $PAGE->previous_url     = $PAGE->url;
        $PAGE->url              = curPageURL();
    }
    if ($PAGE->php             != curPageName()){
        $PAGE->previous_php     = $PAGE->php;
        $PAGE->php              = curPageName();
    }
    if ($PAGE->action          != filter_input(INPUT_GET, 'action')){           //previous action represents the action parameter of the last url
        $PAGE->previous_action  = $PAGE->action;
        $PAGE->action           = filter_input(INPUT_GET, 'action');
    }
    $PAGE->message = null;
    $_SESSION['PAGE']           =& $PAGE;
    assign_to_template($_SESSION['PAGE'],'page_');                              // assign $_SESSION['PAGE'] to $TEMPLATE 
} else {
    $PAGE->previous_url         = 'null'; 
    $PAGE->url                  = curPageURL();
    $PAGE->previous_php         = 'null'; 
    $PAGE->php                  = curPageName();
    $PAGE->previous_action      = 'null'; 
    $PAGE->action               = filter_input(INPUT_GET, 'action');
    $PAGE->browser              = $_SERVER['HTTP_USER_AGENT'];                  //$_SERVER nicht filtern --> http://php.net/manual/de/function.filter-input.php#77307
    
    $_SESSION['PAGE']           =  new stdClass();
    $_SESSION['PAGE']           =& $PAGE;
    assign_to_template($_SESSION['PAGE'],'page_');                              // assign $_SESSION['PAGE'] to $TEMPLATE 
}

/**
 * Setup global $INSTITUTION and the config data for this institution
 */
$INSTITUTION = new stdClass();
if (isset($_SESSION['INSTITUTION'])){
    $INSTITUTION                =  $_SESSION['INSTITUTION'];
    $_SESSION['INSTITUTION']    =& $INSTITUTION;
    assign_to_template($_SESSION['INSTITUTION'],'institution_');                // assign $_SESSION['INSTITUTION'] to $TEMPLATE 
} else { 
    $institution                = new Institution(); 
    $institution->loadConfig('user', $USER->id);
    
    $_SESSION['INSTITUTION']    =  new stdClass();                              // Store $INSTITUTION in Session
    $_SESSION['INSTITUTION']    =& $INSTITUTION;
    assign_to_template($_SESSION['INSTITUTION'],'institution_');                // assign $_SESSION['INSTITUTION'] to $TEMPLATE 
}