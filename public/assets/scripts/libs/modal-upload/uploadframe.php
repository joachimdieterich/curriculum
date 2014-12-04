<?php

/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename uploadframe.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.08 13:26
 * @license: 
 * 
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/

include_once '../../../../../share/config.php'; //Läd die config.php
global $CFG, $PAGE, $USER;
include_once $_SERVER['DOCUMENT_ROOT'].$CFG->BASE_URL.'share/include.php';
include_once $_SERVER['DOCUMENT_ROOT'].$CFG->BASE_URL.'share/function.php';      //php-Funktionen includen

$file = new File();

$error = '';
$image = '';
$copy_link = '';

$data_dir = $CFG->BASE_URL.'curriculumdata/';          //data_dir für uploads

//Wenn eine Datei hochgeladen wird, werden die benötigten Variablen über versteckte Felder übergeben.
$POST_curID         = (isset($_POST['curID']) && trim($_POST['curID'] != '')        ? $_POST['curID'] : '-1'); //Default-Value für POST
$POST_terID         = (isset($_POST['terID']) && trim($_POST['terID'] != '')        ? $_POST['terID'] : '-1'); //Default-Value für POST
$POST_enaID         = (isset($_POST['enaID']) && trim($_POST['enaID'] != '')        ? $_POST['enaID'] : '-1'); //Default-Value für POST
$POST_targetID      = (isset($_POST['target']) && trim($_POST['target'] != '')      ? $_POST['target'] : 'myfile'); //Default-Value für POST
$POST_returnformat  = (isset($_POST['format']) && trim($_POST['format'] != '')      ? $_POST['format'] : '0'); //Default-Value für POST
$POST_multipleFiles = (isset($_POST['multiple']) && trim($_POST['multiple'] != '')  ? $_POST['multiple'] : 'false'); //Default-Value für POST


//Auslesen der ids aus der URL
$curriculum_id = (isset($_GET['curID']) && trim($_GET['curID'] != '') ? $_GET['curID'] : $POST_curID);
$terminal_objective_id = (isset($_GET['terID']) && trim($_GET['terID'] != '') ? $_GET['terID'] : $POST_terID);
$enabling_objective = new EnablingObjective();
$enabling_objective->id = (isset($_GET['enaID']) && trim($_GET['enaID'] != '') ? $_GET['enaID'] : $POST_enaID);
$enabling_objective->load();

//Parameter für die Rückgabe
$targetID = (isset($_GET['target']) && trim($_GET['target'] != '') ? $_GET['target'] : $POST_targetID); //Auslesen der TAG-ID in die 
$returnFormat = (isset($_GET['format']) && trim($_GET['format'] != '') ? $_GET['format'] : $POST_returnformat); //Rückgabeformat der Daten
$multipleFiles = (isset($_GET['multiple']) && trim($_GET['multiple'] != '') ? $_GET['multiple'] : $POST_multipleFiles); //Mehrfachauswahl möglich?


$context =      (isset($_GET['context']) && trim($_GET['context'] != '') ? $_GET['context'] : $_POST['context']);
$user_id =      (isset($_GET['userID']) && trim($_GET['userID'] != '') ? $_GET['userID'] : $_POST['userID']);
$token   =      (isset($_GET['token']) && trim($_GET['token'] != '') ? $_GET['token'] : $_POST['token']); //security check to prevent access without login

$upload_user = new User(); 
$upload_user->load('id', $user_id, true); //Load upload User data
$USER = $upload_user;           //Hack - $USER not defined but required on upload
$_SESSION['USER'] = $USER;      //Hack - $_SESSION['USER'] not defined but required on upload

/**
 * Security check based on username token and current ip to prevent access without login
 */
$authenticate = new Authenticate();
$authenticate->username = $upload_user->username;
$authenticate->getUser('username');
if (!$authenticate->check(getIp())){
    throw new CurriculumException('Unberechtigter Zugriff!');
}//security 

