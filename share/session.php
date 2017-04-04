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

/**
 * Setup global $USER 
 */
global $CFG, $USER, $_SESSION, $PAGE, $INSTITUTION, $TEMPLATE;
$USER = new User();

if (isset($_SESSION['USER'])){                                                  // Wenn $USER Object uin Session existiert diesen übernehmen
    assign_to_template($_SESSION['USER'],'my_');                                // $_SESSION['USER'] im $TEMPLATE verfügbar machen
    $USER                       = $_SESSION['USER'];
    $institution                = new Institution();
    $timeout                    = $institution->getTimeout($USER->institution_id);
    if ($timeout){
        $CFG->timeout = $timeout;
    }
    $TEMPLATE->assign('global_timeout', $CFG->timeout);
} else {                                                                        // ... anderenfalls $USER aus db laden
    $_SESSION['USER']           =  new User();  
    session_reload_user();
 }
/**
 * reload user --> moved to function.php
 */

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
        if (isset($_SESSION['lock'])){
            if ($_SESSION['lock']  != true){                                    // check if session is locked
                $PAGE->action       = filter_input(INPUT_GET, 'action'); 
            }                    
        } else {
            $PAGE->action       = filter_input(INPUT_GET, 'action');
        }
        
    }
    $PAGE->target_url           = null;   //reset target_url  
    if (!isset($_SESSION['PAGE']->message)){                                    // to get messages from popups
        $PAGE->message          = null;                                         //reset page messages
    } 
    $_SESSION['PAGE']           = new stdClass();
    $_SESSION['PAGE']           =& $PAGE;
    assign_to_template($_SESSION['PAGE'],'page_');                              // assign $_SESSION['PAGE'] to $TEMPLATE 
    $_SESSION['PAGE']->message  = null;
} else {
    $PAGE->previous_url         = 'null'; 
    $PAGE->url                  = curPageURL();
    $PAGE->previous_php         = 'null'; 
    $PAGE->php                  = curPageName();
    $PAGE->previous_action      = 'null'; 
    $PAGE->action               = filter_input(INPUT_GET, 'action');
    $PAGE->browser              = $_SERVER['HTTP_USER_AGENT'];   //$_SERVER nicht filtern --> http://php.net/manual/de/function.filter-input.php#77307
    
    $_SESSION['PAGE']           =  new stdClass();
    $_SESSION['PAGE']           =& $PAGE;
    assign_to_template($_SESSION['PAGE'],'page_');                              // assign $_SESSION['PAGE'] to $TEMPLATE 
}

if (null !== (filter_input(INPUT_GET, 'course'))){
    $COURSE                     = new stdClass();
    list ($curriculum_id, $group_id) = explode('_', filter_input(INPUT_GET, 'course'));
    $course                     = new Course();
    $course                     = $course->getCourseId($curriculum_id, $group_id);
    $_SESSION['COURSE']         =& $course;
} else {
    if (isset($_SESSION['COURSE'])){
    $COURSE                     = $_SESSION['COURSE'];
    }
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
    
    $_SESSION['INSTITUTION']    =  new Institution();                           // Store $INSTITUTION in Session
    $_SESSION['INSTITUTION']    =& $INSTITUTION;
    assign_to_template($_SESSION['INSTITUTION'],'institution_');                // assign $_SESSION['INSTITUTION'] to $TEMPLATE 
}