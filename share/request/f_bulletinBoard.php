<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename f_bulletinBoard.php
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.05.28 11:14
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

global $CFG, $USER;
$USER   = $_SESSION['USER'];
$func   = $_GET['func'];

$error  =   null;
$object = file_get_contents("php://input");
$data  = json_decode($object, true);
if (is_array($data)) {
    foreach ($data as $key => $value){
        $$key = $value;
    }
}
            
if (isset($func)){
    switch ($func) {
        case "edit":        checkCapabilities('dashboard:editBulletinBoard', $USER->role_id);
                            $header            = 'Pinnwand ändern';
                            $edit              = true;   
                            $bulletinBoard     = new Institution();
                            $bulletinBoard->id = $USER->institution_id;
                            $bb                = $bulletinBoard->getBulletinBoard();
                            foreach ($bb as $key => $value){
                                if (!is_object($value)){
                                    $$key = $value;
                                }
                            }
            break;
        default: break;
    }
}

/* if validation failed, get formdata from session*/
if (is_object($_SESSION['FORM'])) {
    foreach ($_SESSION['FORM'] as $key => $value){
        $$key = $value;
    }
}

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">'.$header.'</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
$html .='<form id="form_bulletinBoard"  class="form-horizontal" role="form" method="post" action="../share/processors/fp_bulletinBoard.php"';

if (isset($currentUrlId)){ $html .= $currentUrlId; }
$html .= '"><input type="hidden" name="func" id="func" value="'.$func.'"/>';
/* Only edit bulletinboard of current institution todo, load bulletinboard on input_select*/
//$html .= Form::input_select('institution_id', 'Institution', $USER->institutions, 'institution', 'institution_id', $institution_id , $error);
$html .= Form::input_text('title', 'Überschrift', $title, $error,'z.B. Ankündigung');
$html .= Form::input_textarea('text', 'Pinnwand-Text', $text, $error);
$html .= '</div><!-- /.modal-body -->
          <div class="modal-footer">';
          if (isset($edit)){
              $html .= '<button name="update" type="submit" class="btn btn-primary glyphicon glyphicon-saved pull-right" onclick="document.getElementById(\'form_bulletinBoard\').submit();"> '.$header.'</button>'; 
          } 
          if (isset($add)){
              $html .= '<button id="add" name="add" type="submit" class="btn btn-primary glyphicon glyphicon-ok pull-right" onclick="document.getElementById(\'form_bulletinBoard\').submit();"> '.$header.'</button> ';
          }    
$html .=  '</div></form></div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));