<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_schooltype.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.01.07 16:39
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
include_once(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
if (!isset($_POST['state'])){ $_POST['state'] = 1; }
$gump            = new Gump();    /* Validation */
$_POST           = $gump->sanitize($_POST);       //sanitize $_POST
// todo alle Regeln definieren
$gump->validation_rules(array(
'schooltype'    => 'required'  
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'schootype';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    $schootype = new Schooltype(); 
    if (isset($_POST['id'])){
        $schootype->id              = filter_input(INPUT_POST, 'id',    FILTER_VALIDATE_INT);
    }
    $schootype->schooltype    = filter_input(INPUT_POST, 'schooltype',  FILTER_UNSAFE_RAW);
    $schootype->description   = filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW);
    $schootype->country_id    = filter_input(INPUT_POST, 'country_id',  FILTER_VALIDATE_INT);
    $schootype->state_id      = filter_input(INPUT_POST, 'state_id',    FILTER_VALIDATE_INT);
    
    switch ($_POST['func']) {
        case 'new':    
                        if ($schootype->add()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Schul-/Institutionstyp hinzufgefügt', 'icon' => 'fa-list-alt text-success');
                            session_reload_user(); //reload session to get changes to current session (my enrolments)
                        }     
                        SmartyPaginate::setTotal(SmartyPaginate::getTotal('schooltypeP')+1, 'schooltypeP');
                        $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('schooltypeP'); //jump to new entry in list
            
            break;
        case 'edit':    if ($schootype->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Schul-/Institutionstyp erfolgreich aktualisiert', 'icon' => 'fa-list-alt text-success');
                        }
            break;

        default:
            break;
    }
    
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);