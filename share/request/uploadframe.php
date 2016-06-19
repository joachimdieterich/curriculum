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

include_once '../setup.php'; //Läd alle benötigten Dateien
global $CFG, $PAGE, $USER, $LOG;
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
if (!isset($_SESSION['USER'])){ die(); }    // logged in?
$USER = $_SESSION['USER'];                  // $USER not defined but required on 

/* set defaults */
$file       = new File();
$curID      = null;         //todo: only use ref_id
$terID      = null;         //todo: only use ref_id
$enaID      = null;         //todo: only use ref_id
$ref_id     = null;         //todo: only use ref_id
$target     = null;         // id of target field
$format     = null;         // return format 0 == file_id; 1 == file_name; 2 == filePath / URL
$multiple   = null;         // upload multiple files // not used yet  false == returns one file, true = returns array of files_id/file_name/file_path (depends on $format)
$context    = null; 
$title      = null; 
$description= null; 
$author     = $USER->firstname.' '.$USER->lastname;
$license    = 2;
$fileURL    = null; 
$action     = 'upload';

$error      = '';
$image      = '';
$copy_link  = '';

/* get url parameters */
foreach ($_GET  as $key => $value) { $$key = $value; } 
/* get form data */
foreach ($_POST as $key => $value) { $$key = $value; }
?>

<!-- HTML -->
<script type="text/javascript" src="../../public/assets/scripts/uploadframe.js"></script>

