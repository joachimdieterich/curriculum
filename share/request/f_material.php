<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_material.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.04.20 08:57
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
global $USER, $PAGE, $CFG;

$USER       = $_SESSION['USER'];
$edit       = checkCapabilities('file:editMaterial',    $USER->role_id, false, true); // DELETE / edit anzeigen
$header     = 'Aktenkoffer';
$m_license_icon = null; //to prevent error logs
$file       = new File();
$repo       = get_plugin('repository', 'sodis');
$func       = filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW);

if (isset($_GET['s_key'])){ $s_key =  $_GET['s_key']; } else { $s_key =  'curriculum'; }

switch ($func) {
    case 'enabling_objective':  
    case 'terminal_objective':  Statistic::setStatistics($_SESSION['CONTEXT'][$func]->context_id, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)); // click counter
                                $files       = $file->getFiles($func, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', array('externalFiles' => true));
                                $sodis       = $repo->get($func, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                                // get internal references
                                $reference   = new Reference();
                                $references  = $reference->get('reference_id', $_SESSION['CONTEXT'][$func]->context_id, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
                                Reference::sortByProp($references, $s_key, 'asc');
                                // get quotes
                                $quote       = new Quote();
                                $quotes      = $quote->get($_SESSION['CONTEXT'][$func]->context_id, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
 
                                if ($func == 'enabling_objective'){
                                    $objective   = new EnablingObjective();
                                    $objective->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); 
                                    $objective->load();
                                    $header     = 'Aktenkoffer<br><small><b>Kompetenz/Bereich</b><br>'.strip_tags($objective->enabling_objective).'</small>'; 
                                } else {
                                    $objective   = new TerminalObjective();
                                    $objective->id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); 
                                    $objective->load();
                                    $header     = 'Aktenkoffer<br><small><b>Kompetenz/Bereich</b><br>'.strip_tags($objective->terminal_objective).'</small>'; 
                                }
        break;
    case 'id' :         $files  = $file->getFiles('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', array('externalFiles' => false, 'user_id' => filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT)));
                        $header = 'Lösungen / Dateien des Users';
                        $edit   = false;    //don't show delete button in solution window
        break;
    case 'solution':    $header                 = 'Eingereichte Lösungen';
                        $course                 = new Course();
                        $course->curriculum_id  = filter_input(INPUT_GET, 'curriculum_id', FILTER_VALIDATE_INT);
                        $members                = $course->members('group_id', filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT));
                        $user_ids               = implode(", ", array_column($members, 'id')); //require php 5.5
                        $files                  = $file->getSolutions('objective', $user_ids, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));  // load solutions
                        $edit                   = false;    //don't show delete button in solution window
        break;
    default:
        break;
}

$f_content  = null;
$content    = null; 
    
