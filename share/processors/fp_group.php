<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_group.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.28 18:06
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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER           = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$group                 = new Group();
$gump                  = new Gump();    /* Validation */
$_POST                 = $gump->sanitize($_POST);       //sanitize $_POST

if (isset($_POST['group_id'])){
    $group->id         = $_POST['group_id']; 
}
$group->group          = $_POST['group']; 
$group->description    = $_POST['description']; 
$group->grade_id       = $_POST['grade_id'];  
$group->semester_id    = $_POST['semester_id']; 
$group->institution_id = $_POST['institution_id']; 
// $group->creator_id     = $USER->id; // now set in add function
if (isset($_POST['assumeUsers'])){
    $assumeUsers = true;
}


// todo alle Regeln definieren
$gump->validation_rules(array(
'group'          => 'required',
'description'    => 'required',
'grade_id'       => 'required',
'semester_id'    => 'required',    
'institution_id' => 'required',    
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'group'; 
    foreach($group as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    switch ($_POST['func']) {
        case 'new':      if ($group->add()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Gruppe hinzufgefügt', 'icon' => 'fa-group text-success');
                         }               
            
            break;
        case 'edit':     if ($group->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Gruppe erfolgreich aktualisiert', 'icon' => 'fa-group text-success');
                         }
            break;
        case 'semester': if ($group->add('semester')){ error_log('semester grid:'.$group->id);
                            if ($assumeUsers){ 
                                $group->changeSemester(); 
                            } 
                            $_SESSION['PAGE']->message[] = array('message' => 'Semester geändert', 'icon' => 'fa-calendar text-success');
                         }
            break;

        default:
            break;
    }

    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);