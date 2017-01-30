<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_settings.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.01.30 08.24:05
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
global $CFG, $USER,$TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$template       = null;
$error          = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":
        case "edit":    $header     = 'Einstellungen ändern';      
                        $selected_template   = $CFG->settings->template;
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

$content ='<form id="form_settings" class="form-horizontal" role="form" method="post" action="../share/processors/fp_settings.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= '<div class="nav-tabs-custom"> 
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false" ><i class="fa fa-user margin-r-10"></i>Persönliche Einstellungen</a></li>
                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true" ><i class="fa fa-university margin-r-10"></i>Einstellungen (Institution)</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">';
    $directories = glob($CFG->share_root . '/templates/*' , GLOB_ONLYDIR);
    $templates_obj = new stdClass();
    foreach ($directories as $key => $value) {
        $templates_obj->label     = $key;
        $templates_obj->template  = basename($value);
        $t[] = clone $templates_obj;
    }
    $content .= Form::input_select('template', 'Template', $t, 'template', 'template', $selected_template,  $error);
    $content .='</div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2"> under construction
                </div><!-- /.tab-pane -->
            </div>';
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_settings\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));
echo json_encode(array('html'   => $html));