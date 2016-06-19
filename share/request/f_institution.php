<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename form_institution.php
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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $INSTITUTION;
$USER           = $_SESSION['USER'];
$INSTITUTION    = $_SESSION['INSTITUTION'];
$func           = $_GET['func'];
$cur            = new Curriculum();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id                     =   null; 
$institution            =   null; 
$description            =   null; 
$schooltype_id          =   null; 
$btn_newSchooltype      =   null;
$new_schooltype         =   null;
$schooltype_description =   null; 
$country_id             =   null;
$state_id               =   null;
$file_id                =   $CFG->standard_ins_logo_id; 
$paginator_limit        =   null; 
$std_role               =   null; 
$csv_size               =   null; 
$avatar_size            =   null; 
$acc_days               =   null; 
$timeout                =   null; 
$semester_id            =   null; 
$error                  =   null;
$object                 = file_get_contents("php://input");
$data                   = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($_GET['func'])){
    switch ($_GET['func']) {
        case "new":     checkCapabilities('institution:add',    $USER->role_id);
                        $header = 'Institution hinzufügen';
                        if (!isset($country_id)){ 
                            $country_id = $INSTITUTION->country_id;
                            $state_id   = $INSTITUTION->state_id;         
                        }
                        $add = true;              
            break;
        case "edit":    checkCapabilities('institution:update',    $USER->role_id);
                        $header     = 'Institution bearbeiten';
                        $edit       = true; 
                        $ins        = new Institution();
                        $ins->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $ins->load();
                        
                        foreach ($ins as $key => $value){
                             $$key = $value;
                             //error_log($key. ': '.$value);
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

$content ='<form id="form_institution" class="form-horizontal" role="form" method="post" action="../share/processors/fp_institution.php"';

if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><h4>Institution</h4>
<input type="hidden" name="func" id="func" value="'.$func.'"/>
<input id="id" name="id" type="text" class="invisible" ';
if (isset($id)) { $content .= 'value="'.$id.'"';} $content .= '>';

$content .= Form::input_text('institution', 'Institution / Schule', $institution, $error, 'z. B. Realschule Plus Landau');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');

/* schooltypes */ 
$sch     = new Schooltype();
$content .= Form::input_select('schooltype_id', 'Schulart', $sch->getSchooltypes(), 'schooltype', 'id', $schooltype_id , $error);


/* add new schooltype*/ 
$content .= Form::input_checkbox('btn_newSchooltype', 'Neuen Schultyp anlegen', $btn_newSchooltype, $error, 'checkbox', 'toggle([\'newSchooltype\'], [\'schooltype_id\']);');
$content .= '<div id="newSchooltype" ';
if (!isset($new_schooltype) AND !isset($schooltype_description)){ $content .= 'class="hidden"';} // only hide if no Data is given
$content .= '>';
$content .= Form::input_text('newSchool', 'Neue Schulart', $new_schooltype, $error, 'z. B. Medienzentrum Landau');
$content .= Form::input_text('schooltype_description', 'Beschreibung', $schooltype_description, $error, 'Beschreibung der neuen Schulart');
$content .= '</div>';

$countries = new State($country_id);                                                   //Load country   
$content .= Form::input_select('state_id', 'Bundesland/Region', $countries->getStates(), 'state', 'id', $state_id , $error);
$content .= Form::input_select('country_id', 'Land', $countries->getCountries(), 'de', 'id', $country_id , $error, 'getStates(this.value, \'state_id\');');
   
/* Schulbild */ 
if (isset($id)) { // id have to be set to add image
$content .= '<input type="hidden" name="file_id" id="file_id" value="'.$file_id.'"/><div class="col-xs-3"></div><div class="col-xs-9">'
            . '<a href="'.$CFG->request_url .'uploadframe.php?&context=institution&target=file_id&ref_id='.$id.'&format=0&multiple=false" class="nyroModal">'
            . '<img id="icon" style="height:100px; margin-left: -5px; padding-bottom:10px;" src="'.$CFG->access_id_url.$file_id.'" alt="Foto der Institution">'
            . '</a></div>';
}
$content .= '<h4>Einstellungen</h4>';
$rol         = new Roles(); 
$content .= Form::input_select('std_role', 'Rolle', $rol->get(), 'role', 'id', $std_role , $error);
if ($semester_id){
    $sem   =  new Semester();
    $content .= Form::input_select('semester_id', 'Semester', $sem->getSemesters(), 'semester', 'id', $semester_id , $error);
}
$content .= Form::input_text('paginator_limit', 'Listeneinträge / Seite', $paginator_limit, $error, '30','number',5,150);
$content .= Form::input_text('acc_days', 'Lernerfolge x Tage anzeigen', $acc_days, $error, '7','number',1,356);
$content .= Form::input_text('timeout', 'Timeout (Minuten)', $acc_days, $error, '15','number',1,240);
$content .= Form::input_text('csv_size', 'CSV-Dateien (byte)', $csv_size, $error, '30','number',5000,1048576);
$content .= Form::input_text('avatar_size', 'Profilfotos (byte)', $csv_size, $error, '30','number',5000,1048576);
$content .= Form::input_text('material_size', 'Dateien (byte)', $csv_size, $error, '30','number',5000,1048576);
$content .= '</div></form>';
$f_content = '';
if (isset($edit)){
    $f_content .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_institution\').submit();"> Institution aktualisieren</button>'; 
} 
if (isset($add)){
    $f_content .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_institution\').submit();"> Institution hinzufügen</button> ';
}    

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));

$script = '<script id=\'modal_script\'>
        $(function() {
            $(\'.nyroModal\').nyroModal();
            $(\'#popup_generate\').nyroModal();
        });
        $(\'#file_id\').change(\'input\', function() {
            document.getElementById("icon").src = "'.$CFG->access_id_url.'"+document.getElementById("file_id").value;
        });
        </script>';
echo json_encode(array('html'=>$html, 'script' => $script));