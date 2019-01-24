<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_backup.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.05.27 17:44
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
global $CFG, $USER;
$USER           = $_SESSION['USER'];


/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$cert_id           = null;
$certificate       = null; 
$description       = null;
$institution_id    = null;
$template          = null;

$func              = $_GET['func'];
$error             =   null;
$object            = file_get_contents("php://input");
$data              = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case "new": checkCapabilities('backup:add',    $USER->role_id, false, true);
                    $header             = 'Backup erstellen';
                    /* load backups and courses */
                    $courses            = new Course(); //load Courses
                    if (checkCapabilities('backup:getAllBackups', $USER->role_id, false, true)) {                          // Administrators
                        $options        = $courses->getCourse('admin', $USER->id);
                    } else if (checkCapabilities('backup:getMyBackups', $USER->role_id, false, true)) {                    // Teacher and Tutor
                        $options        = $courses->getCourse('teacher', $USER->id);
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
   
$content  = '<form id="form_backup"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_backup.php">
             <input type="hidden" name="func" id="func" value="'.$func.'"/>';
$content .= Form::input_select('curriculum_id', 'Lehrplan', $options, 'course', 'curriculum_id', null , $error);
/* Format selector*/
$format_array = array('XML curriculum' => 'xml',
                'XML RLP Digitale Lehrpläne' => 'xml-rlp',
                'XML edu-sharing Sammlungen' => 'xml-edu-sharing',
                'XML edu-sharing Metadatenset' => 'xml-edu-sharing-metadata',
                'IMSCC Moodle' => 'imscc'
               );
$format_obj = new stdClass();
foreach ($format_array as $key => $value) {
    $format_obj->name  = $key;
    $format_obj->format = $value;
    $f_list[] = clone $format_obj;
}
$content     .= Form::input_select('export_format', 'Export-Format', $f_list, 'name', 'format', null , $error);
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_backup\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';  
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));

echo json_encode(array('html'=>$html));