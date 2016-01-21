<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename showMaterial.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.01 08:57
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

global $USER, $PAGE;
$USER       = $_SESSION['USER'];

$file       = new File(); 
if (filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT)) {
    $files  = $file->getFiles('enabling_objective', filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT));
} else {
    $files  = $file->getFiles('terminal_objective', filter_input(INPUT_GET, 'terminalObjectiveID', FILTER_VALIDATE_INT));
}
$edit       = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_BOOLEAN); // DELETE anzeigen

echo '<div class="messageboxClose" onclick="closePopup();"></div><div class="contentheader">Material</div>
      <div id="popupcontent" class="scroll">';
if (!$files){
    echo 'Es gibt leider kein Material zum gewählten Lernziel.<p class="space-bottom"></p>';
} else {$file_context = 1;
    for($i = 0; $i < count($files); $i++) {
        if ($files[$i]->file_context >= $file_context){ 
           echo '<p><h3>';
            switch ($files[$i]->file_context) {
                case 1: echo 'Globale Dateien'; break;
                case 2: echo 'Dateien meiner Instution(en)'; break;
                case 3: echo 'Dateien meiner Gruppe(n)';break;
                case 4: echo 'Meine Dateien'; break;
                case 5: echo 'OMEGA Materialien'; break;
                default:
                    break;
            } $file_context = $files[$i]->file_context+1; //file_context auf nächstes Level setzen
            echo '</h3></p><p class="space-top"></p>';
        }
        
        echo '<span id="material_'.$files[$i]->id.'">';
        
        if ($files[$i]->file_version == false){
            echo '<div class="'.ltrim ($files[$i]->type, '.').'_btn floatleft" style="width:120px;"></div>';
            if (checkCapabilities('file:editMaterial', $USER->role_id, false) && $edit){
                echo '<span class="deletebtn floatright" onclick="removeMaterial('.$files[$i]->id.');"></span>';
            } 
            if (checkCapabilities('file:showHits', $USER->role_id, false)  && $edit){
                echo '<span class="floatright space-right materialtxt_small">'.$files[$i]->hits.' Aufrufe</span>';
            }
        } else {
            $icon = 0; 
            foreach ($files[$i]->file_version as $key => $value) {
                foreach ($value as $k => $v) {
                    if ($k == 'filename'){$filename = $v;}
                    if ($k == 'size'){$size = $v;}
                }
                if ($key == 't'){
                    if ($files[$i]->type == 'omega'){
                        echo '<img  class="floatleft gray-border" style="width:120px;width:100px;" src="'.$filename .'">';
                    } else {
                        echo '<img  class="floatleft gray-border" style="width:120px;width:100px;" src="'.$CFG->curriculum_path.$files[$i]->path.$filename .'">';
                    }
                    $icon ++;
                }   
            }
            if ($icon == 0){
                    echo' <div class="'.ltrim ($files[$i]->type, '.').'_btn floatleft" style="width:120px;"></div>';
                    $icon ++;
                }
        }

        if ($files[$i]->type == '.url' or $files[$i]->type == 'omega') {
            if ($files[$i]->type == '.url'){
                echo '<div class="materialtxt" onclick="updateFileHits('.$files[$i]->id.')"><p> <a href="'.$files[$i]->path.'" target="_blank">'.$files[$i]->title.'</a><br></p>';
            } else {
               echo '<div class="materialtxt"><p> <a href="'.$files[$i]->path.'" target="_blank">'.$files[$i]->title.'</a><br></p>'; 
            }
               echo '<lable></label><p>'.$files[$i]->description .' &nbsp;</p></div>'; // Leerzeichen  &nbsp; wichtig bei fehlender Beschreibung sonst wird es falsch dargestellt 
            } else {
                if ($files[$i]->file_version == true){
                    if (checkCapabilities('file:editMaterial', $USER->role_id, false) && $edit){
                        echo '<span class="deletebtn floatright" style="margin-left:10px;" onclick="deleteFile('.$files[$i]->id.');"></span>';
                    }
                    if (checkCapabilities('file:showHits', $USER->role_id, false)){
                        echo '<span class="floatright space-right materialtxt_small">'.$files[$i]->hits.' Aufrufe</span>';
                    }
                    echo '<div class=" floatright" style="width:100px;" onclick="updateFileHits('.$files[$i]->id.')">';
                    foreach ($files[$i]->file_version as $key => $value) {
                        foreach ($value as $k => $v) {
                            if ($k == 'filename'){$filename = $v;}
                            if ($k == 'size'){$size = $v;}
                        }
                        echo '<a class="floatright materialtxt_small" href="'.$CFG->curriculum_path.$files[$i]->path.$filename .'" target="_blank">'.translate_size($key).'('.$size.')</a><br>'; 
                    }
                    echo '</div>';
                }

                if ($files[$i]->type == '.mp3'){
                    echo '<div class="materialtxt" onclick="updateFileHits('.$files[$i]->id.')"><p><a href="'.$CFG->curriculum_path.$files[$i]->path. $files[$i]->filename .'" target="_blank">'.$files[$i]->title.'</a><br>
                        <audio controls >';
                        //<source src="'.$CFG->access_file_url.'curriculum/'.$files[$i]->path. $files[$i]->filename.'" type="audio/mpeg"> //damit session_id übergeben wird --> wird datei über http:// aufgerufen ändert sich die session id
                    echo    '<source src="'.$CFG->curriculum_path.$files[$i]->path. $files[$i]->filename.'" type="audio/mpeg">
                      Your browser does not support the audio element.
                    </audio>';
                } else {
                    echo '<div class="materialtxt" onclick="updateFileHits('.$files[$i]->id.')"><p><a href="'.$CFG->curriculum_path.$files[$i]->path. $files[$i]->filename .'" target="_blank" onclick="updateFileHits('.$files[$i]->id.')">'.$files[$i]->title.'</a>';
                }
                echo ' <lable></label><p>'.$files[$i]->description .' &nbsp;</p></div>'; // Leerzeichen  &nbsp; wichtig bei fehlender Beschreibung sonst wird es falsch dargestellt 
                echo '</p>';  
            }
        if ($files[$i]->file_context != 5 && $files[$i]->licence != ''){
            echo'<div class="materialtxt"><p>Lizenz: '.$files[$i]->getLicence($files[$i]->licence).'</p></div>';    // Lizenzform anzeigen
        } else { 
            if (isset($files[$i]->licence) && $files[$i]->licence != ''){
                echo'<div class="materialtxt"><p>Lizenz: '.$files[$i]->licence.'</p></div>';    // Lizenzform anzeigen
            }

        }
        if (isset($files[$i+1])){
            if ($files[$i+1]->file_context == $files[$i]->file_context){ // Linie nur ausgeben, wenn nächstes Material im gleichen file_context
                echo '<span class="clearfix"></span><div class="materialseperator"></div><div class="space-top"></div>';
            }  else { echo '<p class="space-bottom"></p>';}
        } else { echo '<p class="space-bottom"></p>';}
        echo '</span>';
    }
}  

echo '</div><p class="space-bottom"></p><div class="popup_footer"><input class="space-left" type="submit" name="Submit" value="Fenster schließen" onclick="closePopup()"/></div></div>';