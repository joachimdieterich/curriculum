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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $PAGE, $CFG;
$USER       = $_SESSION['USER'];
$edit       = checkCapabilities('file:editMaterial',    $USER->role_id, false); // DELETE / edit anzeigen
$header     = 'Material';

$file       = new File();
$func       = filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW);
switch ($func) {
    case 'enabling_objective':         $files  = $file->getFiles('enabling_objective', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', array('externalFiles' => true));
        break;
    case 'terminal_objective':         $files  = $file->getFiles('terminal_objective', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', array('externalFiles' => true));
        break;
    case 'id' :         $files  = $file->getFiles('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', array('externalFiles' => false, 'user_id' => filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT)));
                        $header = 'Lösungen / Dateien des Users';
                        $edit   = false;    //don't show delete button in solution window
        break;
    case 'solution':    $header  = 'Eingereichte Lösungen';
                        $course  = new Course();
                        $course->curriculum_id = filter_input(INPUT_GET, 'curriculum_id', FILTER_VALIDATE_INT);
                        $members = $course->members('group_id', filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT));
                        $user_ids = implode(", ", array_column($members, 'id')); //require php 5.5
                        $files   = $file->getSolutions('objective', $user_ids, filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));  // load solutions
                        $edit    = false;    //don't show delete button in solution window
        break;
    default:
        break;
}


$f_content  = null;
$content    = null; 
$m_boxes    = '';

