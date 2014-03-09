<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename confirm.php
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
global $USER, $TEMPLATE, $PAGE;

if(isset($_GET['reset']) OR (isset($_POST['reset']))){
    resetPaginator('usersPaginator');            
}
// Formular für neuen Rollentyp anzeigen
if($_POST){
    $new_user = new User(); 
    // Benutzer freigeben
    if(isset($_POST['confirmUser'])) {
        foreach ( $_POST['id_user'] as $check ) { 
            if($check == "none") {// no selection = none
                if (count($_POST['id_user']) == 1){
                    $PAGE->message[] = 'Es muss mindestens ein Nutzer ausgewählt werden!';
                    }	
                } else {
                    if ($USER->confirmUser($check)){
                        $new_user->load('id', $check);
                        $PAGE->message[] = 'Der Benutzer <strong>'.$new_user->firstname.' '.$new_user->lastname.' ('.$new_user->username.')</strong> erfolgreich freigegeben.';  
                    }
                }
        }
    }
    
    // (unerwünschte) Benutzer löschen
    if(isset($_POST['deleteUser'])) {
        foreach ( $_POST['id_user'] as $check ) { 
            if($check == "none") {// no selection = none
                if (count($_POST['id_user']) == 1){
                    $PAGE->message[] = 'Es muss mindestens ein Nutzer ausgewählt werden!';
                    }	
            } else {
                if ($check != $USER->id) {
                    $new_user->load('id', $check);
                    if ($new_user->delete($check)){
                    $PAGE->message[] = 'Benutzerkonten wurden erfolgreich gelöscht!';
                    }
                } else {
                    $PAGE->message[] = 'Man kann sich nicht selbst löschen!';	
                }
            }
        } 
    }
        $TEMPLATE->assign('page_message', $PAGE->message);
}

/******************************************************************************
 * END POST / GET 
 */
$TEMPLATE->assign('page_title', 'Freigaben');   //setContenttitle

$users = new USER();
$users->id = $USER->id; 
$users->role_id = $USER->role_id; 
$user_list = $users->userList('confirm'); // load Userdata
if (isset($user_list)){
    setPaginator('usersPaginator', $TEMPLATE, $user_list, 'results', 'index.php?action=adminConfirm'); //set Paginator    
} else {
    $TEMPLATE->assign('data', 'none'); //keine Datensätze vorhanden
}
?>