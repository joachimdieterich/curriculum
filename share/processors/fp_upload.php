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
global $CFG, $PAGE, $USER, $LOG;
if (!isset($_SESSION['USER'])){ die(); }    // logged in?
$USER   = $_SESSION['USER'];

foreach ($_POST as $key => $value) {
    $$key = $value;
    //error_log($key.': '.$value);
}

$file       = new File();

/* set defaults*/
$title      = null; 
$context    = null; 
$author     = $USER->firstname.' '.$USER->lastname;
$action     = 'upload';
$license    = 2;
$error      = '';
$image      = '';
$copy_link  = '';

//$v_error  = false;
/* get url parameters */
foreach ($_GET  as $key => $value) { $$key = $value; } 
/* get form data */
foreach ($_POST as $key => $value) { $$key = $value; }

// Pfade
switch ($context) {
case "userFiles":   
case "avatar":
case "editor":      $folders = $USER->id.'/'; //siehe unten                
                    break;
case "solution":    $folders = $curID.'/'.$terID.'/'.$enaID.'/'; //siehe unten                
                    break;  
                
case "curriculum":  if ($enaID != 0){
                        $folders = $curID.'/'.$terID.'/'.$enaID.'/'; // Dateien die zu einem Ziel gehören
                    } else {
                        $folders = $curID.'/'.$terID.'/'; // Dateien die zum Thema gehören
                        $enaID = -1; //todo statt -1 sollte null verwendet werden
                    }
                    break;

case "badge":       $folders = '/'; //siehe unten                        
    break;  
case "institution": $folders = $insID.'/'; //geändert, vorher wurde curID für den Wert verwendet! 
    break;  
default:            $folders = '';    
    break;
}

$my_upload = new file_upload(); // my_upload muss auch bei URLs existieren da sonst Bedingung nach validation nicht funktioniert
if (isset($_FILES['upload'])){
    $my_upload->upload_dir = $CFG->curriculumdata_root.$file->getContextPath($context).$folders; //Set current uploaddir;
    $my_upload->extensions = array(".png", ".jpg", ".jpeg", ".gif", ".pdf", ".doc", ".docx", ".ppt", ".pptx", ".txt", ".rtf", ".bmp", ".tiff", ".tif", ".mpg", ".mpeg" , ".mpe", ".mp3", ".m4a", ".qt", ".mov", ".mp4", ".avi", ".aif", ".aiff", ".wav", ".zip", ".rar", ".mid", ".imscc", ".curriculum"); // allowed extensions
    $my_upload->rename_file = false;
    $my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
    $my_upload->the_file = str_replace(' ', '_', $_FILES['upload']['name']);
    $filename = $my_upload->the_file;
    while (file_exists($my_upload->upload_dir.$my_upload->the_file)){ // if file exists --> rename, add -1
        $pos = strrpos($my_upload->the_file, "."); 
        $my_upload->the_file = substr($my_upload->the_file, 0, $pos) . '-1' . substr($my_upload->the_file, $pos);  
    }
    //////////////// Todo Errorhandling ?
    $my_upload->http_error = $_FILES['upload']['error'];    
}

if ($my_upload->upload()) {//in datenbank eintragen
    $file->title                 = $title; 
    $file->description           = $description;
    $file->author                = $author;
    $file->license               = $license;
    $file->file_context          = $file_context;
    $file->context_id            = $file->getContextId($context);
    $file->creator_id            = $USER->id;
    $file->curriculum_id         = $curID;
    $file->terminal_objective_id = $terID;
    $file->enabling_objective_id = $enaID;

    $copy_link         = ' <input type="submit" id="closelink" name="Submit" value="Datei verwenden"/>';
    $file->filename    = str_replace(' ', '_', $my_upload->the_file);
    $file->type        = $my_upload->get_extension($my_upload->the_file);
    $file->path        = $folders;
    $file->id          = $file->add();
    $href_mail         = $CFG->access_file_url.'solutions/'.$folders.''.rawurlencode(str_replace(' ', '_', $my_upload->the_file));
    if ($CFG->thumbnails){ // Generate Thumbs // todo: var to define thumbs (sizes)
       generateThumbnail($my_upload->upload_dir, $my_upload->the_file, $context);
    }

    if ($context == "solution") { // --> upload of solution file
        $course = new Course(); 
        $teachers = $course->getTeacher($USER->id, $enabling_objective->curriculum_id); //get Teachers

        $mail = new Mail();
        for($i = 0; $i < count($teachers); ++$i) {
            $mail->sender_id    = $USER->id;
            $mail->receiver_id  = $teachers[$i]; //current Teacher
            $mail->subject      = 'Lösung eingereicht';
            $mail->message      = '<p>Zum Lernziel: <br> "'.$enabling_objective->enabling_objective.'" hat '.$USER->firstname.' '.$USER->lastname.' ('.$USER->username.') folgende Lösung eingereicht:<br>'; 
            $mail->message     .= '<link id="'.$file->id.'"></link>';
            $mail->message     .= '<accomplish id="'.$enabling_objective->id.'"></accomplish>';
            $mail->message     .= '</p>';
            $mail->postMail();
        }
    }
}
 //////////////// Errorbehandlung ?
$error = $my_upload->show_error_string();    
echo $file->id;
