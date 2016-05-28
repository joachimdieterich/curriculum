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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

global $CFG, $USER, $INSTITUTION;
$USER           = $_SESSION['USER'];
$INSTITUTION    = $_SESSION['INSTITUTION'];
$cur            = new Curriculum();
/*Variablen anlegen -> vermeidet unnötige if-Abfragen im Formular*/
$id                     =   null; 
$institution            =   null; 
$description            =   null; 
$schooltype_id          =   null; 
$new_schooltype         =   null;
$schooltype_description =   null; 
$country_id             =   null;
$state_id               =   null;
$file_id                =   null; 

$paginator_limit        =   null; 
$std_role               =   null; 
$csv_size               =   null; 
$avatar_size            =   null; 
$acc_days               =   null; 
$timeout                =   null; 
$semester_id            =   null; 
$error                  =   null;
$object = file_get_contents("php://input");
$data = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
        error_log($key.': '.$value);
    }
}
            
if (isset($_GET['function'])){
    switch ($_GET['function']) {
        case "new":     checkCapabilities('institution:add',    $USER->role_id);
                        $header = 'Institution hinzufügen';
                        if (!isset($country_id)){ 
                            $country_id = $INSTITUTION->country_id;
                            $state_id   = $INSTITUTION->state_id;         
                        }
                        $add = true;              
            break;
        case "edit":    checkCapabilities('institution:update',    $USER->role_id);
                        $header = 'Institution bearbeiten';
                        $edit = true; 
            break;
        default: break;
    }
}


$help = "curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Institutionen');"; // not used yet

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
   
$html .='<form id="institutionForm"  class="form-horizontal" role="form" method="post" action="index.php?action=institution&function='.$_GET['function'];

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><h4>Institution</h4>
<input id="id" name="id" type="text" class="invisible" ';
if (isset($id)) { $html .= 'value="'.$id.'"';} $html .= '>';

$html .= Form::input_text('institution', 'Institution / Schule', $institution, $error, 'z. B. Realschule Plus Landau');
$html .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');

/* schooltypes */ 
$sch     = new Schooltype();
$html .= Form::input_select('schooltype_id', 'Schulart', $sch->getSchooltypes(), 'schooltype', 'id', $schooltype_id , $error);


/* add new schooltype*/ 
$html .= Form::input_checkbox('btn_newSchooltype', 'Neuen Schultyp anlegen', $btn_newSchooltype, $error, 'checkbox', 'toggle([\'newSchooltype\'], [\'schooltype_id\']);');
$html .= '<div id="newSchooltype" ';
if (!isset($new_schooltype) AND !isset($schooltype_description)){ $html .= 'class="hidden"';} // only hide if no Data is given
$html .= '>';
$html .= Form::input_text('newSchool', 'Neue Schulart', $new_schooltype, $error, 'z. B. Medienzentrum Landau');
$html .= Form::input_text('schooltype_description', 'Beschreibung', $schooltype_description, $error, 'Beschreibung der neuen Schulart');
$html .= '</div>';

$countries   = new State($country_id);                                                   //Load country   
$html       .= Form::input_select('state_id', 'Bundesland/Region', $countries->getStates(), 'state', 'id', $state_id , $error);
$html       .= Form::input_select('country_id', 'Land', $countries->getCountries(), 'de', 'id', $country_id , $error, 'getStates(this.value, \'state_id\');');
   
/* Schullogo */ 
$logo = new File(); 
$html .= Form::input_text('file_id', 'Logo', $file_id, $error, '... hier klicken');
/*$html .= Form::input_select('icon_id', 'Fach-Icon', $icons->getFiles('context', 5), 'title', 'id', $icon_id , $error, 'showSubjectIcon(\''.$CFG->access_id_url .'\', this.options[this.selectedIndex].value);');*/
/* Icon Preview */
$html .= '<div class="form-group"><label class="control-label col-sm-4"></label>
      <div class="col-sm-7"> <div id="icon_img" class="form-control input-lg bg-white col-sm-7" ';
if ($icon_id){
    $html .= 'style="background-image: url(\''.$CFG->access_id_url . $icon_id.'\'); background-position: 50% 50%; background-repeat: initial initial;"';
}
$html .= '></div></div></div>';

$html .= '<h4>Einstellungen</h4>';
$rol         = new Roles(); 
$html .= Form::input_select('std_role', 'Rolle', $rol->get(), 'role', 'id', $std_role , $error);
if ($semester_id){
    $html .= Form::input_select('semester_id', 'Semester', $rol->get(), 'semester', 'id', $semester_id , $error);
}
$html .= Form::input_text('paginator_limit', 'Listeneinträge / Seite', $paginator_limit, $error, '30','number',5,150);
$html .= Form::input_text('acc_days', 'Lernerfolge x Tage anzeigen', $acc_days, $error, '7','number',1,356);
$html .= Form::input_text('timeout', 'Timeout (Minuten)', $acc_days, $error, '15','number',1,240);
$html .= Form::input_text('csv_size', 'CSV-Dateien (byte)', $csv_size, $error, '30','number',5000,1048576);
$html .= Form::input_text('avatar_size', 'Profilfotos (byte)', $csv_size, $error, '30','number',5000,1048576);
$html .= Form::input_text('material_size', 'Dateien (byte)', $csv_size, $error, '30','number',5000,1048576);
$html       .= '</div><!-- /.modal-body -->
            <div class="modal-footer">';
            if (isset($edit)){
                $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right"> Institution aktualisieren</button>'; 
            } 
            if (isset($add)){
                $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right"> Institution hinzufügen</button> ';
            }    
$html .=  '</div></form></div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));
