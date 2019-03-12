<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename user.php
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
global $USER, $PAGE, $TEMPLATE;

$groups      = new Group();
$institution = new Institution();

if (isset($_POST) ){
    $edit_user = new User(); 
    $sel_id    = SmartyPaginate::_getSelection('userP');    //use selection from paginator (don't use form data) to get selections on all pages of paginator
    if (is_array($sel_id)){
        foreach ($sel_id as $edit_user->id ) {              //Array per schleife abarbeiten	
            $edit_user->load('id',$edit_user->id);          // load current user 
            switch ($_POST) {
                case isset($_POST['resetPassword']):
                                    if (isset($_POST['confirmed'])) {
                                        $edit_user->confirmed = 3; // User have to change password after login
                                    } else {
                                        $edit_user->confirmed = 1; 
                                    }
                                    $edit_user->password = $_POST['pwchange'];
                                    $validated_data = $edit_user->validate(true);
                                    if($validated_data === true) {/* validation successful */
                                        $edit_user->resetPassword();
                                    } else {
                                        $TEMPLATE->assign('error', $validated_data);     
                                    }        
                    break;
                case isset($_POST['deleteUser']):   $edit_user->delete();
                    break; 
                case isset($_POST['enroleGroups']): $edit_user->enroleToGroup($_POST['groups']);
                    break; 
                case isset($_POST['expelGroups']):  $edit_user->expelFromGroup($_POST['groups']);
                    break;     
                case isset($_POST['acceptUser']):  $edit_user->acceptUser(); //acception registration
                    break;     
                case isset($_POST['enroleInstitution']): 
                                    if (isset($_POST['institution'])){
                                        $institution->id = $_POST['institution'];
                                    } else {
                                        $institution->id = $USER->institution_id;
                                    }
                                    
                                    if ($USER->id             == $edit_user->id AND 
                                        $USER->role_id        == $edit_user->role_id AND
                                        $USER->institution_id == $institution->id) {
                                        $PAGE->message[] = array('message' => 'Man kann seine Rolle an der aktuellen Institution kann nicht bearbeiten.', 'icon' => 'fa fa-group text-warning'); // Benutzer dürfen sich nicht selbst an der aktuellen Institution eine geringere Rolle zuweisen. 
                                    } else {
                                        $edit_user->role_id = $_POST['roles'];
                                    }
                                    $edit_user->enroleToInstitution($institution->id);
                    break; 
                case isset($_POST['expelInstitution']):
                                    $edit_user->expelFromInstitution($_POST['institution']);    
                    break;     
                default:
                    break;
            }      
        session_reload_user(); // --> get the changes immediately 
        }
    } else {
        $PAGE->message[] = array('message' => 'Es muss mindestens ein Nutzer ausgewählt werden!', 'icon' => 'fa-user text-warning');
    }
}

/*******************************************************************************
 * END POST / GET
 */

$TEMPLATE->assign('page_title', 'Benutzerverwaltung');
$TEMPLATE->assign('breadcrumb',  array('Benutzerverwaltung' => 'index.php?action=user'));

$roles      = new Roles(); 
$TEMPLATE->assign('roles', $roles->get());                              //getRoles

//$group_list = $groups->getGroups('group', $USER->id);                   // Load groups
$group_list = $groups->getGroups('institution', $USER->institution_id);   // Load groups --> only load groups of current institution to prevent enroling to groups of foreign institutions
$TEMPLATE->assign('groups_array', $group_list);                         
$TEMPLATE->assign('myInstitutions', $institution->getInstitutions('user', '', $USER->id));
error_log(json_encode(end($institution->getInstitutions('user', '', $USER->id))));

