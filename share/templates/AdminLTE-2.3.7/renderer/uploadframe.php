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

include_once '../../../setup.php'; //Läd alle benötigten Dateien
global $CFG, $PAGE, $USER, $LOG;
include('../../../login-check.php');  //check login status and reset idletimer
if (!isset($_SESSION['USER'])){ die(); }    // logged in?
$USER = $_SESSION['USER'];                  // $USER not defined but required on 
$template_path = $CFG->smarty_template_dir_url.'renderer/';
/* set defaults */
$file       = new File();
$ref_id     = null;         //todo: only use ref_id
$target     = null;         // id of target field
$format     = null;         // return format 0 == file_id; 1 == file_name; 2 == filePath / URL
$context    = null; 
$title      = null; 
$description= null; 
$author     = $USER->firstname.' '.$USER->lastname;
$license    = 2;
$fileURL    = null; 
$action     = 'upload';
$list_format= 'thumbs';
$error      = '';
$image      = '';
$copy_link  = '';

/* get url parameters */
foreach ($_GET  as $key => $value) { $$key = $value; } 
/* get form data */
foreach ($_POST as $key => $value) { $$key = $value; }
if (isset($paginator) AND isset($paginator_search) AND isset($order)) {
    if ($paginator_search == '%'){
        unset ($_SESSION['SmartyPaginate'][$paginator]['pagi_search']);
    } else {
        if ($order != ''){ //if no field list is defined search in order field
            SmartyPaginate::setSearchField(array($order),  $paginator);
        } else {
            SmartyPaginate::setOrder('', $paginator);
        }
        SmartyPaginate::setSearch($order, $paginator_search, $paginator);
    }
}
?>

