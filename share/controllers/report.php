<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename messages.php
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
global $PAGE, $USER, $TEMPLATE;

$TEMPLATE->assign('timestamp', time());  //hack, damit bei wiederholten anklicken eines postfachs die funktion detect_reload() nicth ausgelöst wird

if ($_GET){
  if (isset($_GET['id'])){
      $selected_user_id = $_GET['id'];
  }
}

if ($_POST){
  
} 

/*******************************************************************************
 * End POST / GET
 */

if (checkCapabilities('user:getUsers', $USER->role_id)){
    $user = new User(); 
    $user->load('id', $USER->id);
    $TEMPLATE->assign('user', $user);
    setPaginator('userPaginator', $TEMPLATE, $user->getUsers('institution', $USER->id), 'results', 'index.php?action=report'); //set Paginator    
}   
    
/*End User List*/ 


$enabling_objective = new EnablingObjective();
if (isset($selected_user_id)){
    /* User List*/
    
    $report = $enabling_objective->getReport($selected_user_id);
} else {
    $report = $enabling_objective->getReport();
}


/* test report*/

$ena_counter = 0; 
for($i = 0; $i<count($report); $i++){ 
    if (isset($last_date)){
        if ($last_date == substr($report[$i]->accomplished_time, 0, 10)){
            $ena_counter++; 
        } else {  
            if (isset($date)){
                $id   .= ', '.$ena_counter;
                $date .= ', "'.$last_date.'"';
            } else { //First Time
                $id    = $ena_counter;
                $date  = '"'.$last_date.'"';    
            }
            $ena_counter = 1; 
            $last_date = substr($report[$i]->accomplished_time, 0, 10);
        }
        //echo "<p>output: ",$i, " date: ",count($report)-1;
        if ($i == count($report)-1){
            $id   .= ', '.$ena_counter;
            $date .= ', "'.$last_date.'"';
        }
        $last_date = substr($report[$i]->accomplished_time, 0, 10);
    } else {$last_date = substr($report[$i]->accomplished_time, 0, 10); $ena_counter++; }
}
if (isset($id)){
    $TEMPLATE->assign('report_id', $id); 
    $TEMPLATE->assign('report_acc_date', $date); 
}
 /**End Test report **/ 
 
/**
 * setContenttitle & log
 */
$TEMPLATE->assign('page_title', 'Berichte');    
$TEMPLATE->assign('page_message', $PAGE->message);
?>