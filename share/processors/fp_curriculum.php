<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_curriculum.php
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
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$curriculum = new Curriculum();
$purify                                 = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier                               = new HTMLPurifier($purify);
$curriculum->description                = $purifier->purify(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));

$gump            = new Gump();    /* Validation */
$_POST           = $gump->sanitize($_POST);       //sanitize $_POST

// todo alle Regeln definieren
if (isset($CFG->settings->show_subjectIcon)){
  $show_subjectIcon = $CFG->settings->show_subjectIcon;
} else {
  $show_subjectIcon =  "ALWAYS"; // possible values: ALWAYS, NEVER, SELECT
}
if ($show_subjectIcon != "NEVER") {
  $gump->validation_rules(array(
  'curriculum'     => 'required',
  'description'    => 'required',
  'subject_id'     => 'required',
  'grade_id'       => 'required',
  'schooltype_id'  => 'required',
  'state_id'       => 'required',
  'country_id'     => 'required',
  'icon_id'        => 'required',
  'color'          => 'required'
  ));
} else {
  $gump->validation_rules(array(
  'curriculum'     => 'required',
  'description'    => 'required',
  'subject_id'     => 'required',
  'grade_id'       => 'required',
  'schooltype_id'  => 'required',
  'state_id'       => 'required',
  'country_id'     => 'required',
  'color'          => 'required'
  ));
}

if (isset($_POST['id'])){
    $curriculum->id         = filter_input(INPUT_POST, 'id',             FILTER_VALIDATE_INT);
}
$curriculum->curriculum     = filter_input(INPUT_POST, 'curriculum',     FILTER_SANITIZE_STRING);
$curriculum->subject_id     = filter_input(INPUT_POST, 'subject_id',     FILTER_VALIDATE_INT);
$curriculum->grade_id       = filter_input(INPUT_POST, 'grade_id',       FILTER_VALIDATE_INT);
$curriculum->schooltype_id  = filter_input(INPUT_POST, 'schooltype_id',  FILTER_VALIDATE_INT);
$curriculum->state_id       = filter_input(INPUT_POST, 'state_id',       FILTER_VALIDATE_INT);
$curriculum->country_id     = filter_input(INPUT_POST, 'country_id',     FILTER_VALIDATE_INT);
$curriculum->icon_id        = filter_input(INPUT_POST, 'icon_id',        FILTER_VALIDATE_INT);#
$curriculum->color          = filter_input(INPUT_POST, 'color',     FILTER_SANITIZE_STRING).'AA'; //AA -> setting opacity

$validated_data  = $gump->run($_POST);
if (!isset($_POST['state'])){ $_POST['state'] = 1; }
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'curriculum'; 
    foreach($curriculum as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    switch ($_POST['func']) {
        case 'new':     if ($curriculum->add()){
                            SmartyPaginate::setTotal(SmartyPaginate::getTotal('curriculumP')+1, 'curriculumP');
                            $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('curriculumP'); //jump to new entry in list
                            $_SESSION['PAGE']->message[] = array('message' => 'Lehrplan hinzufgefügt', 'icon' => 'fa-th text-success');
                         }               
            break;
        case 'edit':     if ($curriculum->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Lehrplan erfolgreich aktualisiert', 'icon' => 'fa-th text-success');
                            session_reload_user(); // --> get the changes immediately 
                         }
            break;
        case 'import':  if (isset($_POST['importFileName'])){
                            $file = $CFG->backup_root.''. filter_input(INPUT_POST, 'importFileName', FILTER_UNSAFE_RAW);
                            if ($curriculum->import($file)){
                                SmartyPaginate::setTotal(SmartyPaginate::getTotal('curriculumP')+1, 'curriculumP');
                                $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('curriculumP'); //jump to new entry in list
                                $_SESSION['PAGE']->message[] = array('message' => 'Lehrplan erfolgreich importiert', 'icon' => 'fa-th text-success');
                            }
                        } 
            break;

        default:
            break;
    }

    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);