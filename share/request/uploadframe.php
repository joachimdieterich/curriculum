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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/

include_once '../setup.php'; //Läd alle benötigten Dateien
global $CFG, $PAGE, $USER, $LOG;

$file = new File();
$error = '';
$image = '';
$copy_link = '';
$v_error = false;


//Wenn eine Datei hochgeladen wird, werden die benötigten Variablen über versteckte Felder übergeben.
$POST_curID         = (isset($_POST['curID']) && trim($_POST['curID'] != '')        ? $_POST['curID'] : '-1'); //Default-Value für POST
$POST_terID         = (isset($_POST['terID']) && trim($_POST['terID'] != '')        ? $_POST['terID'] : '-1'); //Default-Value für POST
$POST_enaID         = (isset($_POST['enaID']) && trim($_POST['enaID'] != '')        ? $_POST['enaID'] : '-1'); //Default-Value für POST
$POST_targetID      = (isset($_POST['target']) && trim($_POST['target'] != '')      ? $_POST['target'] : 'myfile'); //Default-Value für POST
$POST_returnformat  = (isset($_POST['format']) && trim($_POST['format'] != '')      ? $_POST['format'] : '0'); //Default-Value für POST
$POST_multipleFiles = (isset($_POST['multiple']) && trim($_POST['multiple'] != '')  ? $_POST['multiple'] : 'false'); //Default-Value für POST
$POST_context       = (isset($_POST['context']) && trim($_POST['context'] != '')    ? $_POST['context'] : ''); //Default-Value für POST

//Auslesen der ids aus der URL
$curriculum_id          = (isset($_GET['curID']) && trim($_GET['curID'] != '')      ? $_GET['curID'] : $POST_curID);
$terminal_objective_id  = (isset($_GET['terID']) && trim($_GET['terID'] != '')      ? $_GET['terID'] : $POST_terID);
$enabling_objective     = new EnablingObjective();
$enabling_objective->id = (isset($_GET['enaID']) && trim($_GET['enaID'] != '')      ? $_GET['enaID'] : $POST_enaID);
$enabling_objective->load();
//Parameter für die Rückgabe
$targetID       = (isset($_GET['target']) && trim($_GET['target'] != '')            ? $_GET['target'] : $POST_targetID); //Auslesen der TAG-ID in die 
$returnFormat   = (isset($_GET['format']) && trim($_GET['format'] != '')            ? $_GET['format'] : $POST_returnformat); //Rückgabeformat der Daten
$multipleFiles  = (isset($_GET['multiple']) && trim($_GET['multiple'] != '')        ? $_GET['multiple'] : $POST_multipleFiles); //Mehrfachauswahl möglich?
$context        = (isset($_GET['context']) && trim($_GET['context'] != '')          ? $_GET['context'] : $POST_context);

/* Security check based on token to prevent access without login */
$USER = $_SESSION['USER'];                  //Hack - $USER not defined but required on 

if (isset($context)) {
        $contextPath = $file->getContextPath($context);
        switch ($context) {
        case "userFiles":   $extendUploadPath = $USER->id.'/'; //siehe unten                
                            break;
        case "userView":    $extendUploadPath = $curriculum_id.'/'.$terminal_objective_id.'/'.$enabling_objective->id.'/'; //siehe unten                
                            break;  
        case "curriculum":  if ($enabling_objective->id == -1){
                                $extendUploadPath = $curriculum_id.'/'.$terminal_objective_id.'/'; // Dateien die zum Thema gehören
                            } else {
                                $extendUploadPath = $curriculum_id.'/'.$terminal_objective_id.'/'.$enabling_objective->id.'/'; // Dateien die zu einem Ziel gehören
                            }
                            break;
        case "avatar":
        case "editor":      $extendUploadPath = $USER->id.'/'; //siehe unten                        
                            break;
        case "badge":       $extendUploadPath = '/'; //siehe unten                        
            break;  
        case "institution": $extendUploadPath = $curriculum_id.'/'; //$curriculum_id ist in diesem Fall institution_id                         
            break;  
        default:            $extendUploadPath = '';    
            break;
        }
}

