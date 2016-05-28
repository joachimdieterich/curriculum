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

global $USER, $PAGE, $CFG;
$USER       = $_SESSION['USER'];

$file       = new File(); 
switch (filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW)) {
    case 'ena': $files  = $file->getFiles('enabling_objective', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', true);
        break;
    case 'ter': $files  = $file->getFiles('terminal_objective', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), '', true);
        break;

    default:
        break;
}

$edit       = checkCapabilities('file:editMaterial',    $USER->role_id, false); // DELETE / edit anzeigen
$header     = 'Material';
$f_content  = '';
$content    = ''; 
$m_boxes = '';

if (!$files){
    $content .= 'Es gibt leider kein Material zum gewählten Lernziel.';
} else {
    $file_context = 1;
    for($i = 0; $i < count($files); $i++) {
        $m_content    = ''; 
        if ($files[$i]->file_context >= $file_context){ 
            switch ($files[$i]->file_context) {
                case 1: $level_header = 'Globale Dateien'; break;
                case 2: $level_header = 'Dateien meiner Instution(en)'; break;
                case 3: $level_header = 'Dateien meiner Gruppe(n)';break;
                case 4: $level_header = 'Meine Dateien'; break;
                case 5: $level_header = 'OMEGA Materialien'; break;
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
                if ($files[$i]->type != 'omega'){
                    $f_versions .= '<a class="pull-right" href="'.$CFG->curriculum_path.$files[$i]->path.$filename .'" target="_blank">'.translate_size($key).' ('.$size.')</a><br>'; 
                }        
                if ($key == 't'){
                    if ($files[$i]->type == 'omega'){
                        $preview =  $filename ;
                    } else {
                        $preview =  $CFG->curriculum_path.$files[$i]->path.$filename;
                    }
                    $icon ++;
                }   
            }
        }
        /* . Icon */

        if ($files[$i]->type != 'omega'){ $m_onclick      = 'updateFileHits('.$files[$i]->id.')'; }
        $m_title        = $files[$i]->title;
        $m_description  = $files[$i]->description;
        
        switch ($files[$i]->type) {
            case '.url':    $m_url = $files[$i]->path;       
                break;
            case 'omega':   $m_url = $CFG->curriculum_path.$files[$i]->path. $files[$i]->filename;
                break;
            case '.mp3':    /* Player*/  
                            $m_player =  '<audio width="100%" controls preload="none">
                                            <source src="'.$CFG->curriculum_path.$files[$i]->path. $files[$i]->filename.'" type="audio/mpeg" />
                                        Your browser does not support the audio element.</audio>';
                break;
            case '.mp4':    /* Player*/ 
            case '.mov':    
                            $m_player =  '<video width="100%" controls>
                                            <source src="'.$CFG->curriculum_path.$files[$i]->path.$files[$i]->filename.'&video=true"  type="video/mp4"/>
                                            <!--source src="http://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4" /-->
                                        Your browser does not support the video element.</video>';
                break;
            default:      
                break;
        }
        
        if (checkCapabilities('file:showHits', $USER->role_id, false)  && $files[$i]->type != 'omega'){ // für omega können keine Zugriffszahlen erfasst werden
            $m_hits     = $files[$i]->hits;
        }  
            
        /*  Lizenzform */
        $license = new License();
        if ($files[$i]->license != '' && isset($files[$i]->license)){
            if ($files[$i]->file_context == 5){
                $license->license = $files[$i]->license;  //OMEGA
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
        
        if (checkCapabilities('file:editMaterial', $USER->role_id, false) && $edit && ($files[$i]->type != 'omega')){
            $m_delete = true;
        }

        /* Material footer */
        /* End Material footer*/
        if ($files[$i]->type != 'omega'){
        
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
            
        } else { // Bei OMEGA Materialien nur die Lizenz zeigen
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
        unset($m_id, $m_preview, $m_icon_class, $m_delete, $m_url, $m_onclick, $m_title, $m_description, $m_player, $m_content, $m_footer, $m_hits, $f_versions, $license);
        
        /* context box */   
        $close = false;
        if (count($files) == ($i+1)){ 
            $close = true;
        } else {
            if ($files[$i+1]->file_context >= $file_context){ $close = true; }  
        }
        if ($close == true){ //close file_context box
            $content   .= Form::box(array('header' => $level_header,
                                         'content' => $m_boxes));  
            unset($m_boxes);
        }

    }
}

$html     = Form::modal(array('title' => $header, 
                            'content' => $content, 
                            'background' => '#ecf0f5'));  
echo json_encode(array('html'=> $html));