if (!$files AND !isset($references) AND !isset($sodis)){
    $content .= 'Es gibt leider keine Eintragungen zur gewählten Kompetenz.';
} else {
    /* Tab header */    
    $file_context_count[1] = 0; // counter for file_context 1 --> global
    $file_context_count[2] = 0; // counter for file_context 2 --> institution
    $file_context_count[3] = 0; // counter for file_context 3 --> group
    $file_context_count[4] = 0; // counter for file_context 4 --> user
    $file_context_count[5] = 0; // counter for file_context 5 --> external reference
    $file_context_count[6] = 0; // counter for file_context 6 --> external webservice ressource
    $file_context_count[7] = 0; // counter for file_context 7 --> internal reference
    $file_context_count[8] = 0; // counter for file_context 8 --> quotes referecne
    $file_context_count[9] = 0; // counter for file_context 9 --> external sodis reference
    
    for($i = 0; $i < count($files); $i++) {         // counter for file context 1-6
        $file_context_count[$files[$i]->file_context]++;
    }
    if (isset($references)){
        $file_context_count[7] = count($references); // counter for file_context 7 --> internal reference
    } 
    if (isset($quotes)){
        if ($quotes != false){
            $file_context_count[8] = count($quotes); // counter for file_context 8 --> quotes referecne
        }
    } 
    if (isset($sodis)){
        $file_context_count[9] = count($sodis);     // counter for file_context 9 --> external sodis reference
    } 
    
    $content .= '<div class="nav-tabs-custom">';
    $active   = array( '1' => '', '2' => '', '3' => '','4' => '','5' => '','6' => '', '7' => '', '8' => '', '9' => '');
    foreach ($file_context_count as $key => $value) { // mark first tab with files as "active"
        $active[$key] = ''; //deactivate == default
        if ($value > 0){
            $active[$key] = 'active';
            break;
        } 
    }
    /* Nav Tabs */
    $tabs   = array('1' => 'Global', 
                    '2' => 'Institution', 
                    '3' => 'Gruppe',
                    '4' => 'Persönlich',
                    '5' => 'Externe Medien',
                    '6' => 'Externe Aufgaben', 
                    '7' => 'Überfachliche Bezüge', 
                    '8' => 'Textstellen-/Bezüge', 
                    '9' => 'KMK');
    $content .= '<ul class="nav nav-tabs">';
    foreach ($tabs as $key => $value) {
        if ($file_context_count[$key] != 0){
            $content .= '<li id="nav_tab_'.$key.'" class="'.$active[$key].'"><a href="#tab_'.$key.'" data-toggle="tab" >'.$value.' <span class="label label-primary">'.$file_context_count[$key].'</span></a></li>';
        }
    }
    $content .='</ul>';
    
    /* tab content*/
    $content .='<div class="tab-content">';
    $file_context       = 1;
    $used_subjects      = [];
    $m_boxes_data       = [];
    $allowed_subject    = new Subject();
    $allowed_subjects   = array();
    foreach($allowed_subject->getSubjects() as $as){
        if ($as->schooltype_id == $USER->institution->schooltype_id) {
            $allowed_subjects[] = $as->subject;
        }
    }
    
    for($i = 0; $i < count($files); $i++) {
        /* reset vars */
        $m_footer       = '';
        $m_player       = null;
        $m_icon_class   = null;
        $m_preview      = null;
        $m_delete       = null;
        $m_content      = ''; 
        if (isset($files[$i]->subjects)){
            foreach ($files[$i]->subjects as $file_subj) {
                if (! isset($used_subjects[$file_subj])) {
                    if (in_array($file_subj, $allowed_subjects) ) {
                        $used_subjects[$file_subj]->subject_id = $file_subj;
                        $used_subjects[$file_subj]->subject    = $file_subj;
                    }
                }
            }
        }
        if ($files[$i]->file_context >= $file_context){ 
            /*switch ($files[$i]->file_context) {
                case 1: $level_header = 'Globale Dateien'; break;
                case 2: $level_header = 'Dateien meiner Instution(en)'; break;
                case 3: $level_header = 'Dateien meiner Gruppe(n)'; break;
                case 4: $level_header = 'Meine Dateien'; break;
                case 5: $level_header = 'Externe Medien'; break;
                case 6: $level_header = 'Externe Aufgaben'; break;
                case 7: $level_header = 'Überfachliche Bezüge'; break;
                case 8: $level_header = 'Textstellen'; break;
                case 9: $level_header = 'KMK'; break;
                default: break;
            }*/ $file_context       = $files[$i]->file_context+1; //file_context auf nächstes Level setzen
        }
        
        $m_title    = '';
        $m_subjects = '';
        $m_url      = '';
        $m_onclick  = '';
        /* Icon */ 
        if ($files[$i]->file_version != false){
            $icon = 0; 
            $f_versions = '';
            foreach ($files[$i]->file_version as $key => $value) {
                foreach ($value as $k => $v) {
                    if ($k == 'filename'){  $filename = $v; }
                    if ($k == 'size')    {      $size = $v; }
                }
                if ($files[$i]->type == 'internal'){
                    $f_versions .= '<a class="pull-right" href="'.$CFG->access_file.$files[$i]->context_path.$files[$i]->path.$filename .'" target="_blank">'.translate_size($key).' ('.$size.')</a><br>'; 
                }        
                if ($key == 't'){
                    if ($files[$i]->type != 'internal'){
                        $preview =  $filename ;
                    } else {
                        $preview =  $CFG->access_file.$files[$i]->context_path.$files[$i]->path.$filename;
                    }
                    $icon ++;
                }   
            }
        }
        /* . Icon */
        if ($files[$i]->type == 'internal'){  //has no effect -> todo cleancode 
            $m_onclick = 'processor("set","count",'.$files[$i]->id.', {"dependency":"fileHits", "reload":"false", "callback":"innerHTML", "element_id":"material_counter_id_'.$files[$i]->id.'"});'; 
        } else { 
            $m_onclick = false; //deactivate onclick ! 
        }
        
        if ($func == 'solution'){
            $m_title = $files[$i]->author.': '.$m_title;
        } else {
            $m_title = $files[$i]->title;
        }
        $m_description  = $files[$i]->description;
        if (isset($files[$i]->subjects)){
            $m_subjects = $files[$i]->subjects;
        } else {
            $m_subjects = [];
        }
        
        switch ($files[$i]->type) {
            case '.url':      $m_url = $files[$i]->path;       
                break;
            case 'external':  $m_url = $files[$i]->filename;
                break;
            case '.mp3':    /* Player*/  
                            $m_player =  '<audio width="100%" controls preload="none" onplay="processor(\'set\',\'count\',\''.$files[$i]->id.'\', {\'dependency\':\'fileHits\', \'reload\':\'false\', \'callback\':\'innerHTML\', \'element_id\':\'material_counter_id_'.$files[$i]->id.'\'});">
                                            <source src="'.$CFG->access_id_url.$files[$i]->id.'" type="audio/mpeg" />
                                        Your browser does not support the audio element.</audio>';
                            $m_url = $CFG->access_id_url.$files[$i]->id;
                break;
            case '.mp4':    /* Player*/ 
            case '.mov':    $m_player =  '<video width="100%" controls onplay="processor(\'set\',\'count\',\''.$files[$i]->id.'\', {\'dependency\':\'fileHits\', \'reload\':\'false\', \'callback\':\'innerHTML\', \'element_id\':\'material_counter_id_'.$files[$i]->id.'\'});">
                                            <source src="'.$CFG->access_id_url.$files[$i]->id.'&video=true"  type="video/mp4"/>
                                          Your browser does not support the video element.</video>';
                break;
            //default:        $m_url = $CFG->access_file.$files[$i]->context_path.$files[$i]->path. $files[$i]->filename;
            default:        $m_url = $CFG->access_id_url.$files[$i]->id;
                break;
        }
        
        if (checkCapabilities('file:showHits', $USER->role_id, false)  && $files[$i]->type != 'external'){ // für Externe Medien können keine Zugriffszahlen erfasst werden
            $m_hits     = $files[$i]->hits;
        }  
            
        /*  Lizenzform */
        $license = new License();
        if ($files[$i]->license != '' && isset($files[$i]->license)){
            if ($files[$i]->file_context == 5){
                $license->license = $files[$i]->license;  //Externes Medium
            } else { 
                $license->get($files[$i]->license);
            }
        } /* . Lizenzform */
    
        /* Materialbox */
        $m_id = $files[$i]->id;
        if (isset($preview)){
            $m_preview = $preview;
        } else {
            $m_icon_class = resolveFileType($files[$i]->type);
        }     
        
        if ((checkCapabilities('file:editMaterial', $USER->role_id, false) && $edit && ($files[$i]->type != 'external')) OR ($files[$i]->creator_id == $USER->id)){
            $m_delete = true;
        }

        /* Material footer */
        if ($files[$i]->type != 'external'){
            $m_footer .= '<div class="info-box-text" style="padding-top:10px;white-space:normal; text-transform:none; display:block;">
                           <div class="row">
                    <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                      <div id="sparkline-1"></div><div class="knob-label">';
            if (isset($license->license)){ 
                if (isset($license->file_id)){ 
                    $m_license_icon = $CFG->access_id_url.$license->file_id;
                } else {
                    $m_footer .= $license->license; 
                }
            }
            $m_footer .= '</div></div><!-- ./col -->
                    <div class="col-xs-3 text-center" style="border-right: 1px solid #f4f4f4">
                      <div id="sparkline-2"></div><div id="material_counter_id_'.$files[$i]->id.'" class="knob-label">';
            if (isset($m_hits)){ $m_footer .= ' '.$m_hits.' Aufrufe'; }
            $m_footer .= '</div>
                    </div><!-- ./col -->
                    <div class="col-xs-4 text-center">
                      <div id="sparkline-3"></div>
                      <div class="knob-label">';
            if (isset($f_versions)){ $m_footer .= $f_versions; }
            $m_footer .= '</div>
                      </div><!-- ./col -->
                      </div><!-- ./row -->
                      </div><!-- ./info-box-text -->';
            
        } else { // Bei Externen Medien nur die Lizenz zeigen
            $m_footer .= '<div class="info-box-text">
                           <div class="row">   
                            <div class="col-xs-12 text-center">
                                <div class="knob-label" style="padding-top:10px;white-space:normal; text-transform:none; display:block;">';
            if (isset($license->license)){ 
                if (filter_var($license->license, FILTER_VALIDATE_URL)) { 
                    $m_footer .= '<img style="max-height:20px;" src="' . $license->license . '">';
                } else { 
                    $m_footer .= $license->license; 
                }
            }
            $m_footer .= '     </div>
                            </div><!-- ./col -->
                           </div><!-- ./row -->
                          </div><!-- ./info-box-text -->';
        } 
        /* End Material footer*/
        $m_boxes_data[$i] = array('id'          => $m_id,
                                  'preview'     => $m_preview,
                                  'license_icon'=> $m_license_icon,
                                  'icon_class'  => $m_icon_class,
                                  'delete'      => $m_delete,
                                  'url'         => $m_url,
                                  'onclick'     => $m_onclick,
                                  'title'       => $m_title,
                                  'description' => $m_description,
                                  'subjects'    => $m_subjects,
                                  'player'      => $m_player,
                                  'content'     => $m_content,
                                  'footer'      => $m_footer);
        unset($m_id, $preview, $m_preview, $m_icon_class, $m_delete, $m_url, $m_onclick, $m_title, $m_description, $m_subjects, $m_player, $m_content, $m_footer, $m_hits, $f_versions, $license);
        
        /* context box */   
        /* generate tabs for each file context*/
        $close = false;
        if (count($files) == ($i+1)OR $files[$i+1]->file_context >= $file_context){ 
            $close = true;
        }
        
        if ($close == true AND $m_boxes_data != []){ //close file_context box // only generate tab-pane when there are files (m_boxes)
            $content   .='<div class="tab-pane';
            if ($active[$file_context-1] == 'active' ){
                $content   .=' active';
            }
            $content   .='" id="tab_'.($file_context-1).'">';
            if ($file_context_count[5] != 0 AND ($file_context-1) == 5) {
                $media_render_data            = [];
                $media_render_data['subject'] = 'false';
                $media_render_data['ajax']    = 'false';
                $media_render_data['m_boxes_json']   = urlencode(json_encode($m_boxes_data, JSON_UNESCAPED_SLASHES));
                asort($used_subjects);
                if (!empty($used_subjects)) { $content .= render_subject_filter($used_subjects); }
                $content   .='<span id="subject_ajax">';
                $content   .= RENDER::external_media($func, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), $media_render_data);
                $content   .='</span>';
            } else { 
                foreach ($m_boxes_data AS $m_box){
                    $content   .= Form::info_box($m_box); //render internal files
                }
                unset($m_boxes_data);
            }
             $content   .='</div>';  
        }
    }
    
    /* internal reference*/
    if (isset($references)){
        if (count($references) > 0 ){
            $content   .='<div class="tab-pane';
            if ($active[7] == 'active' ){
                $content   .=' active';
            }
            $content .='" id="tab_7">';
            if ($_SESSION['PAGE']->reference_curriculum_id == false){
                $content .= render_filter($schooltype_id  = null, $subject_id = null, $curriculum_id = null, $grade_id = null);
                $content .='<span id="reference_ajax">';
                $content .= RENDER::reference($func, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), array('schooltype_id' => 'false', 'subject_id' => 'false', 'curriculum_id' => 'false', 'grade_id' => 'false', 'ajax' => 'false'));
            } else {
                $content .= render_filter($schooltype_id  = null, $subject_id = null, $curriculum_id = $_SESSION['PAGE']->reference_curriculum_id, $grade_id = null);
                $content .='<span id="reference_ajax">';
                $content .= RENDER::reference($func, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), array('schooltype_id' => 'false', 'subject_id' => 'false', 'curriculum_id' => $_SESSION['PAGE']->reference_curriculum_id, 'grade_id' => 'false', 'ajax' => 'false'));
            }
            
            $content   .='</span>';
            $content .='</div>';
        }else{
            $content .= 'Es bestehen keine Bezüge auf Ihrem Freigabelevel.';
        }
    }
    /* end internal reference*/
    /* quotes */    
    if (isset($quotes)){
        if (count($quotes) > 0 ){
            $content   .='<div class="tab-pane';
            if ($active[8] == 'active' ){
                $content   .=' active';
            }
            $content .= '" id="tab_8">';
            $content .= '<br>'.RENDER::quote($quotes, array('schooltype_id' => 'false', 'subject_id' => 'false', 'curriculum_id' => 'false', 'grade_id' => 'false', 'ajax' => 'false')).'<hr>';
            $content .='</div>';
        }
    }   
    /* end quotes */
    /* external sodis reference*/
    if (isset($sodis)){
        if (count($sodis) > 0 ){
            $content   .='<div class="tab-pane';
            if ($active[9] == 'active' ){
                $content   .=' active';
            }
            $sodis_content = '<br><h4><small><strong>'.$repo->config('kmk').'</strong></small></h4><hr>';
            foreach ($sodis as $s) {
                $r = json_decode($s);
                $sodis_content   .= '<li>'.str_replace("0", ".", substr($r->get[0]->id, 5)).'. '.$r->get[0]->description.'</li>';   
            }
            $content   .='" id="tab_9">'.$sodis_content.'</div>';
        }
    }   
    /* end external sodis reference*/                          
    $content   .='</div><!-- /.tab-content -->
                  </div><!-- /.nav-tab-custom -->';
}


