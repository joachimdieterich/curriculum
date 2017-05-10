<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_grade.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.28 21:33
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
$grade                 = new Grade();
$gump                  = new Gump();    /* Validation */
$_POST                 = $gump->sanitize($_POST);       //sanitize $_POST
$grade->id             = $_POST['grade_id']; 
$grade->grade          = $_POST['grade']; 
$grade->description    = $_POST['description'];  
$grade->institution_id = $_POST['institution_id']; 

// todo alle Regeln definieren
$gump->validation_rules(array(
'grade'          => 'required',
'description'    => 'required',    
'institution_id' => 'required'    
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'grade';
    foreach($grade as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    switch ($_POST['func']) {
        case 'new':      if ($grade->add()){
                            $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('gradeP'); //jump to new entry in list
                            $_SESSION['PAGE']->message[] = array('message' => 'Klassenstufe hinzufgefügt', 'icon' => 'fa-signal text-success');
                         }               
            
            break;
        case 'edit':     if ($grade->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Klassenstufe erfolgreich aktualisiert', 'icon' => 'fa-signal text-success');
                         }
            break;
       
        default:
            break;
    }

    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);