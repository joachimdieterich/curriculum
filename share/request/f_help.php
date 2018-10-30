<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_help.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.04.06 09:08
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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $TEMPLATE;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id             = null; 
$title          = null; 
$description    = null; 
$category       = null;
$object         = file_get_contents("php://input");
$data           = json_decode($object, true);
$file_id        = $CFG->settings->standard_avatar_id;
$error          = null;
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('help:add',    $USER->role_id, false, true);
                        $header = 'Hilfe-Datei hinzufügen';            
            break;
        case "edit":    checkCapabilities('help:update',    $USER->role_id, false, true);
                        $header   = 'Hilfe-Datei bearbeiten';
                        $edit     = true; 
                        $h        = new Help(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                        foreach ($h as $key => $value){
                             $$key = $value;
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

$content ='<form id="form_help" class="form-horizontal" role="form" method="post" action="../share/processors/fp_help.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= Form::input_text('title', 'Titel', $title, $error, 'z.B. Anleitung Benutzerverwaltung');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_text('category', 'Kategorie', $category, $error, 'z.B. Tutorial');

$content .= '<input type="hidden" name="file_id" id="file_id" value="'.$file_id.'"/>';
 // id have to be set to add image
$content .= '<div class="col-xs-3"></div><div class="col-xs-9">'
            . '<a href="'.$CFG->smarty_template_dir_url.'renderer/uploadframe.php?context=userFiles&target=file_id&ref_id='.$id.'&format=0&modal=true" class="nyroModal">
               <img id="icon" class="hidden" style="height:100px; margin-left: -5px; padding-bottom:10px;"';
               if (isset($id)) { 
                   $content .= 'src="'.$CFG->access_id_url.$file_id.'" ';
               }
                   $content .= '>';
               if (!isset($id)) {
                   $content .= '<span id="add_btn" ><i class="fa fa-plus"></i> Datei hinzufügen</span>';
               }
$content .= '</a></div></form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_help\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>'; 

$html     = Form::modal(array('target'    => 'null',
                              'title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

$script = '<script id=\'modal_script\'>
        $(function() {
            $(\'.nyroModal\').nyroModal({
            callbacks: {
                beforeShowBg: function(){
                    $(\'body\').css(\'overflow\', \'hidden\');
                       
                },
                afterHideBg: function(){
                    $(\'body\').css(\'overflow\', \'\');
                 
                },
                afterShowCont: function(nm) {
                    $(\'.scroll_list\').height($(\'.modal\').height()-150);
                }   
            }
        });
            $(\'#popup_generate\').nyroModal();
        });
        $(\'#file_id\').change(\'input\', function() {
            document.getElementById("icon").src = "'.$CFG->access_id_url.'"+document.getElementById("file_id").value;
            $(\'#icon\').removeClass("hidden");
            $(\'#add_btn\').addClass("hidden");
        });
        </script>';
echo json_encode(array('html' => $html, 'script' => $script));