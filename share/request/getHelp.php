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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
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
      
      //$html .= $users->username. ': <a href="index.php?action=messages&function=shownewMessage&help_request=true&receiver_id='.$users->id.'&subject='.$enabling_objective->id.'">Benutzer kontaktieren</a><br>';
    }
} else {
    $html .= ' Leider gibt es keinen Benutzer, der dieses Lernziel erreicht hat';
}
$html .= '</div></div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->';

echo json_encode(array('html'=>$html));