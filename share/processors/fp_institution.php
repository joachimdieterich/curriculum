<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_institution.php
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
'institution'    => 'required',
'description'    => 'required'   
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'institution';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    $new_institution = new Institution(); 
    if (isset($_POST['id'])){
        $new_institution->id            = filter_input(INPUT_POST, 'id',                FILTER_VALIDATE_INT);
    }
    $new_institution->institution       = filter_input(INPUT_POST, 'institution',       FILTER_SANITIZE_STRING);
    $new_institution->description       = filter_input(INPUT_POST, 'description',       FILTER_SANITIZE_STRING);
    $new_institution->street            = filter_input(INPUT_POST, 'street',            FILTER_SANITIZE_STRING);
    $new_institution->postalcode        = filter_input(INPUT_POST, 'postalcode',        FILTER_SANITIZE_STRING);
    $new_institution->city              = filter_input(INPUT_POST, 'city',              FILTER_SANITIZE_STRING);
    $new_institution->phone             = filter_input(INPUT_POST, 'phone',             FILTER_SANITIZE_STRING);
    $new_institution->email             = filter_input(INPUT_POST, 'email',             FILTER_SANITIZE_STRING);
    $new_institution->schooltype_id     = filter_input(INPUT_POST, 'schooltype_id',     FILTER_VALIDATE_INT);
    $new_institution->country_id        = filter_input(INPUT_POST, 'country_id',        FILTER_VALIDATE_INT);
    $new_institution->state_id          = filter_input(INPUT_POST, 'state_id',          FILTER_VALIDATE_INT);
    $new_institution->confirmed         = 1;  // institution is confirmed
    $new_institution->paginator_limit   = filter_input(INPUT_POST, 'paginator_limit',   FILTER_VALIDATE_INT);
    $new_institution->std_role          = filter_input(INPUT_POST, 'std_role',          FILTER_VALIDATE_INT);
    $new_institution->csv_size          = filter_input(INPUT_POST, 'csv_size',          FILTER_VALIDATE_INT);
    $new_institution->avatar_size       = filter_input(INPUT_POST, 'avatar_size',       FILTER_VALIDATE_INT);
    $new_institution->material_size     = filter_input(INPUT_POST, 'material_size',     FILTER_VALIDATE_INT);
    $new_institution->acc_days          = filter_input(INPUT_POST, 'acc_days',          FILTER_VALIDATE_INT);
    $new_institution->timeout           = filter_input(INPUT_POST, 'timeout',           FILTER_VALIDATE_INT);
    $new_institution->semester_id       = filter_input(INPUT_POST, 'semester_id',       FILTER_VALIDATE_INT);
    $new_institution->file_id           = filter_input(INPUT_POST, 'file_id',           FILTER_VALIDATE_INT);
    $new_institution->support_user_ids  = $_POST['support_user_ids']; #FILTER_VALIDATE_RAW
    
    switch ($_POST['func']) {
        case 'new':     if (isset($_POST['btn_newSchooltype'])){ 
                            $new_schooltype = new Schooltype();
                            $new_schooltype->schooltype = filter_input(INPUT_POST, 'new_schooltype',    FILTER_SANITIZE_STRING);
                            $new_schooltype->description= filter_input(INPUT_POST, 'schooltype_description',FILTER_SANITIZE_STRING);
                            $new_schooltype->country_id = filter_input(INPUT_POST, 'country_id',           FILTER_VALIDATE_INT);
                            $new_schooltype->state_id   = filter_input(INPUT_POST, 'state_id',             FILTER_VALIDATE_INT);
                            $new_schooltype_id     = $new_schooltype->add(); 
                            
                        }
                        if (isset($new_schooltype_id)){
                            $schooltype_id = $new_schooltype_id;
                        } else {
                            $schooltype_id = filter_input(INPUT_POST, 'schooltype_id',    FILTER_VALIDATE_INT);
                        }
                        $new_institution->schooltype_id = $schooltype_id;
                        $institution_id                 = $new_institution->add();
                        $USER->enroleToInstitution($institution_id); 
                        $TEMPLATE->assign('institution_id', $institution_id);
                        if (isset($institution_id)){
                            $_SESSION['PAGE']->message[] = array('message' => 'Institution hinzufgefügt', 'icon' => 'fa-university text-success');
                            session_reload_user(); //reload session to get changes to current session (my enrolments)
                        }     
                        SmartyPaginate::setTotal(SmartyPaginate::getTotal('institutionP')+1, 'institutionP');
                        $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('institutionP'); //jump to new entry in list
            
            break;
        case 'edit':    if ($new_institution->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Institution erfolgreich aktualisiert', 'icon' => 'fa-university text-success');
                            session_reload_user(); //reload session to get changes to current session (my enrolments)
                        }
            break;

        default:
            break;
    }
    
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);