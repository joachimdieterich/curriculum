<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename role.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.11.17 17:18
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
//throw new CurriculumException("Sie verfügen nicht über ausreichende Berechtigungen");
if (isset($_GET['function']) AND checkCapabilities('page:showRoleForm', $USER->role_id)) {
     switch ($_GET['function']) {
        case "newRole": 
                $TEMPLATE->assign('showRoleForm', true);
                $edit_capabilities = new Capability();
                $TEMPLATE->assign('capabilities', $edit_capabilities->getCapabilities(0)); //use student capabilities
                 break;       
        default: break;
     }
}

// edit role
if (isset($_GET['edit'])) {
    $TEMPLATE->assign('showRoleForm',      true);
    $TEMPLATE->assign('showeditRoleForm',  true);
    
    $edit_role = new Roles();
    $edit_role->role_id = $_GET['id'];
    $edit_role->load();
    
    $edit_capabilities = new Capability();
    //var_dump($edit_capabilities->getCapabilities($edit_role->role_id));
    $TEMPLATE->assign('capabilities', $edit_capabilities->getCapabilities($edit_role->role_id)); 
    $TEMPLATE->assign('id',           $edit_role->role_id);
    $TEMPLATE->assign('role',         $edit_role->role);                
    $TEMPLATE->assign('description',  $edit_role->description);  
     
} 

if($_POST){
    switch ($_POST) {
       case isset($_POST['deleteRole']):       
                        $edit_role->role_id = $_POST['id'];
                        $edit_role->creator_id = $USER->id;
                        $edit_role->delete();
                        break; 

       case isset($_POST['newRole']): 
                        $TEMPLATE->assign('showRoleForm', true); 
                        break;

       //Formulardaten verarbeiten    
       case isset($_POST['addRole']):
       case isset($_POST['updateRole']):
                        $new_role = new Roles();
                        if (isset($_POST['id'])){
                            $new_role->role_id         = $_POST['id'];   
                        }
                        $new_role->role           = $_POST['role'];
                        $new_role->description    = $_POST['description'];
                        $new_role->creator_id     = $USER->id;
                         
                        foreach($_POST as $key => $value)
                        {
                            if ($value === "true" OR $value === "false")
                            {
                                $new_role->capabilities[] = array ($key => $value);
                            }
                        }                        
                        
                        $gump = new Gump(); /* Validation */
                        $gump->validation_rules(array(
                        'role'         => 'required',
                        'description'   => 'required'
                        ));
                        $validated_data = $gump->run($_POST);
                        
                        if($validated_data === false) {/* validation failed */
                            foreach($new_role as $key => $value){
                            $TEMPLATE->assign($key, $value);
                            } 
                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                            $TEMPLATE->assign('showRoleForm', true); 
                        } else {/* validation successful */
                            if (isset($_POST['addRole'])){
                                $new_role->add();
                            }
                            if (isset($_POST['updateRole'])){
                                $new_role->update();
                            }       
                        }
                        break;                    
}
    $TEMPLATE->assign('page_message', $PAGE->message);
}
/*******************************************************************************
 * END POST / GET 
 */
$role = new Roles();
setPaginator('rolePaginator', $TEMPLATE, $role->get(), 'role_list', 'index.php?action=role'); //set Paginator
//include ('./../lang/'.$USER->language.'/role.php');                       //Wählt die gesetzte Sprache aus //muss ganz unten stehen!
?>