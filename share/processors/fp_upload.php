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
global $CFG, $PAGE, $USER, $LOG;
if (!isset($_SESSION['USER'])){ die(); }    // logged in?
$USER       = $_SESSION['USER'];

/*foreach ($_POST as $key => $value) {
    $$key = $value;
}*/

$file       = new File();

/* set defaults*/
$title              = null; 
$description        = null;
$context            = null; 
$author             = $USER->firstname.' '.$USER->lastname;
$publisher          = null;
$publishingCompany  = null;
$place              = null;
$date               = null;
$action             = 'upload';
$file_context       = 1;
$license            = 2;
$error              = '';
$image              = '';
$copy_link          = '';
$curID              = NULL;
$terID              = NULL;
$enaID              = NULL;
$ref_id             = NULL; 
$fileURL            = NULL;

//$v_error  = false;
/* get url parameters */
foreach ($_GET  as $key => $value) { $$key = $value; } 
/* get form data */
foreach ($_POST as $key => $value) { $$key = $value; }

// Pfade
switch ($context) {
case "userFiles":   
case "avatar":
case "editor":              $folders = $USER->id.'/';                       // set upload-folder 
                            break;
case "curriculum":          // see case enabling_objective and terminal_objective
                            $curID   = $ref_id;
                            $folders = $ref_id.'/';
      break;
case "enabling_objective":  $context = 'curriculum';                        // ! set context to curriculum to get right context_id, enabling_objective is used to load curID and terID
case "solution":            $eo      = new EnablingObjective();     
                            $eo->id  = $ref_id;
                            $eo->load();                                    // load ids folders
                            $curID = $eo->curriculum_id;
                            $terID = $eo->terminal_objective_id;
                            $enaID = $eo->id;
                            $folders = $curID.'/'.$terID.'/'.$enaID.'/';    // set upload-folder 
                            break;       
case "terminal_objective":  $to      = new TerminalObjective();     
                            $to->id  = $ref_id;
                            $to->load();                                    // load ids folders
                            $curID = $to->curriculum_id;
                            $terID = $to->id;
                            $folders = $curID.'/'.$terID.'/';               // set upload-folder 
                            $context = 'curriculum';                        // ! set context to curriculum to get right context_id, terminal_objective is used to load curID
                            break;                 

case "task":
case "badge":               
case "backup":              $folders = '/';                                  // siehe unten                        
    break;  
case "institution":         $folders = $ref_id.'/';                          //ref_id == institution_id
    break;  
default:                    $folders = '';    
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

if ($my_upload->upload() OR filter_var($fileURL, FILTER_VALIDATE_URL)) {//in datenbank eintragen
    $file->title                 = $title; 
    $file->description           = $description;
    $file->author                = $author;
    $file->publisher             = $publisher;
    $file->publishingCompany     = $publishingCompany;
    $file->place                 = $place;
    $file->date                  = $date;
    $file->license               = $license;
    $file->file_context          = $file_context;
    $file->context_id            = $file->getContextId($context);
    //$file->creator_id            = $USER->id;
    $file->curriculum_id         = $curID;  //replace with refid
    $file->terminal_objective_id = $terID;  //replace with refid
    $file->enabling_objective_id = $enaID;  //replace with refid
    $file->reference_id          = $ref_id;
    
    //error_log("Feldausgabe".$title);
    //error_log(json_encode($file));
    switch ($action) {
        case 'upload':  $copy_link         = ' <input type="submit" id="closelink" name="Submit" value="Datei verwenden"/>';
                        $file->filename    = str_replace(' ', '_', $my_upload->the_file);
                        $file->type        = $my_upload->get_extension($my_upload->the_file);
                        $file->path        = $folders;
                        $file->id          = $file->add();
                        $href_mail         = $CFG->access_file_url.'solutions/'.$folders.''.rawurlencode(str_replace(' ', '_', $my_upload->the_file));
                        if ($CFG->settings->thumbnails){ // Generate Thumbs // todo: var to define thumbs (sizes)
                           generateThumbnail($my_upload->upload_dir, $my_upload->the_file, $context);
                        }
            break;
        case 'url':     $file->filename    = $fileURL; //todo: doppelt gespeichert... muss noch optimiert werden
                        $file->path        = $fileURL; //todo: doppelt gespeichert... muss noch optimiert werden
                        $file->type        = '.url';
                        $file->id          = $file->add();
                        $href_mail         = $file->path;
            break;
        default:
            break;
    }

    if ($context == "solution") { // --> upload of solution file
        $course                 = new Course(); 
        $enabling_objective     = new EnablingObjective();
        $enabling_objective->id = $file->enabling_objective_id;
        $enabling_objective->load();
        $teachers               = $course->getTeacher($USER->id, $enabling_objective->curriculum_id); //get Teachers
        
        $token                  = getToken();
        $enabling_objective->setAccomplishedStatus('student', $USER->id, $USER->id, '1x', $token);     //set setAccomplishedStatus with token    
        $mail = new Mail();
        for($i = 0; $i < count($teachers); $i++) {
            $mail->sender_id    = $USER->id;
            $mail->receiver_id  = $teachers[$i]; //current Teacher
            $mail->subject      = $CFG->app_title.': '.$USER->firstname.' '.$USER->lastname.' ('.$USER->username.') hat eine Lösung eingereicht';
            $mail->message      = '<p>Zur Kompetenz: <strong>'.$enabling_objective->enabling_objective.'</strong> hat '.$USER->firstname.' '.$USER->lastname.' ('.$USER->username.') folgende Lösung eingereicht:<br>'; 
            if ($CFG->settings->messaging == 'email'){                          // if email messaging is activated
                $file->load($file->id);
                $mail->email->addAttachment($CFG->curriculumdata_root.$file->full_path, $file->filename);   //Add solution to email 
                $mail->email->CharSet = 'UTF-8';
                $u = new User();
                $u->load('id',$mail->receiver_id, false);
                $mail->email->addAddress($u->email); // Add a recipient
                $mail->email->isHTML(true);                                     // Set email format to HTML
                $mail->email->Subject = $mail->subject;
                $mail->email->Body    = $mail->message;
                $mail->email->Body   .= Render::accCheckboxes(array('id' => $enabling_objective->id, 'student' => $mail->sender_id, 'teacher' => $mail->receiver_id, 'link' => false, 'email' => true, 'token' => $token)).'</p>';
                $mail->email->AltBody = strip_tags($mail->message);
                if (!$mail->email->send()) {
                    error_log('Message could not be sent.');
                    error_log('Mailer Error: ' . $mail->email->ErrorInfo);
                } else {
                    return true; 
                }
            } else { // use internal messaging
                $mail->message     .= '<link id="'.$file->id.'"></link>';
                $mail->message     .= '<accomplish id="'.$enabling_objective->id.'"></accomplish>';
                $mail->message     .= '</p>';
                $mail->postMail();
            }
        }
    }
}
 //////////////// todo: Errorbehandlung 
$error = $my_upload->show_error_string(); 
echo $file->id;