<!-- HTML -->
<div class="uploadframeClose" onclick="self.parent.tb_remove();"></div>    
<div class="modal-content ">
    <div class="modal-header">
        <button type="button" class="close nyroModalClose" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-bars" onclick="toggle_sidebar('modal_sidebar')"></i> Dateiauswahl</h4>
    </div>
    <div id="modal_sidebar" class="modal-body sidebar" style="min-height: 450px !important;padding-left: 0px; padding-top: 0px; position:relative;"> <!-- to do recalc nyroModal on changes--> 
        <aside class="main-sidebar" style="padding-top:0px !important;position: absolute !important;">
          <!-- sidebar: style can be found in sidebar.less -->
          <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                  <?php 
                  $values = array (0 => array('capabilities' =>  'file:upload',           'id' =>  'fileuplbtn',          'name' => 'Datei hochladen',      'class' => 'fa  fa-upload',    'action' => 'upload'), 
                                   1 => array('capabilities' =>  'file:uploadURL',        'id' =>  'fileURLbtn',          'name' => 'Datei-URL verknüpfen', 'class' => 'fa  fa-link',      'action' => 'url'), 
                                   2 => array('capabilities' =>  'file:lastFiles',        'id' =>  'filelastuploadbtn',   'name' => 'Letzte Dateien',       'class' => 'fa  fa-files-o',   'action' => 'user'), 
                                   3 => array('capabilities' =>  'file:curriculumFiles',  'id' =>  'curriculumfilesbtn',  'name' => 'Aktueller Lehrplan',   'class' => 'fa  fa fa-th',     'action' => 'curriculum'), 
                                   4 => array('capabilities' =>  'file:solution',         'id' =>  'solutionfilesbtn',    'name' => 'Meine Abgaben',        'class' => 'fa  fa-clipboard', 'action' => 'solution'), 
                                   5 => array('capabilities' =>  'file:myFiles',          'id' =>  'myfilesbtn',          'name' => 'Meine Dateien',        'class' => 'fa  fa-user',      'action' => 'userfiles'), 
                                   6 => array('capabilities' =>  'file:myAvatars',        'id' =>  'avatarfilesbtn',      'name' => 'Meine Profilbilder',   'class' => 'fa  fa-user',      'action' => 'avatar'),
                                   7 => array('capabilities' =>  'file:myCertificates',   'id' =>  'certifiatefilesbtn',  'name' => 'Meine Zertifikate',    'class' => 'fa  fa-certificate','action' => 'certificate')
                  );
                  foreach($values as $value){
                      if (checkCapabilities($value['capabilities'], $USER->role_id, false)){ //don't throw exeption!
                          if (($value['action'] == 'curriculum' AND ($context != 'terminal_objective' AND $context != 'enabling_objective')) OR ($value['action'] == 'solution' AND $context != 'solution'))  { 
                              // do nothing
                          } else { ?>
                              <li class="treeview <?php if ($action == $value['action']){echo 'active';}?>" >
                                  <a id="<?php echo $value['id']?>" 
                                     href="<?php echo $template_path.'uploadframe.php?action='.$value['action'].'&context='.$context.'&ref_id='.$ref_id.'&target='.$target.'&format='.$format;?>" 
                                     class="nyroModal">
                                      <i class="<?php echo $value['class']?>"></i> <span><?php echo $value['name']?></span>
                                  </a>
                              </li> <?php 
                          }
                      }
                  } ?>
            </ul>
          </section>
        </aside>
      
        <div class="content-wrapper" >
            <div class="bg-white">
          <?php if (($action == 'upload' AND checkCapabilities('file:upload', $USER->role_id, false)) OR ($action == 'url' AND checkCapabilities('file:uploadURL', $USER->role_id, false))){ ?>
            <form id="uploadform" class="form-horizontal" style="padding-top:10px;padding-left: 10px;" role="form" method="post" enctype="multipart/form-data">
              <input id="context" name="context" type="hidden" value="<?php echo $context; ?>" /> <!-- context = von wo wird das Uploadfenster aufgerufen-->
              <input id="action"  name="action"  type="hidden" value="<?php echo $action; ?>" />
              <input id="ref_id"  name="ref_id"  type="hidden" value="<?php echo $ref_id; ?>" /><?php
              echo Form::input_text('title', 'Titel', $title, $error, 'z. B. Diagramm eLearning'); 
              echo Form::input_text('description', 'Beschreibung', $description, $error, 'Beschreibung'); 
              echo Form::input_text('author', 'Autor', $author, $error, 'Max Mustermann'); 
              $l = new License();
              echo Form::input_select('license', 'Lizenz', $l->get(), 'license', 'id', $license , $error);
              $c = new Context();
              echo Form::input_select('file_context', 'Freigabe-Level', $c->get(), 'description', 'id', $context , $error);?>
              <p><input id="target" name="target" type="hidden" value="<?php  echo $target; ?>" /></p>
              <p><input id="format" name="format" type="hidden" value="<?php  echo $format; ?>" /></p>
              <?php 
              if ($action == 'upload') { ?> 
              <span id="div_fileuplbtn">    <!-- Fileupload-->
                  <?php echo Form::upload_form('uploadbtn', 'Datei hochladen', '', $error); ?>
              </span><?php } 
              if ($action == 'url') { ?> 
              <span id="div_fileURLbtn" >     <!-- URLupload--><?php
                  echo Form::input_text('fileURL', 'URL', $fileURL, $error, 'http://www.curriculumonline.de'); 
                  echo '<div name="update_btn" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="uploadURL();"> URL hinzufügen</div><br>';
                  ?>
              </span>
              <?php } ?>
              </form>    
              <p class="text ">&nbsp;<?php echo $error; ?></p>
              <div class="uploadframe_footer"><?php echo $copy_link; ?></div>

          <?php 
          } else {
              echo Form::info(array('id' => 'info', 'label' => '', 'content' => 'Bitte links die gewünschte Datei-Liste wählen.'));
          }
          ?>
              <input id="target" name="target" type="hidden" value="<?php  echo $target; ?>" />
              <input id="context" name="context" type="hidden" value="<?php echo $context; ?>" />
              <?php
              if (in_array($action, array('user','curriculum','userfiles','avatar', 'certificate'))){ 
              ?>
              <div class="box-header">
                  <div class="btn-group">
                      <button type="button" class="btn btn-default">
                          <a href="<?php echo $template_path;?>uploadframe.php?action=<?php 
                              echo $action.'&context='.$context.'&list_format=thumbs&ref_id='.$ref_id.'&target='.$target.'&format='.$format;?>" class="nyroModal">
                              <i class="fa fa-th"></i>
                          </a>
                      </button>
                      <button type="button" class="btn btn-default">
                          <a href="<?php echo $template_path;?>uploadframe.php?action=<?php 
                              echo $action.'&context='.$context.'&list_format=list&ref_id='.$ref_id.'&target='.$target.'&format='.$format;?>" class="nyroModal">
                              <i class="fa fa-th-list"></i>
                          </a>
                      </button>
                      <button type="button" class="btn btn-default">
                          <a href="<?php echo $template_path;?>uploadframe.php?action=<?php 
                              echo $action.'&context='.$context.'&list_format=detail&ref_id='.$ref_id.'&target='.$target.'&format='.$format;?>" class="nyroModal">
                              <i class="fa fa-list"></i>
                          </a>
                      </button>
                  </div>
                  <div class="btn-group ">
                      <button type="button" class="btn btn-default"><?php 
                          $sort    = SmartyPaginate::getSort('sort', 'filelist_'.$action);
                          if ($sort == 'ASC'){
                              $sort = 'DESC';
                          } else {
                              $sort = 'ASC';
                          }
                          echo '<a href="'.$template_path.removeUrlParameter('uploadframe.php?action='.$action.'&context='.$context, array ( 0 => 'paginator', 1 => 'paginator_search', 2 => 'order', 3 => 'sort')).'&paginator=filelist_'.$action.'&order=filename&sort='.$sort.'&list_format='.$list_format.'&target='.$target.'&format='.$format.'" class="nyroModal"><i class="fa  fa-sort-alpha-'.strtolower($sort).'"></i></a>';
                          ?>
                      </button>
                  </div>
                  <?php 
                  if (SmartyPaginate::_getSearch('filelist_'.$action) != null){
                      echo '<div class="btn-group pull-right"><button type="button" class="btn btn-default pull-right">
                          <a href="'.$template_path.removeUrlParameter('uploadframe.php?action='.$action.'&context='.$context, array ( 0 => 'paginator', 1 => 'paginator_search', 2 => 'order', 3 => 'sort')).'&paginator=filelist_'.$action.'&order=filename&sort='.$sort.'&list_format='.$list_format.'&target='.$target.'&format='.$format.'&paginator_search=%" class="nyroModal">
                              <i class="fa fa-close"></i>
                          </a>
                      </button></div>';
                  } 
                  ?>
                  <div class="has-feedback pull-right" style="padding-top: 2px;padding-right: 2px;width:150px;">
                      <form id="file_search" method="get" action="<?php echo $template_path.removeUrlParameter('uploadframe.php?action='.$action.'&context='.$context, array ( 0 => 'paginator', 1 => 'paginator_search', 2 => 'order', 3 => 'sort')).'&paginator=filelist_'.$action.'&order=filename&sort='.$sort.'&list_format='.$list_format.'&target='.$target.'&format='.$format.'" class="nyroModal"'; ?>">
                          <input type="text" name="paginator_search" class="form-control input-sm" placeholder="Suchen" onkeydown="if (event.keyCode === 13) {document.getElementById(\'file_search\').submit();} " 
                              <?php 
                              if (SmartyPaginate::_getSearch('filelist_'.$action) == null){
                                  echo '><span class="fa fa-search form-control-feedback"></span>';
                              } else {
                                  echo 'value="'.SmartyPaginate::_getSearch('filelist_'.$action).'"><span class="fa fa-search form-control-feedback"></span>';
                              }
                              ?>  
                      </form>
                  </div>
              </div>
              <?php
              }

              switch ($action) {
                  case 'user':
                  case 'userfiles':
                  case 'avatar':      
                  case 'certificate': echo RENDER::filelist('uploadframe.php?action='.$action.'&context='.$context, $action, $list_format, $target, $USER->id);
                      break;
                  case 'curriculum':
                  case 'solution':    echo RENDER::filelist('uploadframe.php?action='.$action.'&context='.$context, $action, $list_format, $target, $ref_id);
                      break;
                  default:
                      break;       
              }
              ?>

            </div>
        </div><!--. content-wrapper -->          
    </div> <!-- .modal-body -->
</div>