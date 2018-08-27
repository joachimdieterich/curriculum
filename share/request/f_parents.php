<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_parents.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.10.27 10:52
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
global $CFG, $USER, $COURSE;
$USER           = $_SESSION['USER'];
$children_id    = null;
checkCapabilities('user:parentalAuthority', $USER->role_id, false, true);
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
        case "edit":     $header            = 'Erziehungsberechtigungen bearbeiten';
                         $user              = new User();
                         $id                = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                         $user->id          = $id;
                         $user->load('id', $user->id, false);
                         $children          = $user->getChildren();      
                         $u                 = $user->userList('institution', '');
                        
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

$content ='<form id="form_parents"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_parents.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '">';
$content     .= Form::info(array('id' => 'ref_info', 'content' => '<strong>'.$user->firstname.' '.$user->lastname.' </strong>ist für die folgenden Personen erziehungsberechtigt:' ));
$content     .= '<div class="form-group">
                  <label class="control-label col-sm-3"></label>
                  <div class="col-sm-9"><div class="row">';
foreach ($children as $key => $value) {
    $content .= '<div class="col-sm-4">';
    $content .= '<div class="user-panel pull-left" >
        <div class="pull-left image" ><img src="'.$CFG->access_id_url.$value->avatar_id.'" style="height:45px;width:45px;" class="img-circle" alt="User Image"></div>
        <div class="pull-left info text-black">
          <p>'.$value->firstname.' '.$value->lastname.'</p><a data-toggle="tooltip" title="Zuweisung aufheben" onclick="processor(\'set\', \'parentalAuthority\',\''.$id.'\', {\'child_id\':\''.$value->id.'\'});"><i class="fa fa-minus text-danger"></i></a>
        </div>
      </div>';
    $content .= '</div>';
}
$content .= '</div></div></div>';
if (isset($id)){
    $content .= '<input type="hidden" name="user_id" id="user_id" value="'.$id.'"/>';    
}
$content .= '<input type="hidden" name="func" id="func" value="'.$func.'"/>';

$semesters                  = new Semester();  //Load Semesters
$semesters->institution_id  = $USER->institution_id;
$content .= Form::input_select_multiple(array('id' => 'children_id', 'label' => 'Kinder', 'select_data' => $u , 'select_label' => 'firstname, lastname', 'select_value' => 'id', 'input' => $children_id, 'error' => $error));

$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_parents\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';  
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html'=>$html));