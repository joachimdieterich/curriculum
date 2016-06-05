<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename upload.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.01.07 10:09
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
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER   = $_SESSION['USER'];

$my_upload = new file_upload; // my_upload muss auch bei URLs existieren da sonst Bedingung nach validation nicht funktioniert
if (isset($_FILES['upload']) AND isset($USER)){ 
    $my_upload->upload_dir = $CFG->backup_root.'tmp/'; //Set current uploaddir;
    $my_upload->extensions = array(".png", ".jpg", ".jpeg", ".gif", ".pdf", ".doc", ".docx", ".ppt", ".pptx", ".txt", ".rtf", ".bmp", ".tiff", ".tif", ".mpg", ".mpeg" , ".mpe", ".mp3", ".m4a", ".qt", ".mov", ".mp4", ".avi", ".aif", ".aiff", ".wav", ".zip", ".rar", ".mid", ".imscc", ".curriculum"); // allowed extensions
    $my_upload->rename_file = false;
    $my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
    $my_upload->the_file = str_replace(' ', '_', $_FILES['upload']['name']);
    $filename = $my_upload->the_file;

    $my_upload->http_error = $_FILES['upload']['error'];    
    if (file_exists($my_upload->upload_dir.$my_upload->the_file)){              // Falls Datei existiert löschen --> kommt bei fehlerhaften Uploads vor.
        unlink($my_upload->upload_dir.$my_upload->the_file);
    }
    $my_upload->upload(); 
    
}
