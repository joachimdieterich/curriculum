<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_bulletinBoard.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.28 11:14
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER;
$USER   = $_SESSION['USER'];
$func   = $_GET['func'];

$error  =   null;
$object = file_get_contents("php://input");
$data   = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case "edit":    checkCapabilities('dashboard:editBulletinBoard', $USER->role_id);
                        $header            = 'Pinnwand ändern';
                        $bulletinBoard     = new Institution();
                        $bulletinBoard->id = $USER->institution_id;
                        $bb                = $bulletinBoard->getBulletinBoard();
                        foreach ($bb as $key => $value){
                            if (!is_object($value)){
                                $$key = $value;
                            }
                        }
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
        }
    }
}

$content ='<form id="form_bulletinBoard"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_bulletinBoard.php"';

if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content.= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
/* Only edit bulletinboard of current institution todo, load bulletinboard on input_select*/
//$html .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$content.= Form::input_text('title', 'Überschrift', $title, $error,'z.B. Ankündigung');
$content.= Form::input_textarea('text', 'Pinnwand-Text', $text, $error);
$content.= '</form>';
$footer  = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_bulletinBoard\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';
  
$html    = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html'=>$html));