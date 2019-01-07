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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $CFG, $USER, $INSTITUTION;
$USER           = $_SESSION['USER'];
$INSTITUTION    = $_SESSION['INSTITUTION'];
$func           = $_GET['func'];
$cur            = new Curriculum();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id                     = null; 
$institution            = null; 
$description            = null; 
$schooltype_id          = null; 
$btn_newSchooltype      = null;
$new_schooltype         = null;
$schooltype_description = null; 
$street                 = null;
$postalcode             = null;
$city                   = null;
$phone                  = null;
$email                  = null;
$country_id             = null;
$state_id               = null;
$file_id                = $CFG->settings->standard_ins_logo_id; 
$paginator_limit        = $CFG->settings->paginator_limit;
$std_role               = $CFG->settings->standard_role;
$csv_size               = $CFG->settings->csv_size;
$avatar_size            = $CFG->settings->avatar_size;
$acc_days               = $CFG->settings->acc_days;
$timeout                = $CFG->settings->timeout;
$semester_id            = null; 
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
        case "new":     checkCapabilities('institution:add',    $USER->role_id, false, true);
                        $header = 'Institution hinzufügen';
                        if (!isset($country_id)){ 
                            $country_id = $USER->institution->country_id;
                            $state_id   = $USER->institution->state_id;         
                        }              
            break;
        case "edit":    checkCapabilities('institution:update',    $USER->role_id, false, true);
                        $header     = 'Institution aktualisieren';
                        $ins        = new Institution();
                        $ins->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $ins->load();
                        foreach ($ins as $key => $value){
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

$content ='<form id="form_institution" class="form-horizontal" role="form" method="post" action="../share/processors/fp_institution.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
if (isset($id)) {                                                               // only set id input field if set! prevents error on validation form reload
     $content .= '<input id="id" name="id" type="text" class="invisible" value="'.$id.'">';
}
$content .= Form::input_text('institution', 'Institution / Schule', $institution, $error, 'z. B. Realschule Plus Landau');
$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_text('street',      'Straße',       $street, $error, 'Straße');
$content .= Form::input_text('postalcode',  'Postleitzahl', $postalcode, $error, 'PLZ');
$content .= Form::input_text('city',        'Ort',          $city, $error, 'Ort');
$content .= Form::input_text('phone',       'Telefon',      $phone, $error, 'Telefonnummer');
$content .= Form::input_text('email',       'Email',        $email, $error, 'Email-Adresse');

/* schooltypes */ 
$sch     = new Schooltype();
$content .= '<div id="existingSchooltype"> ';
$content .= Form::input_select('schooltype_id', 'Schulart', $sch->getSchooltypes(), 'schooltype', 'id', $schooltype_id , $error);
$content .= '</div>';

/* add new schooltype*/ 
$content .= Form::input_checkbox('btn_newSchooltype', 'Neuen Schultyp anlegen', $btn_newSchooltype, $error, 'checkbox', 'toggle([\'newSchooltype\'], [\'existingSchooltype\']);');
$content .= '<div id="newSchooltype" ';
if (!isset($new_schooltype) AND !isset($schooltype_description)){ $content .= 'class="hidden"';} // only hide if no Data is given
$content .= '>';
$content .= Form::input_text('new_schooltype', 'Neue Schulart', $new_schooltype, $error, 'z. B. Medienzentrum Landau');
$content .= Form::input_text('schooltype_description', 'Beschreibung', $schooltype_description, $error, 'Beschreibung der neuen Schulart');
$content .= '</div>';

$countries = new State($country_id);                                                   //Load country   
$states    = $countries->getStates();
$content .= Form::input_select('country_id', 'Land', $countries->getCountries(), 'de', 'id', $country_id , $error, 'getValues(\'state\', this.value, \'state_id\');');
$content .= Form::input_select('state_id', 'Bundesland/Region', $states, 'state', 'id', $state_id , $error);
   
/* institution logo */ 

$content .= '<input type="hidden" name="file_id" id="file_id" value="'.$file_id.'"/>';
if (isset($id)) { // id have to be set to add image
$content .= '<div class="col-xs-3"></div><div class="col-xs-9">'
                . '<a href="'.$CFG->smarty_template_dir_url.'renderer/uploadframe.php?context=institution&target=file_id&ref_id='.$id.'&format=0&modal=true" class="nyroModal">'
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
$content .= Form::input_text('timeout', 'Timeout (Minuten)', $timeout, $error, '15','number',1,240);
$content .= '<input type="hidden" name="csv_size" id="csv_size" value="'.$csv_size.'"/>';
$content .= '<input type="hidden" name="avatar_size" id="avatar_size" value="'.$csv_size.'"/>';
$content .= '<input type="hidden" name="material_size" id="material_size" value="'.$csv_size.'"/>';
$content .= '</form>';
$footer   = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_institution\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button> ';
  

$html     = Form::modal(array('title'     => $header,
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
        });
        </script>';
echo json_encode(array('html'=>$html, 'script' => $script));
