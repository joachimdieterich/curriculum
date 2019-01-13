<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_comment.php
* @copyright 2019 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.01.11 09:24
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
require ($base_url.'login-check.php');  
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$cm             = new Comment();
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {      
        case 'accomplished':
                    $header             = 'Lernstand kommentieren';
                    checkCapabilities('comment:add',         $USER->role_id, false, true);
                    $us                 = new User();
                    $ena_id             = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $acc_id             = $us->getAccomplished($ena_id, $USER->id, $_SESSION['CONTEXT']['enabling_objective']->context_id);
            break;
        default:    
            
            break;
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
$footer  = '<button type="submit" class="btn btn-primary pull-right" onclick="closePopup()"><i class="fa fa-commenting-o margin-r-10"></i> Schließen</button>'; //Default
if (isset($acc_id->id)){
    $content = RENDER::comments(["id"=> $acc_id->id, "context"=> "accomplished"]);
} else {
    $content = 'Kommentare können erst gemacht werden, wenn ein Lernstand gesetzt wurde.';
}
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content));  

echo json_encode(array('html'=>$html));