if (isset($context)) {
        $contextPath = $file->getContextPath($context);
        $extendCurriculumPath = ''; //Pfad zum Curriculumordner
        $extendUserPath = '';       //Pfad zum Benutzerorder
        $extendUploadPath = '';     //Pfad für den Upload 
        
        switch ($context) {
        case "userFiles":   $extendUploadPath = $upload_user->id.'/'; //siehe unten                
                            break;
        case "userView":    $extendUploadPath = $curriculum_id.'/'.$terminal_objective_id.'/'.$enabling_objective->id.'/'; //siehe unten                
                            break;  
        case "curriculum":  $extendUploadPath = $curriculum_id.'/'.$terminal_objective_id.'/'.$enabling_objective->id.'/';
                            break;
        case "avatar":      $extendUploadPath = $upload_user->id.'/'; //siehe unten                        
                            break;                
        default:            break;
        }
}

//Pfade
$extendUserPath = $file->getContextPath('userFiles').''.$upload_user->id.'/';      
         
if (isset($_POST['Submit'])) {
    switch ($_POST['Submit']) {
        case "Datei hochladen": 
                                $my_upload = new file_upload;
        
                                $my_upload->upload_dir = $_SERVER['DOCUMENT_ROOT'].$data_dir.$file->getContextPath($context).$extendUploadPath; //Set current uploaddir;
                                $my_upload->extensions = array(".png", ".jpg", ".jpeg", ".gif", ".pdf", ".doc", ".docx", ".ppt", ".pptx", ".txt", ".rtf", ".bmp", ".tiff", ".tif", ".mpg", ".mpeg" , ".mpe", ".mp3", ".m4a", ".qt", ".mov", ".mp4", ".avi", ".aif", ".aiff", ".wav", ".zip", ".rar", ".mid"); // allowed extensions
                                $my_upload->rename_file = false;
                                $my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
                                $my_upload->the_file = str_replace(' ', '_', $_FILES['upload']['name']);
                                $filename = $my_upload->the_file;
                                while (file_exists($my_upload->upload_dir.$my_upload->the_file)){ // if file exists --> rename, add -1
                                    $pos = strrpos($my_upload->the_file, "."); 
                                    $my_upload->the_file = substr($my_upload->the_file, 0, $pos) . '-1' . substr($my_upload->the_file, $pos);  
                                }
                                
                                $my_upload->http_error = $_FILES['upload']['error'];
                                
                                $gump = new Gump(); /* Validation */
                                        $gump->validation_rules(array(
                                        'title'     => 'required',
                                        'author'    => 'required', 
                                        'licence'   => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                            $v_error = $gump->get_readable_errors(); 
                                        } else {
                                            if ($my_upload->upload()) {
                                                    //$image = $my_upload->file_copy;

                                                    $copy_link = ' <input type="submit" id="closelink" name="Submit" value="Datei verwenden" onclick="self.parent.tb_remove();"/>';
                                                    $material_link = ' <input type="submit" id="materiallink" name="Submit" value="Datei hinzufügen" onclick="self.parent.tb_remove();"/>';

                                                    //in datenbank eintragen
                                                    $file->filename = str_replace(' ', '_', $my_upload->the_file);
                                                    $file->title = $_POST['title'];
                                                    $file->description = $_POST['description'];
                                                    $file->author = $_POST['author'];
                                                    $file->licence = $_POST['licence'];
                                                    $file->type = $my_upload->get_extension($my_upload->the_file);
                                                    $file->path = $extendUploadPath;
                                                    $file->context_id = $file->getContextId($context);
                                                    $file->creator_id = $upload_user->id;
                                                    $file->curriculum_id = $curriculum_id;
                                                    $file->terminal_objective_id = $terminal_objective_id;
                                                    $file->enabling_objective_id = $enabling_objective->id;
                                                    $my_upload->id = $file->add();

                                                    if ($context == "userView") { // --> upload of solution file
                                                        $course = new Course(); 
                                                        $teachers = $course->getTeacher($upload_user->id, $enabling_objective->curriculum_id); // get Teachers

                                                        $mail = new Mail();
                                                        for($i = 0; $i < count($teachers); ++$i) {
                                                            $mail->sender_id = $upload_user->id;
                                                            $mail->receiver_id = $teachers[$i]; //current Teacher
                                                            $mail->subject = $upload_user->firstname.' '.$upload_user->lastname.' ('.$upload_user->username.') hat eine Lösung eingereicht.';
                                                            $mail->message = $upload_user->firstname.' '.$upload_user->lastname.' ('.$upload_user->username.') hat zum Lernziel: <br> "'.$enabling_objective->enabling_objective.'" folgende Lösung eingereicht:<br> 
                                                                Link zur Lösung: <a target="_blank" href="'.$data_dir.'solutions/'.$extendUploadPath.''.str_replace(' ', '_', $my_upload->the_file).'"> Lösung öffnen...</a> <br> <br>
                                                                <p class="pointer" onclick="setAccomplishedObjectivesBySolution('.$teachers[$i].', '.$upload_user->id.', '.$enabling_objective->id.', 1)">Ziel freischalten</p><br>
                                                                <p class="pointer" onclick="setAccomplishedObjectivesBySolution('.$teachers[$i].', '.$upload_user->id.', '.$enabling_objective->id.', 0)">Ziel deaktivieren</p>'; 
                                                            $mail->postMail();
                                                        }
                                                    }
                                            } 
                                        }
                                $error = $my_upload->show_error_string();
                                break;
       case "URL einfügen":     
                                $gump = new Gump(); /* Validation */
                                        $gump->validation_rules(array(
                                        'title'     => 'required',
                                        'description'    => 'required',
                                        'url_author'    => 'required', 
                                        'url_licence'   => 'required'
                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if($validated_data === false) {/* validation failed */
                                            $v_error = $gump->get_readable_errors(); 
                                            $showurlForm = true;
                                        } else {
                                            if (filter_var($_POST['fileURL'], FILTER_VALIDATE_URL)){
                                            $file->filename = $_POST['fileURL']; //todo: doppelt gespeichert... muss noch optimiert werden
                                            $file->path = $_POST['fileURL'];     //todo: doppelt gespeichert... muss noch optimiert werden
                                            $file->type = '.url';
                                            $file->title = $_POST['title'];
                                            $file->description = $_POST['description'];
                                            $file->author = $_POST['url_author'];
                                            $file->licence = $_POST['url_licence'];
                                            $file->context_id = $file->getContextId($context);
                                            $file->creator_id = $upload_user->id;
                                            $file->curriculum_id = $curriculum_id;
                                            $file->terminal_objective_id = $terminal_objective_id;
                                            $file->enabling_objective_id = $enabling_objective->id;
                                            $file->add();
                                            
                                            if ($context == "userView") { // --> upload of solution file
                                                $course = new Course(); 
                                                $teachers = $course->getTeacher($upload_user->id, $enabling_objective->curriculum_id); // get Teachers

                                                $mail = new Mail();
                                                for($i = 0; $i < count($teachers); ++$i) {
                                                    $mail->sender_id = $upload_user->id;
                                                    $mail->receiver_id = $teachers[$i]; //current Teacher
                                                    $mail->subject = $upload_user->firstname.' '.$upload_user->lastname.' ('.$upload_user->username.') hat eine Lösung eingereicht.';
                                                    $mail->message = $upload_user->firstname.' '.$upload_user->lastname.' ('.$upload_user->username.') hat zum Lernziel: <br> "'.$enabling_objective->enabling_objective.'" folgende Lösung eingereicht:<br> 
                                                        Link zur Lösung: <a href="'.$file->path.'"> Lösung öffnen...</a> <br> <br>
                                                        <p class=" pointer" onclick="setAccomplishedObjectivesBySolution('.$teachers[$i].', '.$upload_user->id.', '.$enabling_objective->id.', 1)">Ziel freischalten</p><br>
                                                        <p class=" pointer" onclick="setAccomplishedObjectivesBySolution('.$teachers[$i].', '.$upload_user->id.', '.$enabling_objective->id.', 0)">Ziel deaktivieren</p>'; 
                                                    $mail->postMail();
                                                }
                                            }                        
                                                $PAGE->message[] = 'Material wurde erfolgreich hinzugefügt.';
                                                //Fenster ausblenden
                                                ?> <script type="text/javascript">
                                                self.parent.tb_remove();
                                                </script><?php
                                            } else {
                                            $error = 'Es wurde keine valide URL eingegeben (URL muss mit http:// oder https:// beginnen)';
                                            $showurlForm = true; //damit wieder die URL Form gezeigt wird
                                            }  
                                        }
                                break;
       
       default: break;
    }                              
}


?>

<html style="overflow:hidden !important;"">
<head>
<title>Image upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="thickbox.js"></script>
<script type="text/javascript" src="../../script.js"></script>
<link rel="stylesheet" href="../../../stylesheets/all.css" type="text/css" media="screen" />

<script type="text/javascript">

function previewFile(URL, POSTFIX, TITLE, DESCRIPTON, AUTHOR, LICENCE) {
   document.getElementById('img_FilePreview').src = URL; //Gibt kompletten Link aus  
   document.getElementById('div_FilePreview').style.display = 'block'; 
   document.getElementById(POSTFIX + 'p_author').innerHTML =  AUTHOR; 
   document.getElementById(POSTFIX + 'p_licence').innerHTML =  LICENCE;
   document.getElementById(POSTFIX + 'p_title').innerHTML =  TITLE; 
   document.getElementById(POSTFIX + 'p_description').innerHTML =  DESCRIPTON;  
   document.getElementById(POSTFIX + 'p_information').style.display = 'block'; 
   document.getElementById(POSTFIX + 'p_information').style.visibility = 'visible'; 
   
   
}

function exitpreviewFile(POSTFIX) {
   document.getElementById('div_FilePreview').style.display = 'none';  
   document.getElementById(POSTFIX + 'p_information').style.visibility = 'hidden';  
   
}   

//Funktion zum auslesen von Checkboxes
function iterateListControl(containerId,checkboxnameroot,targetID,returnFormat,multipleFiles){
 var containerRef = document.getElementById(containerId);
 var inputRefArray = containerRef.getElementsByTagName('input');
 var returnList = '';
 
 for (var i=0; i<inputRefArray.length; i++){
  var inputRef = inputRefArray[i];

  if ( inputRef.type.substr(0, 8) == 'checkbox' ){
   if ( inputRef.checked == true ) {
    if (returnList == '') {
        returnList = inputRef.id.substr(checkboxnameroot.length)
    } else {   
        returnList = returnList + ',' + inputRef.id.substr(checkboxnameroot.length);   //Kommagetrennte Liste mit den ausgewählten Dateien (Referenz ist die ID aus der files DB)
    }
   }
  }
 }

   // Aufbereiten der Rückgabedaten 
   switch (returnFormat) {
        case "0": var returnListArray = returnList.split(",");
                  var processedreturnListArray = '';
                  for (var i=0; i<returnListArray.length; i++) {
                      if (processedreturnListArray == '') {
                          processedreturnListArray = returnListArray[i]; //Gibt nur den Dateinamen aus
                      } else {
                        processedreturnListArray = processedreturnListArray + ',' + returnListArray[i];  //Gibt nur den Dateinamen aus
                        
                      }
                  }
                  returnList = processedreturnListArray;
                  break; //Es wird der Dateinamen zurückgegeben
            break; //Es sollen die IDs aus der DB zurückgegeben werden
        case "1": var returnListArray = returnList.split(",");
                  var processedreturnListArray = '';
                  for (var i=0; i<returnListArray.length; i++) {
                      if (processedreturnListArray == '') {
                          processedreturnListArray = document.getElementById('href_' + returnListArray[i]).innerHTML; //Gibt nur den Dateinamen aus
                      } else {
                        processedreturnListArray = processedreturnListArray + ',' + document.getElementById('href_' + returnListArray[i]).innerHTML;  //Gibt nur den Dateinamen aus
                      }
                  }
                  returnList = processedreturnListArray;
                  break; //Es wird der Dateinamen zurückgegeben
                  
        case "2": var returnListArray = returnList.split(",");
                  var processedreturnListArray = '';
                  for (var i=0; i<returnListArray.length; i++) {
                      if (processedreturnListArray == '') {
                        processedreturnListArray = document.getElementById('href_' + returnListArray[i]).href; //Gibt kompletten Link aus  
                      } else {
                        processedreturnListArray = processedreturnListArray + ',' + document.getElementById('href_' + returnListArray[i]).href;  //Gibt Kompletten Link aus
                      }
                  }
                  returnList = processedreturnListArray;
                  break; //Es wird die URL zurückgegeben
                  
        default: break;
   }
   switch (multipleFiles) {
        case "false": if (returnList.indexOf(',') != '-1'){
                      var index = returnList.indexOf(',');
                      var processedreturnList = returnList.slice(0,index);
                    } else {
                         processedreturnList = returnList;
                    }
                    break; // Es wird nur die erste Datei zurückgegeben
        case "true" : var processedreturnList = returnList;
                    break; // Es werden alle dateien zurückgegeben
        default: break;
   }
   
   
   // Auswahl an top.document weitergeben lassen
    $('#'+targetID, top.document).val(processedreturnList);
    $("#getFiles", top.document).val(processedreturnList); //zum Testen
   
    self.parent.tb_remove();
}


$(document).ready(function() { 
        
	$("#closelink").click(function() { //übergibt file_id an #myfile
                $('#'+'<?php echo $targetID; ?>', top.document).val('<?php if (isset($my_upload)){echo $my_upload->id;} ?>');}
        );
        $("#materiallink").click(function() { //übergibt den kompletten pfad mit datei an #myfile
		$('#'+'<?php echo $targetID; ?>', top.document).val('<?php 
                    if(isset($my_upload)) {
                        //echo $my_upload->upload_dir.$my_upload->the_file;
                        echo $my_upload->id;
                    } 
                    ?>');}
        );
        $("#uploadbtn").click(function() {document.getElementById('TB_progressBar').style.display = 'block';});
        
        $("#fileuplbtn").click(function() {
            document.getElementById('div_fileURL').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileupload').style.display = 'block';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'none';
        });   
        
        $("#fileURLbtn").click(function() {
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileURL').style.display = 'block';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'none';
        });
        
        $("#filelastuploadbtn").click(function() {
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'block';
            document.getElementById('div_fileURL').style.display = 'none';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'none';
        });
        $("#myfilesbtn").click(function() {
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileURL').style.display = 'none';
            document.getElementById('div_myfiles').style.display = 'block';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'none';
        });
        $("#curriculumfilesbtn").click(function() {
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileURL').style.display = 'none';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'block';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'none';
        });
        $("#solutionfilesbtn").click(function() {
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileURL').style.display = 'none';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'block';
            document.getElementById('div_avatarfiles').style.display = 'none';
        });
        $("#avatarfilesbtn").click(function() {
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileURL').style.display = 'none';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'block';
        });
        <?php    //wenn url fehlerhaft wird From wieder angezeigt. 
    if (isset($showurlForm)){
        if ($showurlForm == true){?>
            document.getElementById('div_fileupload').style.display = 'none';
            document.getElementById('div_filelastupload').style.display = 'none';
            document.getElementById('div_fileURL').style.display = 'block';
            document.getElementById('div_myfiles').style.display = 'none';
            document.getElementById('div_curriculumfiles').style.display = 'none';
            document.getElementById('div_solutionfiles').style.display = 'none';
            document.getElementById('div_avatarfiles').style.display = 'none';
        <?php
        }
    }
    
?>
 
});
</script>
</head>
<body id="uploadframe_body" name="Anker" >
<div class="messageboxClose" onclick="self.parent.tb_remove();"></div>    
<div id="uploadframe" class="contentheader">Dateiauswahl</div>
        <div id="fileupload">
            <div class="floatleft uploadframe_menu">    
            <nav>
                <ul > <!-- Menu -->
                    <!--<li><p><?php echo 'test'.$multipleFiles; ?></p></li>-->
                    <?php if (checkCapabilities('file:upload', $upload_user->role_id, false)){ //don't throw exeption!?>
                    <li><p><a id="fileuplbtn" href="#Anker">Datei hochladen</a></p></li>
                    <?php }
                        
                    if ($context != 'avatar'){ // nur anzeigen wenn nicht avatar
                        if (checkCapabilities('file:uploadURL', $upload_user->role_id, false)){ //don't throw exeption!?>
                            <li><p><a id="fileURLbtn" href="#Anker">Datei-URL verknüpfen</a></p></li>
                        <?php }
                        if (checkCapabilities('file:lastFiles', $upload_user->role_id, false)){ //don't throw exeption!?>    
                            <li><p><a id="filelastuploadbtn" href="#Anker">Letzte Dateien</a></p></li>
                        <?php } 
                        if ($context == 'curriculum'){ // nur anzeigen wenn in der Curriculumansicht
                            if (checkCapabilities('file:curriculumFiles', $upload_user->role_id, false)){ //don't throw exeption!?>    
                            <li><p><a id="curriculumfilesbtn" href="#Anker">Aktueller Lehrplan</a></p></li>
                        <?php }
                        } 
                        if ($context == 'userView'){ // nur anzeigen wenn in der Curriculumansicht
                            if (checkCapabilities('file:solution', $upload_user->role_id, false)){ //don't throw exeption!?>    
                            <li><p><a id="solutionfilesbtn" href="#Anker">Meine Abgaben</a></p></li>
                        <?php }
                        }  
                        if (checkCapabilities('file:myFiles', $upload_user->role_id, false)){ //don't throw exeption!?>        
                            <li><p><a id="myfilesbtn" href="#Anker">Meine Dateien</a></p></li>
                    <?php }
                    }
                        if (checkCapabilities('file:myAvatars', $upload_user->role_id, false)){ //don't throw exeption!?>        
                            <li><p><a id="avatarfilesbtn" href="#Anker">Meine Avatare</a></p></li>
                        <?php }?>
                </ul>
                <div id="div_FilePreview" style="display:none;">
                    <img id="img_FilePreview" src="" alt="Vorschau">
                </div>
            </nav>
                <div style="clear:both;"></div>    
            </div>          
            
            <!--FileUpload div-->
            <div id="div_fileupload"  class="floatright verticalSeperator">
                <form action="uploadframe.php" method="post" enctype="multipart/form-data">
                <p><input name="userID" type="hidden" value="<?php echo $upload_user->id; ?>" /></p>
                <p><input name="context" type="hidden" value="<?php echo $context; ?>" /></p> <!-- context = von wo wird das Uploadfenster aufgerufen-->
		<p><input name="curID" type="hidden" value="<?php echo $curriculum_id; ?>" /></p>
		<p><input name="terID" type="hidden" value="<?php echo $terminal_objective_id; ?>" /></p>
		<p><input name="enaID" type="hidden" value="<?php echo $enabling_objective->id; ?>" /></p>
		<p><input name="target" type="hidden" value="<?php echo $targetID; ?>" /></p>
		<p><input name="format" type="hidden" value="<?php echo $returnFormat; ?>" /></p>
		<p><input name="multiple" type="hidden" value="<?php echo $multipleFiles; ?>" /></p>
                <p><input name="token" type="hidden" value="<?php echo $token; ?>" /></p>
                <p><label>Titel*: </label><input  id="titel" name="title" /></p>
                <?php
                if (isset($v_error['title']['message'][0])){
                    echo $v_error['title']['message'][0];
                }
                ?>
                <p><label>Beschreibung: </label><input  id="description" name="description" /></p>
                <?php
                if (isset($v_error['description']['message'][0])){
                    echo $v_error['description']['message'][0];
                }
                ?>
                <p><label>Author*: </label><input  id="author" name="author" value="<?php echo $upload_user->firstname.' '.$upload_user->lastname;?>"/></p>
                <?php
                if (isset($v_error['author']['message'][0])){
                    echo $v_error['author']['message'][0];
                }
                ?>
                <p><label>Lizenz: </label><select id="licence" name="licence" class="centervertical">
                                        <option value="1" data-skip="1">Sonstige</option>
                                        <option value="2" data-skip="1" selected>Alle Rechte vorbehalten</option>
                                        <option value="3" data-skip="1">Public Domain</option>
                                        <option value="4" data-skip="1">CC</option>
                                        <option value="5" data-skip="1">CC - keine Bearbeitung</option>;
                                        <option value="6" data-skip="1">CC - keine kommerzielle Nutzung - keine Bearbeitung</option>;
                                        <option value="7" data-skip="1">CC - keine kommerzielle Nutzung</option>;
                                        <option value="8" data-skip="1">CC - keine kommerzielle Nutzung - Weitergabe unter gleichen Bedingungen</option>;
                                        <option value="9" data-skip="1">CC - Weitergabe unter gleichen Bedingungen</option>;
                                  </select></p></p>
                <?php
                if (isset($v_error['licence']['message'][0])){
                    echo $v_error['licence']['message'][0];
                } 
               ?>
                <p><input name="upload" type="file" size="15" />
                <input id="uploadbtn" type="submit" name="Submit" value="Datei hochladen" />
		</form>
                <p id='TB_progressBar' style="display:none;"><img src="<?php echo $CFG->BASE_URL.'public/assets/images/basic/loadingAnimation.gif' ?>"/></p>
		<p class="text ">&nbsp;<?php echo $error; ?></p>
                <div class="uploadframe_footer">
                    <?php if ($context == 'curriculum'){ // nur anzeigen wenn in der Curriculumansicht
                            if (isset($material_link)) { //Verhindert Fehlermeldung
                                echo $material_link;
                                } 
                            } else {
                            echo $copy_link;
                    }?>
                </div>
            </div>
            
            <!--FileURL div-->
            <div id="div_fileURL" class="floatright verticalSeperator" style="display:none;">
                <form action="uploadframe.php" method="post" enctype="multipart/form-data">
                    <p><input name="userID" type="hidden" value="<?php echo $upload_user->id; ?>" /></p>
                    <p><input name="context" type="hidden" value="<?php echo $context; ?>" /></p> <!-- context = von wo wird das Uploadfenster aufgerufen-->
                    <p><input name="curID" type="hidden" value="<?php echo $curriculum_id; ?>" /></p>
                    <p><input name="terID" type="hidden" value="<?php echo $terminal_objective_id; ?>" /></p>
                    <p><input name="enaID" type="hidden" value="<?php echo $enabling_objective->id; ?>" /></p>
                    <p><input name="token" type="hidden" value="<?php echo $token; ?>" /></p>
                    <p><label>Titel: </label><input  id="titel" name="title" /></p>
                    <?php
                    if (isset($v_error['title']['message'][0])){
                        echo $v_error['title']['message'][0];
                    }
                    ?>
                    <p><label>Beschreibung: </label><input  id="description" name="description" /></p>
                    <?php
                    if (isset($v_error['description']['message'][0])){
                        echo $v_error['description']['message'][0];
                    }
                    ?>
                    <p><label>Author*: </label><input  id="url_author" name="url_author" value="<?php echo $upload_user->firstname.' '.$upload_user->lastname;?>"/></p>
                <?php
                if (isset($v_error['url_author']['message'][0])){
                    echo $v_error['url_author']['message'][0];
                }
                ?>
                <p><label>Lizenz: </label><select id="url_licence" name="url_licence" class="centervertical">
                                        <option value="1" data-skip="1">Sonstige</option>
                                        <option value="2" data-skip="1" selected>Alle Rechte vorbehalten</option>
                                        <option value="3" data-skip="1">Public Domain</option>
                                        <option value="4" data-skip="1">CC</option>
                                        <option value="5" data-skip="1">CC - keine Bearbeitung</option>;
                                        <option value="6" data-skip="1">CC - keine kommerzielle Nutzung - keine Bearbeitung</option>;
                                        <option value="7" data-skip="1">CC - keine kommerzielle Nutzung</option>;
                                        <option value="8" data-skip="1">CC - keine kommerzielle Nutzung - Weitergabe unter gleichen Bedingungen</option>;
                                        <option value="9" data-skip="1">CC - Weitergabe unter gleichen Bedingungen</option>;
                                  </select></p></p>
                <?php
                if (isset($v_error['url_licence']['message'][0])){
                    echo $v_error['url_licence']['message'][0];
                } 
               ?>
                    <p>URL:</p>
                    <p><input class="inputlarge" name="fileURL" type="input" /></p>
                    <p><input  type="submit" name="Submit" value="URL einfügen"  /></p>
		</form>
		<p class="text">&nbsp;<?php echo $error; ?></p>
                <p><?php echo $copy_link; ?></p>
                <div class="uploadframe_footer"></div>
            </div>
            
            <!--FileLastUpload div-->
            <div id="div_filelastupload" class="floatright verticalSeperator" style="display:none;">
                <?php 
                $files = $file->getFiles('user', $upload_user->id);
                renderList('uploadframe.php', $files, $data_dir, '_filelastupload', $targetID, $returnFormat, $multipleFiles);  //Rendert die Thumbnailliste 
                ?>
            </div>
            
            <!--curriculumfiles div-->
            <div id="div_curriculumfiles" class="floatright verticalSeperator" style="display:none;">
                <?php 
                $files = $file->getFiles('curriculum', $curriculum_id);
                renderList('uploadframe.php', $files, $data_dir, '_curriculumfiles', $targetID, $returnFormat, $multipleFiles);  //Rendert die Thumbnailliste 
                ?>
            </div>
            
            <!--solutionfiles div-->
            <div id="div_solutionfiles" class="floatright verticalSeperator" style="display:none;">
                <?php 
                $files = $file->getFiles('solution', $curriculum_id);
                renderList('uploadframe.php', $files, $data_dir, '_solutionfiles', $targetID, $returnFormat, $multipleFiles);  //Rendert die Thumbnailliste 
                ?>    
            </div>
            
            <!--avatarfiles div-->
            <div id="div_avatarfiles" class="floatright verticalSeperator" style="display:none;">
                <?php 
                $files = $file->getFiles('avatar', $upload_user->id);
                renderList('uploadframe.php', $files, $data_dir, '_avatarfiles', $targetID, $returnFormat, $multipleFiles);  //Rendert die Thumbnailliste
                ?>
            </div>
            
            <!--myfiles div-->
            <div id="div_myfiles" class="floatright verticalSeperator" style="display:none;">
                <?php 
                $files = $file->getFiles('userfiles', $upload_user->id);
                renderList('uploadframe.php', $files, $data_dir, '_myfiles', $targetID, $returnFormat, $multipleFiles);  //Rendert die Thumbnailliste
                ?>
            </div>
            <div style="clear:both;"></div>
        </div>  

</body>
</html>