$users      = new USER();
$p_options  = array('delete' => array('onclick'     => "processor('delete', 'user', __id__, { 'reload': 'false', 'callback': 'replaceElementByID', 'element_Id': 'row__id__'});", 
                                     'capability'   => checkCapabilities('user:delete', $USER->role_id, false),
                                     'icon'         => 'fa fa-trash',
                                     'tooltip'      => 'löschen'),
                    'parents'  => array('onclick'      => "formloader('parents','edit',__id__);", 
                                     'capability'   => checkCapabilities('user:parentalAuthority', $USER->role_id, false),
                                     'icon'         => 'fa fa-child',
                                     'tooltip'      => 'Erziehungsberechtigung zuweisen'),
                    'edit'  => array('onclick'      => "formloader('profile','editUser',__id__);", 
                                     'capability'   => checkCapabilities('user:updateUser', $USER->role_id, false),
                                     'icon'         => 'fa fa-edit',
                                     'tooltip'      => 'bearbeiten'),
                    'profile'  => array('onclick'   => "formloader('preview_user','full',__id__);", 
                                     'capability'   => checkCapabilities('user:getGroups', $USER->role_id, false),  //todo: use extra capability?
                                     'icon'         => 'fa fa-list-alt',
                                     'tooltip'      => 'Überblick'));
$p_widget  = array('header'      => 'username',
                   'subheader01' => 'firstname, lastname',
                   'subheader02' => 'email',
                   'file_id'     => 'avatar_id',
                   'circle_image'=> 'file_id'); 
if (checkCapabilities('user:shortUserList', $USER->role_id, false)){
	$p_config   = array('id'         => 'checkbox',
                      'username'   => 'Benutzername',
                      'firstname'  => 'Vorname',
                      'lastname'   => 'Nachname',
                   /* 'email'      => 'Email', */
                   /* 'postalcode' => 'PLZ', */
                   /* 'city'       => 'Ort', */
                   /* 'state'      => 'Bundesland', */
                   /* 'country'    => 'Land', */
                      'p_search'   => array('username','firstname','lastname'),
                      'p_widget'   => $p_widget,
                      'p_options'  => $p_options);
}
else {
  $p_config   = array('id'         => 'checkbox',
                      'username'   => 'Benutzername',
                      'firstname'  => 'Vorname',
                      'lastname'   => 'Nachname',
                      'email'      => 'Email',
                      'postalcode' => 'PLZ',
                      'city'       => 'Ort',
                   /* 'state'      => 'Bundesland', */
                   /* 'country'    => 'Land', */
                      'p_search'   => array('username','firstname','lastname','email','postalcode','city'),
                      'p_widget'   => $p_widget,
                      'p_options'  => $p_options);
}
$TEMPLATE->assign('filter_institution_id', false);
if (isset($_GET['filter_institution'])){
    if ($_GET['filter_institution'] == 'false'){
        $u = $users->userList('institution', 'userP', filter_input(INPUT_GET, 'filter', FILTER_UNSAFE_RAW));
    } else {
        $TEMPLATE->assign('filter_group_id', $_GET['filter_group']);
        $TEMPLATE->assign('filter_role_id', $_GET['filter_role']);
        $u = $users->userList('filter_institution', 'userP', filter_input(INPUT_GET, 'filter', FILTER_UNSAFE_RAW), $_GET['filter_institution'], $_GET['filter_role'], $_GET['filter_group']);
        
        $TEMPLATE->assign('filter_institution_id', $_GET['filter_institution']);
        $role      = new Roles();
        $roles     = $role->get();
        $TEMPLATE->assign('roles',      $roles);
        $group     = new Group();
        $groups    = $group->getGroups('user_institution', $USER->id, '', $_GET['filter_institution']);
        $TEMPLATE->assign('groups',     $groups   );
    }
} else {
    $u = $users->userList('institution', 'userP', filter_input(INPUT_GET, 'filter', FILTER_UNSAFE_RAW));
}

if(isset($_SESSION['PAGE']->config['tab'])){
    $TEMPLATE->assign($_SESSION['PAGE']->config['tab'],  true);
} else {
    $TEMPLATE->assign('tab_password',  true);
}

setPaginator('userP', $u, 'us_val', 'index.php?action=user', $p_config); //set Paginator
