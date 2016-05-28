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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful,  but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $USER, $PAGE, $TEMPLATE;

if(isset($_GET['function'])){           
    switch ($_GET['function']) {
        case 'new': checkCapabilities('role:add',       $USER->role_id);      // USER berechtigt?
                    $TEMPLATE->assign('showForm',       true);
                    $edit_capabilities = new Capability();
                    $TEMPLATE->assign('capabilities',   $edit_capabilities->getCapabilities(0)); // Lade Standart-Rechte --> vererbung von Student
            break;
        case 'edit':checkCapabilities('role:update',    $USER->role_id);      // USER berechtigt?
                    $TEMPLATE->assign('showForm',       true);
                    $TEMPLATE->assign('editBtn',        true);

                    $edit_role          = new Roles();
                    $edit_role->id = filter_input(INPUT_GET, 'id', FILTER_UNSAFE_RAW); // kein INT da Systemrolle -1
                    $edit_role->load();

                    assign_to_template($edit_role, 'r_'); //mit Prefix 'r_', um Feld-Dopplungen mit $capabilities zu verhindern
                    $edit_capabilities  = new Capability();
                    $TEMPLATE->assign('capabilities', $edit_capabilities->getCapabilities($edit_role->id)); 
                    assign_to_template($edit_capabilities); 
            break;
        default: break;
    }
}

if($_POST){
    switch ($_POST) {
       case isset($_POST['add']):   checkCapabilities('role:add',    $USER->role_id);
       case isset($_POST['update']):// role:update Berechtigung wird unten geprüft
                                    $new_role = new Roles();
                                    if (isset($_POST['r_id'])){
                                        $new_role->id          = filter_input(INPUT_POST,  'r_id',     FILTER_UNSAFE_RAW);   
                                    }
                                    $new_role->role                 = filter_input(INPUT_POST,  'r_role',        FILTER_UNSAFE_RAW);
                                    $new_role->description          = filter_input(INPUT_POST,  'r_description', FILTER_UNSAFE_RAW);
                                    $new_role->creator_id           = $USER->id;
                                    foreach($_POST as $key => $value){ // vorhandene Capabilities erfassen 
                                        if ($value === "true" OR $value === "false") {
                                            $new_role->capabilities[] = array ($key => $value);
                                        }
                                    }                        

                                    $gump   = new Gump();                         /* Validation */
                                    $_POST  = $gump->sanitize($_POST);           //sanitize $_POST
                                    $gump->validation_rules(array(
                                    'r_role'          => 'required',
                                    'r_description'   => 'required'
                                    ));
                                    $validated_data = $gump->run($_POST);

                                    if($validated_data === false) {/* validation failed */
                                        assign_to_template($new_role);
                                        $TEMPLATE->assign('v_error',    $gump->get_readable_errors());     
                                        $TEMPLATE->assign('showForm',   true); 
                                    } else {/* validation successful */
                                        if (isset($_POST['add'])){
                                            $new_role->add();
                                        }
                                        if (isset($_POST['update'])){
                                            checkCapabilities('role:update',    $USER->role_id); // Berechtigung erst hier abfragen, damit Rollen die nur die Berechtigung  "role:add" besitzten funktionieren
                                            $new_role->update();
                                        }       
                                    }
                        break;    
       default: break;
    }
}
/*******************************************************************************
 * END POST / GET 
 */
$TEMPLATE->assign('page_title', 'Rollen verwalten');
$role = new Roles();

$p_options = array('delete' => array('onclick' => "del('role',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('role:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-plus'),
                    'edit'  => array('href'    => 'index.php?action=role&function=edit&id=__id__',
                                     'capability' => checkCapabilities('role:update', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit'));
$p_config =   array('id'         => 'checkbox',
                    'role'    => 'Rolle', 
                  'description'   => 'Beschreibung', 
                  'p_options'     => $p_options);
setPaginator('roleP', $TEMPLATE, $role->get('roleP'), 'ro_val', 'index.php?action=role', $p_config); //set Paginator