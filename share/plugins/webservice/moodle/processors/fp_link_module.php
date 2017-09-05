<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package plugin
* @filename fp_link_module.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.06.09 1028
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
$base_url  = '../../../../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include($base_url.'login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}

$gump                           = new Gump();    /* Validation */
$_POST                          = $gump->sanitize($_POST);       //sanitize $_POST
$context_id                     = $_SESSION['CONTEXT'][$_POST['func']]->id;
$reference_id                   = $_POST['reference_id'];
$config_data                    = new stdClass();
$config_data->moodle_course_id  = $_POST['moodle_course_id'];
$config_data->moodle_module_id  = $_POST['moodle_module_id'];
$config_data->moodle_percent    = $_POST['moodle_percent'];
        
$gump->validation_rules(array(
'func'          => 'required',
'reference_id'  => 'required',
'moodle_course_id'  => 'required',
'moodle_module_id'  => 'required',
'moodle_percent'  => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']                   = new stdClass();
    $_SESSION['FORM']->form             = 'ws_link_module';
    
    $_SESSION['FORM']->context_id       = $context_id;
    $_SESSION['FORM']->reference_id     = $reference_id;
    $_SESSION['FORM']->moodle_course_id = $_POST['moodle_course_id'];
    $_SESSION['FORM']->moodle_module_id = $_POST['moodle_module_id'];
    $_SESSION['FORM']->moodle_percent   = $_POST['moodle_percent'];
    
    $_SESSION['FORM']->error            = $gump->get_readable_errors();
    $_SESSION['FORM']->func             = $_POST['func'];
} else {
    //if ($_POST['func'] == 'edit'){
        
        $ws  = get_plugin('webservice', $CFG->settings->webservice);
        $ws->link_module($context_id, $reference_id, json_encode($config_data));
        $_SESSION['PAGE']->message[] = array('message' => 'Moodle-Aktivität verknüpft', 'icon' => 'fa-external-link-square text-success');
    /*}  else {
        $certificate->id = $certificate->add(); 
        SmartyPaginate::setTotal(SmartyPaginate::getTotal('certificateP')+1, 'certificateP');
        $_SESSION['PAGE']->target_url = SmartyPaginate::getLastPageIndexURL('certificateP'); //jump to new entry in list
        $_SESSION['PAGE']->message[] = array('message' => 'Zertifikat hinzugefügt', 'icon' => 'fa-files-o text-success');
    }*/
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);