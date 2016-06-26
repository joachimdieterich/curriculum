<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename getHelp.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.06.01 17:00
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
$base_url               = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER                   = $_SESSION['USER'];

$enabling_objective     = new EnablingObjective();
$enabling_objective->id = filter_input(INPUT_GET, 'enablingObjectiveID', FILTER_VALIDATE_INT);
$enabling_objective->load();
$result                 = $enabling_objective->getAccomplishedUsers(filter_input(INPUT_GET, 'group', FILTER_VALIDATE_INT));

$html ='<div class="modal-dialog" style="overflow-y: initial !important;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closePopup()"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Hilfe </h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">';
if ($result){
$html .= 'Folgende Benutzer haben das Lernziel: <strong>'.$enabling_objective->enabling_objective.'</strong> bereits erreicht und können dir helfen:<br>';

$users                  = new User();
    if (count($result)> 10){$max = 10;} else {$max = count($result);}
    for($i = 0; $i < $max; $i++) {
      $users->load('id', $result[$i],false);
      $html .= '<div class="user-block hover">
                        <img class="img-circle img-bordered-sm" src="'.$CFG->access_file.$users->avatar.'" alt="user image"><a href="#" class="pull-right btn-box-tool" onclick="formloader(\'mail\',\'gethelp\','.$users->id.');"><i class="fa fa-envelope"></i></a>
                        <span class="username">'.$users->username.'
                        </span>
                        <span class="description">'.$users->firstname.' '.$users->lastname.'</span>
                      </div><br>';
    }
} else {
    $html .= ' Leider gibt es keinen Benutzer, der dieses Lernziel erreicht hat';
}
$html .= '</div></div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));