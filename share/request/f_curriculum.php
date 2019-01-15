<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename form_curriculum.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.03.30 13:08
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
$id             = null; 
$curriculum     = null; 
$description    = null; 
$subject_id     = null;  
$icon_id        = null;     
$semester_id    = null;  
$institution_id = null; 
$grade_id       = null;       
$schooltype_id  = null;  
$state_id       = null;
$publisher      = null;
$publishingCompany = null;
$place          = null;
$date           = null;
$color          = '#3cc95b';
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
        case "new":     checkCapabilities('curriculum:add',     $USER->role_id, false, true);     //USER berechtigt?
                        $header = 'Lehrplan hinzufügen';
                        if (!isset($country_id)){ 
                            $country_id = $USER->country_id;
                            $state_id   = $USER->state_id;         
                        }
                        $add = true;      
            break;
        case "edit":    checkCapabilities('curriculum:update',     $USER->role_id, false, true);     //USER berechtigt?
                        $header  = 'Lehrplan bearbeiten';
                        $cur->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $cur->load();
                        foreach ($cur as $key => $value){
                            $$key = $value;
                        }
                        $edit = true; 
            break;
        case "import":  checkCapabilities('curriculum:import',     $USER->role_id, false, true);     //USER berechtigt?
                        $header = 'Lehrplan importieren';
                        $import = true;
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

$content ='';
if (!isset($edit)){ // Tabs ausblenden wenn im Edit-Modus
$content .= '<div class="nav-tabs-custom"> 
              <ul class="nav nav-tabs">
                <li id="nav_tab_add" class="active"><a href="#tab_add" data-toggle="tab" aria-expanded="false" onclick="toggle([\'form_curriculum\', \'bAdd\'], [\'tab_add\']);">Lehrplan hinzufügen</a></li>';
                if (checkCapabilities('curriculum:import', $USER->role_id, false, true)){
                  $content .= '<li id="nav_tab_import" class=""><a href="#tab_import" data-toggle="tab" aria-expanded="true" onclick="toggle([\'tab_add\'], [\'form_curriculum\', \'bAdd\']);">Lehrplan importieren</a></li>';
                }
$content .='</ul>';

$content .= '<div class="tab-content">
      <div id="tab_add" class="tab-pane active">
      </div><!-- /.tab-pane -->
      <div id="tab_import" class="tab-pane">
        <div id="upload_form">
            <form id="curriculum_upload" action="" method="post" enctype="multipart/form-data">
                <div class="form-group clearfix">
                  <div class="pull-left">
                    <span id="curriculum_upload_fName" class="hidden"></span><br>
                    <span id="curriculum_upload_fSize" class="hidden"></span><br>';
$content .= '<input id="max_size" name="max_size" type="hidden" value="<?php echo return_bytes(ini_get(\'upload_max_filesize\')); ?>" />';
$content .= '       <span id="curriculum_upload_fType" class="hidden"></span>
                    <div id="curriculum_upload_fProgress" class="progress">
                        <div id="curriculum_upload_fProgress_bar" class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" >
                          <span class="sr-only" id="curriculum_upload_fPercent"></span>
                        </div>
                     </div>
                     </span>
                  </div> 
                  <button id="curriculum_upload_fUpload" name="fileUpload" type="button" value="" class="btn btn-primary pull-right hidden " onclick="uploadFile(\'curriculum_upload\', \'import\');" >
                        <span class="fa fa-cloud-upload" aria-hidden="true" ></span> Sicherung laden
                  </button>
                  <button id="curriculum_upload_fAbort" name="abort" type="button" value="" class="btn btn-primary pull-right hidden" onclick="uploadAbort();">
                        <span class="fa fa-times" aria-hidden="true"></span> Abbrechen
                  </button>
                  <input id="curriculum_upload_fSelector" name="file" type="file"  class="btn btn-primary pull-left" onchange="fileChange(\'curriculum_upload\');">
                </div>  
            </form>
        </div>
      </div><!-- /.tab-pane -->
    </div><!-- /.tab-content -->
  </div><!-- /.nav-tab-custom -->';
}