<div class="uploadframeClose" onclick="self.parent.tb_remove();"></div>    
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close nyroModalClose" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Dateiauswahl</h4>
    </div>
    <div class="modal-body" style="min-height: 450px !important;"> <!-- to do recalc nyroModal on changes--> 
        <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar" style="padding-top:0px !important;">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
              <!--<li class="header">Menü</li>-->
                <?php 
                $values = array (0 => array('capabilities' =>  'file:upload',           'id' =>  'fileuplbtn',          'name' => 'Datei hochladen',      'class' => 'fa  fa-upload',    'action' => 'upload'), 
                                 1 => array('capabilities' =>  'file:uploadURL',        'id' =>  'fileURLbtn',          'name' => 'Datei-URL verknüpfen', 'class' => 'fa  fa-link',      'action' => 'url'), 
                                 2 => array('capabilities' =>  'file:lastFiles',        'id' =>  'filelastuploadbtn',   'name' => 'Letzte Dateien',       'class' => 'fa  fa-files-o',   'action' => 'lastFiles'), 
                                 3 => array('capabilities' =>  'file:curriculumFiles',  'id' =>  'curriculumfilesbtn',  'name' => 'Aktueller Lehrplan',   'class' => 'fa  fa fa-th',     'action' => 'curriculumFiles'), 
                                 4 => array('capabilities' =>  'file:solution',         'id' =>  'solutionfilesbtn',    'name' => 'Meine Abgaben',        'class' => 'fa  fa-clipboard', 'action' => 'mySolutions'), 
                                 5 => array('capabilities' =>  'file:myFiles',          'id' =>  'myfilesbtn',          'name' => 'Meine Dateien',        'class' => 'fa  fa-user',      'action' => 'myFiles'), 
                                 6 => array('capabilities' =>  'file:myAvatars',        'id' =>  'avatarfilesbtn',      'name' => 'Meine Profilbilder',   'class' => 'fa  fa-user',      'action' => 'myAvatars')
                );
                foreach($values as $value){
                    if (checkCapabilities($value['capabilities'], $USER->role_id, false)){ //don't throw exeption!?>
                        <li class="treeview <?php if ($action == $value['action']){echo 'active';}?>" >
                            <a id="<?php echo $value['id']?>" href="../share/request/uploadframe.php?action=<?php 
                                    echo $value['action'].'&context='.$context.'&curID='.$curID.'&terID='.$terID.'&enaID='.$enaID.'&ref_id='.$ref_id.'&target='.$target.'&format='.$format.'&multiple='.$multiple;
                                     ?>" class="nyroModal">
                                <i class="<?php echo $value['class']?>"></i> <span><?php echo $value['name']?></span>
                            </a>
                        </li> <?php 
                    }
                } ?>
            
            <div id="div_FilePreview" style="display:none;">
                <img id="img_FilePreview" src="" alt="Vorschau">
            </div>
          </ul>
        </section>
      </aside>
      
      <div class="content-wrapper" >
        <?php if ($action == 'upload' OR $action == 'url'){ ?>
        <div class="box box-widget">
          <!--?php echo $action;  echo var_dump($action); ?-->
          <form id="uploadform" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
            <p><input id="context" name="context" type="hidden" value="<?php echo $context; ?>" /></p> <!-- context = von wo wird das Uploadfenster aufgerufen-->
            <p><input id="action" name="action" type="hidden" value="<?php   echo $action; ?>" /></p>
            <p><input id="ref_id" name="ref_id" type="hidden" value="<?php   echo $ref_id; ?>" /></p>
            <p><input id="curID"   name="curID" type="hidden" value="<?php   echo $curID; ?>" /></p>
            <p><input id="terID" name="terID" type="hidden" value="<?php   echo $terID; ?>" /></p>
            <p><input id="enaID" name="enaID" type="hidden" value="<?php   echo $enaID; ?>" /></p> <?php
            echo Form::input_text('title', 'Titel', $title, $error, 'z. B. Diagramm eLearning'); 
            echo Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung'); 
            echo Form::input_text('author', 'Autor', $author, $error, 'Max Mustermann'); 
            $l = new License();
            echo Form::input_select('license', 'Lizenz', $l->get(), 'license', 'id', $license , $error);
            $c = new Context();
            echo Form::input_select('file_context', 'Freigabe-Level', $c->get(), 'description', 'id', $context , $error);?>
            <p><input id="target" name="target" type="hidden" value="<?php  echo $target; ?>" /></p>
            <p><input id="format" name="format" type="hidden" value="<?php  echo $format; ?>" /></p>
            <p><input id="multiple" name="multiple" type="hidden" value="<?php echo $multiple; ?>" /></p>
            <?php 
            if ($action == 'upload') { ?> 
            <span id="div_fileuplbtn">    <!-- Fileupload-->
                <?php echo Form::upload_form('uploadbtn', 'Datei hochladen', '', $error); ?>
            </span><?php } 
            if ($action == 'url') { ?> 
            <span id="div_fileURLbtn" >     <!-- URLupload--><?php
                echo Form::input_text('fileURL', 'URL', $fileURL, $error, 'http://www.curriculumonline.de'); 
                echo '<button name="update" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="uploadURL();"> URL hinzufügen</button><br>';
                ?>
            </span>
            <?php } ?>
            </form>    
            <p class="text ">&nbsp;<?php echo $error; ?></p>
            <div class="uploadframe_footer"><?php echo $copy_link; ?></div>
        </div><!-- /.tab-pane -->
        <?php 
        } 
        
        if ($action == 'lastFiles'){ ?>
        <div class="box box-widget form-horizontal">
          <?php error_log($USER->id);
          RENDER::filelist('uploadframe.php', 'user',       $CFG->access_file, '_filelastuploadbtn',   $target, $format, $multiple, $USER->id);        //FileLastUpload div?>
        </div>
        <?php }
        
        if ($action == 'curriculumFiles'){ ?>
        <div class="box box-widget">
          <?php RENDER::filelist('uploadframe.php', 'curriculum', $CFG->access_file, '_curriculumfilesbtn',  $target, $format, $multiple, $curriculum_id);   //curriculumfiles?>
        </div>
        <?php }
        
        if ($action == 'mySolutions'){ ?>
        <div class="box box-widget">
          <?php  RENDER::filelist('uploadframe.php', 'solution',   $CFG->access_file, '_solutionfilesbtn',   $target, $format, $multiple, $curriculum_id);       //solutionfiles div?>
        </div>
        <?php }
        
        if ($action == 'myFiles'){ ?>
        <div class="box box-widget">
          <?php RENDER::filelist('uploadframe.php', 'userfiles',  $CFG->access_file, '_myfilesbtn',          $target, $format, $multiple, $USER->id);          //myfiles div?>
        </div>
        <?php }
        
        if ($action == 'myAvatars'){ ?>
        <div class="box box-widget">
          <?php RENDER::filelist('uploadframe.php', 'avatar',     $CFG->access_file, '_avatarfilesbtn',      $target, $format, $multiple, $USER->id);         //avatarfiles div?>
        </div>
        <?php } ?>
        </div><!--. content-wrapper -->          
    </div> <!-- .modal-body -->
</div>