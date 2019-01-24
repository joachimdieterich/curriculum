<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_curriculum_owner.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.04.29 18:28
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
global $CFG, $USER;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
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
        case 'set':     checkCapabilities('curriculum:update',    $USER->role_id, false, true);
                        $curriculum_id       = filter_input(INPUT_GET, 'id',    FILTER_UNSAFE_RAW);
                        $header = 'Lehrplanbesitzer ändern';
        break;
            
        default : break;
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

$cur     = new Curriculum();
$cur->id = $curriculum_id;
$cur->load();

$content = '<form id="form_curriculum_owner"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_curriculum_owner.php">
            <input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$curriculum_id.'"/>
            <input type="hidden" name="func" id="func" value="'.$func.'"/>
            <div>'. Form::input_select('owner_id', 'Neuer Besitzer', $USER->getGroupMembers(), 'firstname, lastname', 'id', $cur->creator_id , $error) .'
                 <input id="set_owner" name="set_owner" type="submit" class="hidden"/>
            </div>
            </form>';
$footer   = '<button id="set_owner_btn" type="submit" class="btn btn-primary pull-right" onclick="$(\'#set_owner\').click();" ><i class="fa fa-save margin-r-5"></i>'.$header.'</button> ';
              
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

echo json_encode(array('html'=>$html));