if (filter_input(INPUT_GET, 'target', FILTER_SANITIZE_STRING)){
    $target = filter_input(INPUT_GET, 'target', FILTER_SANITIZE_STRING);
} else { $target = 'popup'; }
$html     = Form::modal(array('target' => 'null',
                               'title' => $header, 
                             'content' => $content, 
                          'background' => '#ecf0f5'));  
echo json_encode(array('html'=> $html, 'target' => $target));


function render_filter($schooltype_id  = null, $subject_id = null, $curriculum_id = null, $grade_id = null){
    global $USER;
    $c    = '<div class="row">';
    $schooltypes = new Schooltype();  // Load schooltype 
    $c    .= '<span class="col-sm-3 pull-left">'.Form::input_select('schooltype_id', '', $schooltypes->getSchooltypes(), 'schooltype', 'id', $schooltype_id , null,"$('#reference_ajax').load('../share/request/render_html.php' + '?render=reference&func=".filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW)."&id=".filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)."&schooltype_id='+$('#schooltype_id').val()+'&subject_id='+$('#subject_id').val()+'&curriculum_id='+$('#curriculum_id').val()+'&grade_id='+$('#grade_id').val()+'&ajax=true#reference_ajax');", 'Nach Ausbildungsrichtung filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $subjects                   = new Subject();                                                      
    $subjects->institution_id   = $USER->institutions;
    $c     .= '<span class="col-sm-3 pull-left">'.Form::input_select('subject_id', '', $subjects->getSubjects(), 'subject', 'id', $subject_id , null, "$('#reference_ajax').load('../share/request/render_html.php' + '?render=reference&func=".filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW)."&id=".filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)."&schooltype_id='+$('#schooltype_id').val()+'&subject_id='+$('#subject_id').val()+'&curriculum_id='+$('#curriculum_id').val()+'&grade_id='+$('#grade_id').val()+'&ajax=true#reference_ajax');", 'Nach Fach filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $cur          = new Curriculum();
    $curriculum   = $cur->getCurricula('user', $USER->id);
    $c     .= '<span class="col-sm-3 pull-left">'.Form::input_select('curriculum_id', '', $curriculum, 'curriculum', 'id', $curriculum_id , null, "$('#reference_ajax').load('../share/request/render_html.php' + '?render=reference&func=".filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW)."&id=".filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)."&schooltype_id='+$('#schooltype_id').val()+'&subject_id='+$('#subject_id').val()+'&curriculum_id='+$('#curriculum_id').val()+'&grade_id='+$('#grade_id').val()+'&ajax=true#reference_ajax');", 'Nach Lehr-/Rahmenplan filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $grades       = new Grade();    //Load Grades
    $c     .= '<span class="col-sm-3 pull-left">'.Form::input_select('grade_id', '', $grades->getGrades('institution',$USER->institution_id), 'grade', 'id', $grade_id , null, "$('#reference_ajax').load('../share/request/render_html.php' + '?render=reference&func=".filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW)."&id=".filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)."&schooltype_id='+$('#schooltype_id').val()+'&subject_id='+$('#subject_id').val()+'&curriculum_id='+$('#curriculum_id').val()+'&grade_id='+$('#grade_id').val()+'&ajax=true#reference_ajax');", 'Nach Klassenstufe filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $c    .= '</div>';
    return $c;
}
function render_subject_filter($subjects, $subject_id = null){
    $c    = '<div class="row">';
    $c   .= '<span class="col-sm-3 pull-left">';
    $c   .= Form::input_select('subject','',$subjects,'subject','subject_id',$subject_id,null,"filterBySubject(this.value)",'Nach Fach filtern','col-xs-0','col-xs-12');
    $c   .= '</span></div>';
    return $c;
}
