<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename teacherUser.php
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
global $USER, $PAGE, $TEMPLATE;

$groups = new Group();

$TEMPLATE->assign('showFunctions', true);

if (isset($_GET['function'])) {
    $TEMPLATE->assign('showFunctions', false);  
    $current_user = new User();
     switch ($_GET['function']) {
        case "showCurriculum": 
                $TEMPLATE->assign('showenroledCurriculum', true); 
                $current_user->id = $_GET['userID'];
                $result = $current_user->getCurricula();
                if ($result){
                    setPaginator('curriculumList', $TEMPLATE, $result, 'resultscurriculumList', 'index.php?action=teacherUser&function=showCurriculum&userID='.$_GET['userID']); //set Paginator    
                }
                break;
       case "showGroups": 
                $TEMPLATE->assign('showenroledGroups', true); 
                $TEMPLATE->assign('selectedUserID', $_GET['userID']);
                $current_user->id = $_GET['userID'];
                $result = $current_user->getGroups();
                if ($result){
                    resetPaginator('groupsPaginator');
                    setPaginator('groupsPaginator', $TEMPLATE, $result, 'groups_list', 'index.php?action=teacherUser&function=showGroups&userID='.$_GET['userID']); //set Paginator    
                }
                break;
            default: break;
     }     
}

if (isset($_POST)){
    $edit_user = new User(); 
    if (isset($_POST['id'])){
    foreach ($_POST['id'] as $edit_user->id ) { //Array per schleife abarbeiten
        if($edit_user->id  == "none") {
            if (count($_POST['id']) == 1){
                $PAGE->message[] = 'Es muss mindestens ein Nutzer ausgewählt werden!';
                }	
        } else { 
            $edit_user->load('id',$edit_user->id);      // load current user 
            switch ($_POST) {
                case isset($_POST['resetPassword']):
                                    if (isset($_POST['confirmed'])) {
                                        $edit_user->confirmed = 3; // User have to change password after login
                                    } else {
                                        $edit_user->confirmed = 1; 
                                    }
                                    $edit_user->password = $_POST['password'];
                                    $validated_data = $edit_user->validate(true);
                                    if($validated_data === true) {/* validation successful */
                                        if ($edit_user->resetPassword()) {
                                                $PAGE->message[] = 'Passwort des Nutzers '.$edit_user->firstname.' '.$edit_user->lastname.' ('.$edit_user->username.') wurde zurückgesetzt.';
                                            } else {
                                                $PAGE->message[] = 'Password konnte nicht zurückgesetzt werden.';
                                            }  
                                    } else {
                                        foreach($edit_user as $key => $value){
                                            $TEMPLATE->assign($key, $value);
                                        } 
                                        $TEMPLATE->assign('v_error', $validated_data);     
                                    }        
                    break;
                case isset($_POST['deleteUser']):
                                    if ($edit_user->id != $USER->id) {
                                        if ($edit_user->delete()){
                                            $PAGE->message[] = 'Benutzerkonten wurden erfolgreich gelöscht!';
                                        }
                                    } else {
                                        $PAGE->message[] = 'Man kann sich nicht selbst löschen!';	
                                    }
                    break; 
                case isset($_POST['setRole']):
                                    $edit_user->role_id = $_POST['roles'];
                                    if ($edit_user->updateRole()){
                                        $PAGE->message[] = 'Nutzer <strong>'.$edit_user->username.'</strong> wurde in die Rolle <strong>'.$edit_user->role_name.'</strong> eingeschrieben.';
                                    }
                break; 
                case isset($_POST['enroleGroups']):
                                    if ($edit_user->enroleToGroup($_POST['groups'], $USER->id)){
                                        $groups->id = $_POST['groups']; 
                                        $groups->load();
                                        $PAGE->message[] = 'Nutzer <strong>'.$edit_user->username.'</strong> erfolgreich in <strong>'.$groups->group.'</strong> eingeschrieben.';  
                                    }
                    break; 
                case isset($_POST['expelGroups']):
                                    if ($edit_user->expelFromGroup($_POST['groups'])){
                                        $groups->id = $_POST['groups']; 
                                        $groups->load();
                                        $PAGE->message[] = 'Nutzer <strong>'.$edit_user->username.'</strong> erfolgreich aus <strong>'.$groups->group.'</strong> ausgeschrieben.';  
                                    }
                    break;     
                default:
                    break;
            }      
        }
    }
}
}
/*******************************************************************************
 * END POST / GET
 */

$TEMPLATE->assign('page_message', $PAGE->message);
$TEMPLATE->assign('teacherUser', 'Benutzerverwaltung');
//addLog($USER->id, 'view', curPageURL(), 'teacherUser'); //Addlog

$roles = new Roles(); 
$TEMPLATE->assign('roles', $roles->get());                                 //getRoles

// Load groups
$group_list = $groups->getGroups('group', $USER->id);
$TEMPLATE->assign('groups_array', $group_list);          // Lerngruppen  Laden

$users = new USER();
$users->id = $USER->id; 
$users->role_id = $USER->role_id; 
$user_list = $users->userList(); // load Userdata
setPaginator('userPaginator', $TEMPLATE, $user_list, 'results', 'index.php?action=teacherUser'); //set Paginator    
?>