//Pfade
$extendUserPath = $file->getContextPath('userFiles').''.$USER->id.'/';      
         
if (isset($_POST['Submit'])) {
    $my_upload = new file_upload; // my_upload muss auch bei URLs existieren da sonst Bedingung nach validation nicht funktioniert
    if ($_POST['Submit'] == 'Datei hochladen'){

        $my_upload->upload_dir = $CFG->curriculumdata_root.$file->getContextPath($context).$extendUploadPath; //Set current uploaddir;
        $my_upload->extensions = array(".png", ".jpg", ".jpeg", ".gif", ".pdf", ".doc", ".docx", ".ppt", ".pptx", ".txt", ".rtf", ".bmp", ".tiff", ".tif", ".mpg", ".mpeg" , ".mpe", ".mp3", ".m4a", ".qt", ".mov", ".mp4", ".avi", ".aif", ".aiff", ".wav", ".zip", ".rar", ".mid", ".imscc", ".curriculum"); // allowed extensions
        $my_upload->rename_file = false;
        $my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
        $my_upload->the_file = str_replace(' ', '_', $_FILES['upload']['name']);
        $filename = $my_upload->the_file;
        while (file_exists($my_upload->upload_dir.$my_upload->the_file)){ // if file exists --> rename, add -1
            $pos = strrpos($my_upload->the_file, "."); 
            $my_upload->the_file = substr($my_upload->the_file, 0, $pos) . '-1' . substr($my_upload->the_file, $pos);  
        }
        $my_upload->http_error = $_FILES['upload']['error'];    
    }

    $gump = new Gump();                         /* validation */
    $_POST = $gump->sanitize($_POST);           //sanitize $_POST
    $gump->validation_rules(array(
    /*'title'       => 'required',
    'description' => 'required', */   
    'author'      => 'required|max_len,100', 
    'licence'     => 'required|integer'
    ));
    $validated_data = $gump->run($_POST);
    if($validated_data === false) {             /* validation failed */
        $v_error = $gump->get_readable_errors(); 
        if ($_POST['Submit'] == 'URL einfügen'){$showurlForm = true;}
    } else {
        if ($my_upload->upload() OR filter_var($_POST['fileURL'], FILTER_VALIDATE_URL)) {//in datenbank eintragen
                $file->title            = $_POST['title']; 
                $file->description      = $_POST['description'];
                $file->author           = $_POST['author'];
                $file->licence          = $_POST['licence'];
                $file->file_context     = $_POST['file_context'];
                $file->context_id = $file->getContextId($context);
                $file->creator_id = $USER->id;
                $file->curriculum_id = $curriculum_id;
                $file->terminal_objective_id = $terminal_objective_id;
                $file->enabling_objective_id = $enabling_objective->id;

                 if ($_POST['Submit'] == 'Datei hochladen') {
                     $copy_link         = ' <input type="submit" id="closelink" name="Submit" value="Datei verwenden"/>';
                     $file->filename    = str_replace(' ', '_', $my_upload->the_file);
                     $file->type        = $my_upload->get_extension($my_upload->the_file);
                     $file->path        = $extendUploadPath;
                     $my_upload->id     = $file->add();
                     $file->id          = $my_upload->id; // doppelt $my_upload->id im code ersetzen.
                     $href_mail         = $CFG->access_file_url.'solutions/'.$extendUploadPath.''.rawurlencode(str_replace(' ', '_', $my_upload->the_file));
                     if ($CFG->thumbnails){ // Generate Thumbs --> evtl besser in file.class.php
                        generateThumbnail($my_upload->upload_dir, $my_upload->the_file, $context);
                     }
                 } else {
                     $file->filename    = $_POST['fileURL']; //todo: doppelt gespeichert... muss noch optimiert werden
                     $file->path        = $_POST['fileURL']; //todo: doppelt gespeichert... muss noch optimiert werden
                     $file->type        = '.url';
                     $file->add();
                     $href_mail         = $file->path;
                 }

                if ($context == "userView") { // --> upload of solution file
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
                if ($_POST['Submit'] == 'URL einfügen'){?> 
                    <script type="text/javascript">self.parent.tb_remove();</script> <?php //Fenster ausblenden
                }
        } else {
           $error = 'Es wurde keine valide URL eingegeben (URL muss mit http:// oder https:// beginnen)';
           $showurlForm = true; //damit wieder die URL Form gezeigt wird 
        }
    }
    $error = $my_upload->show_error_string();    
}?>

<html style="overflow:hidden !important;">
<head>
<title>Image upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../../public/assets/scripts/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="../../public/assets/scripts/script.js"></script>
<script type="text/javascript" src="../../public/assets/scripts/uploadframe.js"></script>
<link rel="stylesheet" href="../../public/assets/stylesheets/all.css" type="text/css" media="screen" />

<script type="text/javascript">
$(document).ready(function() { 
    $("#closelink").click(function() { //übergibt datei an target wenn neue datei hochgeladen wird. 
        if (((typeof top.tinyMCE.activeEditor === 'object') && top.tinyMCE.activeEditor !== null) && '<?php echo $context; ?>' === 'editor'){ //Wenn Editor vorhanden 
            top.tinyMCE.activeEditor.insertContent('<img src="<?php if (isset($my_upload)){echo $CFG->access_file_url.$file->getContextPath($context).$extendUploadPath.$my_upload->the_file;} ?>">');
        } else {    
            $('#'+'<?php echo $targetID; ?>', top.document).val('<?php if (isset($my_upload)){echo $my_upload->id;} ?>');    
        }
        self.parent.tb_remove();
    });

    <?php    //wenn url fehlerhaft wird From wieder angezeigt. 
    if (isset($showurlForm)){
        if ($showurlForm == true){?>
            document.getElementById('div_fileuploadbtn').style.display = 'none';
            document.getElementById('div_fileURLbtn').style.display = 'block';
        <?php
        }
    }?>
});
</script>
</head>
<body id="uploadframe_body" name="Anker" >
<div class="uploadframeClose" onclick="self.parent.tb_remove();"></div>    
<div id="uploadframe" class="contentheader">Dateiauswahl</div>
    <div class="floatleft unav_menu">    
        <nav>
            <ul > <!-- Menu --><?php 
                $values = array (0 => array('capabilities' =>  'file:upload',           'id' =>  'fileuplbtn',          'name' => 'Datei hochladen'), 
                                 1 => array('capabilities' =>  'file:uploadURL',        'id' =>  'fileURLbtn',          'name' => 'Datei-URL verknüpfen'), 
                                 2 => array('capabilities' =>  'file:lastFiles',        'id' =>  'filelastuploadbtn',   'name' => 'Letzte Dateien'), 
                                 3 => array('capabilities' =>  'file:curriculumFiles',  'id' =>  'curriculumfilesbtn',  'name' => 'Aktueller Lehrplan'), 
                                 4 => array('capabilities' =>  'file:solution',         'id' =>  'solutionfilesbtn',    'name' => 'Meine Abgaben'), 
                                 5 => array('capabilities' =>  'file:myFiles',          'id' =>  'myfilesbtn',          'name' => 'Meine Dateien'), 
                                 6 => array('capabilities' =>  'file:myAvatars',        'id' =>  'avatarfilesbtn',      'name' => 'Meine Profilbilder')
                );
                foreach($values as $value){
                    if (checkCapabilities($value['capabilities'], $USER->role_id, false)){ //don't throw exeption!?>
                        <li><p><a id="<?php echo $value['id']?>" class="unav" href="#Anker"><?php echo $value['name']?></a></p></li><?php 
                    }
                } ?>
            </ul>
            <div id="div_FilePreview" style="display:none;">
                <img id="img_FilePreview" src="" alt="Vorschau">
            </div>
        </nav>
        <div style="clear:both;"></div>    
    </div>      
    <div id="uf_content" class="floatleft verticalSeperator"> 
        <!--FileUpload div-->
        <div id="div_file_url"  class="floatleft ">
            <form action="uploadframe.php" method="post" enctype="multipart/form-data">
            <p><input name="context" type="hidden" value="<?php echo $context; ?>" /></p> <!-- context = von wo wird das Uploadfenster aufgerufen-->
            <p><input name="curID" type="hidden" value="<?php   echo $curriculum_id; ?>" /></p>
            <p><input name="terID" type="hidden" value="<?php   echo $terminal_objective_id; ?>" /></p>
            <p><input name="enaID" type="hidden" value="<?php   echo $enabling_objective->id; ?>" /></p>
            <p><label>Titel*: </label><input  id="titel" name="title" value="<?php if (isset($_POST['title'])){echo $_POST['title'];}?>"/></p>
            <?php validate_msg($v_error, 'title'); ?>
            <p><label>Beschreibung*: </label><input  id="description" name="description" value="<?php if (isset($_POST['description'])){echo $_POST['description'];}?>"/></p>
            <?php validate_msg($v_error, 'description'); ?>
            <p><label>Author*: </label><input  id="author" name="author" value="<?php if (isset($_POST['author'])){echo $_POST['author'];} else {echo $USER->firstname.' '.$USER->lastname;}?>"/></p>
            <?php validate_msg($v_error, 'author');
            $licence = $file->getLicence();
            if (!isset($_POST['licence'])){
                $select = 2; 
            } else {
                $select = $_POST['licence'];
            } // alle Rechte vorbehalten
            renderSelect('Lizenz:', 'licence', $licence, $select);
            validate_msg($v_error, 'licence'); ?>  
            <?php validate_msg($v_error, 'author');
            
            $level = $file->getFileContext();
            if (!isset($_POST['file_context'])){$select = 1; }  // alle Rechte vorbehalten
            renderSelect('Freigabe-Level:', 'file_context', $level, $select);
            validate_msg($v_error, 'file_context'); ?>  
            
            <span id="div_fileuplbtn" style="display:block;">    <!-- Fileupload-->
                <p><input name="target" type="hidden" value="<?php  echo $targetID; ?>" /></p>
                <p><input name="format" type="hidden" value="<?php  echo $returnFormat; ?>" /></p>
                <p><input name="multiple" type="hidden" value="<?php echo $multipleFiles; ?>" /></p>
                <p><input name="upload" type="file" size="15" />
                <input id="uploadbtn" type="submit" name="Submit" value="Datei hochladen" />
                <p id='TB_progressBar' style="display:none;"><img src="<?php echo $CFG->base_url.'public/assets/images/loadingAnimation.gif' ?>"/></p>
            </span>
            <span id="div_fileURLbtn" style="display:none;">     <!-- URLupload-->
                <p>URL:</p>
                <p><input type="input" class="inputlarge" name="fileURL"  value="<?php if (isset($_POST['fileURL'])){echo $_POST['fileURL'];}?>"/></p>
                <p><input type="submit" name="Submit" value="URL einfügen"  /></p>
            </span>
            </form>    
            <p class="text ">&nbsp;<?php echo $error; ?></p>
            <div class="uploadframe_footer"><?php echo $copy_link; ?></div>
        </div>

        <?php // Rendert Thumbnaillisten
        renderList('uploadframe.php', 'user',       $CFG->access_file, '_filelastuploadbtn',   $targetID, $returnFormat, $multipleFiles, $USER->id);        //FileLastUpload div
        renderList('uploadframe.php', 'curriculum', $CFG->access_file, '_curriculumfilesbtn',  $targetID, $returnFormat, $multipleFiles, $curriculum_id);   //curriculumfiles
        renderList('uploadframe.php', 'solution',   $CFG->access_file, '_solutionfilesbtn',    $targetID, $returnFormat, $multipleFiles, $curriculum_id);       //solutionfiles div
        renderList('uploadframe.php', 'avatar',     $CFG->access_file, '_avatarfilesbtn',      $targetID, $returnFormat, $multipleFiles, $USER->id);         //avatarfiles div
        renderList('uploadframe.php', 'userfiles',  $CFG->access_file, '_myfilesbtn',          $targetID, $returnFormat, $multipleFiles, $USER->id);          //myfiles div
        ?>
    </div>
</body>
</html>