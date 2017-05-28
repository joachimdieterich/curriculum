<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_certificate.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.05.26 12:50
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
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$selected_user_id       = SmartyPaginate::_getSelection('userPaginator');
if ($_POST['certificate_id'] != '-1' AND $selected_user_id != '' AND isset($selected_user_id[0])){
    $pdf = new Pdf();
    $pdf->user_id       = $selected_user_id; 
    $pdf->curriculum_id = $_POST['curriculum_id'];
    $certificate        = new Certificate();
    $certificate->id    = $_POST['certificate_id'];
    $certificate->load();
    $pdf->template      = $certificate->template;
    
    
    if (isset($_POST['deliver'])){                      // save template to users folder
        $pdf->generate_certificate_from_template($_POST['deliver']);
    }  else {
        $pdf->generate_certificate_from_template();
    }
} 
$_SESSION['FORM']            = new stdClass();
$_SESSION['FORM']->form      = 'generate_certificate';
$_SESSION['FORM']            = null;                     // reset Session Form object
header('Location:'.$_SESSION['PAGE']->target_url);