if (!$files){
    $content .= 'Es gibt leider kein Material zum gewählten Lernziel.';
} else {
    /* Tab header */
    $file_context_count[1] = 0; // counter for file_context 1
    $file_context_count[2] = 0; // counter for file_context 2
    $file_context_count[3] = 0; // counter for file_context 3
    $file_context_count[4] = 0; // counter for file_context 4
    $file_context_count[5] = 0; // counter for file_context 5
    for($i = 0; $i < count($files); $i++) {
        $file_context_count[$files[$i]->file_context]++;
    }
    $content .= '<div class="nav-tabs-custom">';
    $active   = array( '1' => '', '2' => '', '3' => '','4' => '','5' => '');
    foreach ($file_context_count as $key => $value) { // mark first tab with files as "active"
        if ($value > 0){
            $active[$key] = 'active';
            break;
        } else {
            $active[$key] = '';
        }
    }
    $content .= '<ul class="nav nav-tabs">';
    if ($file_context_count[1] != 0){
        $content .= '<li class="'.$active[1].'"><a href="#f_context_1" data-toggle="tab" >Global <span class="label label-primary">'.$file_context_count[1].'</span></a></li>';
    }  
    if ($file_context_count[2] != 0){
        $content .= '<li class="'.$active[2].'"><a href="#f_context_2" data-toggle="tab" >Institution <span class="label label-primary">'.$file_context_count[2].'</span></a></li>';
    }
    if ($file_context_count[3] != 0){
        $content .= '<li class="'.$active[3].'"><a href="#f_context_3" data-toggle="tab" >Gruppe <span class="label label-primary">'.$file_context_count[3].'</span></a></li>';
    }
    if ($file_context_count[4] != 0){
        $content .= '<li class="'.$active[4].'"><a href="#f_context_4" data-toggle="tab" >Persönlich <span class="label label-primary">'.$file_context_count[4].'</span></a></li>';
    }
    if ($file_context_count[5] != 0){
        $content .= '<li class="'.$active[5].'"><a href="#f_context_5" data-toggle="tab" >Externe Medien <span class="label label-primary">'.$file_context_count[5].'</span></a></li>';
    }
    $content .='</ul>';
    /* tab content*/
    $content .='<div class="tab-content">';
    
    $file_context = 1;
    for($i = 0; $i < count($files); $i++) {
        /* reset vars */
        $m_footer       = '';
        $m_player       = null;
        $m_icon_class   = null;
        $m_preview      = null;
        $m_delete       = null;
        $m_content      = ''; 
        
        if ($files[$i]->file_context >= $file_context){ 
            switch ($files[$i]->file_context) {
                case 1: $level_header = 'Globale Dateien'; break;
                case 2: $level_header = 'Dateien meiner Instution(en)'; break;
                case 3: $level_header = 'Dateien meiner Gruppe(n)'; break;
                case 4: $level_header = 'Meine Dateien'; break;
                case 5: $level_header = 'Externe Medien'; break;
                default: break;
            } $file_context       = $files[$i]->file_context+1; //file_context auf nächstes Level setzen
        }
        
        $m_title    = '';
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
                if ($files[$i]->type != 'external'){
                    $f_versions .= '<a class="pull-right" href="'.$CFG->access_file.$files[$i]->context_path.$files[$i]->path.$filename .'" target="_blank">'.translate_size($key).' ('.$size.')</a><br>'; 
                }        
                if ($key == 't'){
                    if ($files[$i]->type == 'external'){
                        $preview =  $filename ;
                    } else {
                        $preview =  $CFG->access_file.$files[$i]->context_path.$files[$i]->path.$filename;
                    }
                    $icon ++;
                }   
            }
        }
        /* . Icon */

        if ($files[$i]->type != 'external'){ $m_onclick      = 'updateFileHits('.$files[$i]->id.')'; }
        
        if ($func == 'solution'){
            $m_title = $files[$i]->author.': '.$m_title;
        } else {
            $m_title        = $files[$i]->title;
        }
        $m_description  = $files[$i]->description;
        
        switch ($files[$i]->type) {
            case '.url':      $m_url = $files[$i]->path;       
                break;
            case 'external':  $m_url = $files[$i]->filename;
                break;
            case '.mp3':    /* Player*/  
                            $m_player =  '<audio width="100%" controls preload="none">
                                            <source src="'.$CFG->access_file.$files[$i]->context_path.$files[$i]->path.$files[$i]->filename.'" type="audio/mpeg" />
                                        Your browser does not support the audio element.</audio>';
                break;
            case '.mp4':    /* Player*/ 
            case '.mov':    $m_player =  '<video width="100%" controls>
                                            <source src="'.$CFG->access_file.$files[$i]->context_path.$files[$i]->path.$files[$i]->filename.'&video=true"  type="video/mp4"/>
                                          Your browser does not support the video element.</video>';
                break;
            default:        $m_url = $CFG->access_file.$files[$i]->context_path.$files[$i]->path. $files[$i]->filename;
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
        
        if (checkCapabilities('file:editMaterial', $USER->role_id, false) && $edit && ($files[$i]->type != 'external')){
            $m_delete = true;
        }

        /* Material footer */
        /* End Material footer*/
        if ($files[$i]->type != 'external'){

            $m_footer .= '<div class="info-box-text" style="padding-top:10px;white-space:normal; text-transform:none; display:block;">
                           <div class="row">
                    <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                      <div id="sparkline-1"></div><div class="knob-label">';
            if (isset($license->license)){ $m_footer .= $license->license; }
            $m_footer .= '</div></div><!-- ./col -->
                
                    <div class="col-xs-3 text-center" style="border-right: 1px solid #f4f4f4">
                      <div id="sparkline-2"></div><div class="knob-label">';
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
            if (isset($license->license)){ $m_footer .= $license->license; }
            $m_footer .= '     </div>
                            </div><!-- ./col -->
                           </div><!-- ./row -->
                          </div><!-- ./info-box-text -->';
        }   
        
        $m_boxes .= Form::info_box(array('id'          => $m_id,
                                         'preview'     => $m_preview,
                                         'icon_class'  => $m_icon_class,
                                         'delete'      => $m_delete,
                                         'url'         => $m_url,
                                         'onclick'     => $m_onclick,
                                         'title'       => $m_title,
                                         'description' => $m_description,
                                         'player'      => $m_player,
                                         'content'     => $m_content, 
                                         'footer'      => $m_footer));
        unset($m_id, $preview, $m_preview, $m_icon_class, $m_delete, $m_url, $m_onclick, $m_title, $m_description, $m_player, $m_content, $m_footer, $m_hits, $f_versions, $license);

        /* context box */   
        /* generate tabs for each file context*/
        $close = false;
        if (count($files) == ($i+1)){ 
            $close = true;
        } else {
            if ($files[$i+1]->file_context >= $file_context){ $close = true; }  
        }
        
        if ($close == true AND $m_boxes != ''){ //close file_context box // only generate tab-pane when there are files (m_boxes)
            $content   .='<div class="tab-pane';
            if ($active[$file_context-1] == 'active' ){
                $content   .=' active';
            }
            $content   .='" id="f_context_'.($file_context-1).'">'.$m_boxes.'</div>';
            unset($m_boxes);
            $m_boxes = '';
        }

    }
    $content   .='</div><!-- /.tab-content -->
                        </div><!-- /.nav-tab-custom -->';
}


if (filter_input(INPUT_GET, 'target', FILTER_SANITIZE_STRING)){
    $target = filter_input(INPUT_GET, 'target', FILTER_SANITIZE_STRING);
} else { $target = 'popup'; }
$html     = Form::modal(array('target'   => 'null',
                                 'title' => $header, 
                               'content' => $content, 
                            'background' => '#ecf0f5'));  
echo json_encode(array('html'=> $html, 'target' => $target));