<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_schooltype.php
* @copyright 2019 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.01.07 16:28
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
global $CFG, $USER, $INSTITUTION;
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$cur            = new Curriculum();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id                     = null; 
$schooltype             = null; 
$description            = null; 
$error                  = null;
$object                 = file_get_contents("php://input");
$data                   = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('schooltype:add',    $USER->role_id, false, true);
                        $header = 'Schul-/Institutionstyp hinzufügen';
                        if (!isset($country_id)){ 
                            $country_id = $USER->institution->country_id;
                            $state_id   = $USER->institution->state_id;         
                        }              
            break;
        case "edit":    checkCapabilities('schooltype:update',    $USER->role_id, false, true);
                        $header     = 'Schul-/Institutionstyp aktualisieren';
                        $sc        = new Schooltype();
                        $sc->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $sc->load();
                        foreach ($sc as $key => $value){
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

$content ='<form id="form_schooltype" class="form-horizontal" role="form" method="post" action="../share/processors/fp_schooltype.php">
           <input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= Form::input_text('schooltype', 'Schul-/Institutionstyp', $schooltype, $error, 'z. B. Medienzentrum Landau');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$countries = new State($country_id);                                                   //Load country   
$states    = $countries->getStates();
$content .= Form::input_select('country_id', 'Land', $countries->getCountries(), 'de', 'id', $country_id , $error, 'getValues(\'state\', this.value, \'state_id\');');
$content .= Form::input_select('state_id', 'Bundesland/Region', $states, 'state', 'id', $state_id , $error);
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_schooltype\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';
  

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));
echo json_encode(array('html'=>$html));