$content .='<form id="form_curriculum"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_curriculum.php"';
if (isset($currentUrlId)){ $content .= $currentUrlId; }
$content .= '">
<input id="importFileName" name="importFileName" type="text" class="hidden" value="">
<input type="hidden" name="func" id="func" value="'.$func.'"/>
<input id="id" name="id" type="text" class="invisible" ';
if (isset($id)) { $content .= 'value="'.$id.'"';} $content .= '>';

$content .= Form::input_text('curriculum', 'Titel des Lehrplans', $curriculum, $error, 'z. B. Deutsch');
//$content .= Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung');
$content .= Form::input_text('publisher', 'Herausgeber', $publisher, $error, 'z. B. Land Rheinland-Pfalz');
$content .= Form::input_text('publishingCompany', 'Verlag', $publishingCompany, $error, 'z. B. Digitalverlag');
$content .= Form::input_text('place', 'Ort', $place, $error, 'z. B. Landau');
$content .= Form::input_text('date', 'Datum/Jahr', $date, $error, 'z. B. 27.04.1987');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'Beschreibung');
/* Fächer */ 
$subjects                   = new Subject();                                                      
$subjects->institution_id   = $USER->institutions;
$content .= Form::input_select('subject_id', 'Fach', $subjects->getSubjects(), 'subject, institution', 'id', $subject_id , $error);
/* Fach-Bild */ 
$icons = new File();                                                            
$content .= Form::input_select('icon_id', 'Fach-Icon', $icons->getFiles('context', 5), 'title', 'id', $icon_id , $error, 'showSubjectIcon(\''.$CFG->access_id_url .'\', this.options[this.selectedIndex].value);');
/* Icon Preview */
$content .= '<div class="form-group"><label class="control-label col-sm-3"></label>
      <div class="col-sm-9"> <div id="icon_img" class="form-control input-lg bg-white col-sm-9" ';
if ($icon_id){
    $content .= 'style="background-image: url(\''.$CFG->access_id_url . $icon_id.'\'); background-position: 50% 50%; background-repeat: initial initial;"';
}
$content .= '></div></div></div>';

$grades = new Grade();                                                          // Load grades
$grades->institution_id = $USER->institutions;
$content       .= Form::input_select('grade_id', 'Klassenstufe', $grades->getGrades(), 'grade', 'id', $grade_id , $error);

$schooltypes = new Schooltype();                                                // Load schooltype 
$content       .= Form::input_select('schooltype_id', 'Schultyp', $schooltypes->getSchooltypes(), 'schooltype', 'id', $schooltype_id , $error);
$countries = new State();                                                   //Load country   
$content  .= Form::input_select('country_id', 'Land', $countries->getCountries(), 'de', 'id', $country_id , $error, 'getValues(\'state\', this.value, \'state_id\');');
$countries->load($country_id);
$content  .= Form::input_select('state_id', 'Bundesland/Region', $countries->getStates(), 'state', 'id', $state_id , $error);
$content  .= Form::input_color(array('id' => 'color', 'rgb' => $color, 'error' => $error));
$content  .= '</form>';
$f_content = '';   

if (isset($edit)){
    $f_content .= '<button name="update" type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_curriculum\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>Lehrplan aktualisieren</button>'; 
} 
$f_content .= '<button id="bImport" name="import" type="submit" class="btn btn-primary pull-right hidden" onclick="document.getElementById(\'form_curriculum\').submit();">Lehrplan importieren</button>'; 
if (isset($add)){
    $f_content .= '<button id="bAdd" name="add" type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_curriculum\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>Lehrplan hinzufügen</button> ';
}    
$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $f_content));
$script = "<script id='modal_script'>
        $.getScript('".$CFG->smarty_template_dir_url."plugins/colorpicker/bootstrap-colorpicker.min.js', function (){
            $('.color-picker').colorpicker();
            });</script>";

echo json_encode(array('html'=>$html, 'script' => $script));