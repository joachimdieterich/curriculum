<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename institution.php
 * @copyright 2014 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2014.10.13 08:26
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

global $USER, $TEMPLATE;

$TEMPLATE->assign('page_title', 'Institutionen verwalten');
$TEMPLATE->assign('countries', '');
                
if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case "newInstitution": 
                $TEMPLATE->assign('showInstitutionForm', true); 
                load_Countries();
                $TEMPLATE->assign('country_id', $USER->country_id);
        default: break;
     }
}

if (isset($_GET['edit'])) { // edit institution
    $TEMPLATE->assign('showInstitutionForm', true);
    $TEMPLATE->assign('showeditInstitutionForm', true);
    load_Countries();
    $edit_institution = new Institution();
    $edit_institution->id = $_GET['id'];
    $edit_institution->load();
    foreach($edit_institution as $key => $value){      
      $TEMPLATE->assign($key, $value);
    }  
    $state = new State;
    $states = $state->getStates('profile', $edit_institution->id);
    $TEMPLATE->assign('state', $states);
}


if ($_POST){
     switch ($_POST) {
        case isset($_POST['newInstitution']):
                        $TEMPLATE->assign('showInstitutionForm', true); 
                        load_Countries();
                        $TEMPLATE->assign('country_id', $USER->country_id);
                        break;

        case isset($_POST['addInstitution']):
        case isset($_POST['updateInstitution']):
                        $gump = new Gump();
                        $gump->validation_rules(array(
                                        'institution'     => 'required',
                                        'description'     => 'required'
                                        ));
                        $validated_data = $gump->run($_POST);
                        if (!isset($_POST['state'])){
                            $_POST['state'] = 1;
                        }
                        if($validated_data === false) {/* validation failed */
                                foreach($_POST as $key => $value){
                                $TEMPLATE->assign($key, $value);
                                } 
                                $TEMPLATE->assign('v_error', $gump->get_readable_errors());   
                                load_Countries();
                            } else {
                                $new_institution = new Institution(); 
                                if (isset($_POST['id'])){
                                    $new_institution->id            = $_POST['id'];
                                }
                                $new_institution->institution   = $_POST['institution'];
                                $new_institution->description   = $_POST['description'];
                                $new_institution->schooltype_id = $_POST['schooltype_id'];
                                $new_institution->country_id    = $_POST['country'];
                                $new_institution->state_id      = $_POST['state'];
                                $new_institution->creator_id    = $USER->id; // system user
                                $new_institution->confirmed     = 1;  // institution is confirmed
                                if (isset($_POST['addInstitution'])){                                    
                                    if (isset($_POST['btn_newSchooltype'])){ 
                                        $new_schooltype = new Schooltype();
                                        $new_schooltype->schooltype  = $_POST['new_schooltype'];
                                        $new_schooltype->description = $_POST['schooltype_description'];
                                        $new_schooltype->country_id  = $_POST['country'];
                                        $new_schooltype->state_id    = $_POST['state'];
                                        $new_schooltype->creator_id =  $USER->id; 
                                        $_POST['schooltype_id'] = $new_schooltype->add(); 
                                    }
                                    $new_institution->schooltype_id = $_POST['schooltype_id'];
                                    $institution_id = $new_institution->add();
                                    $config = new Config();                
                                    $config->add('institution', $institution_id);
                                    
                                    $USER->enroleToInstitution($institution_id); 
                                    $TEMPLATE->assign('institution_id', $institution_id);  
                                }
                                if (isset($_POST['updateInstitution'])){
                                    $new_institution->update();
                                }  
                            }      
            break;
            default: break;
     }
    
     
}
                

/**
 * load countries
 * @global object $TEMPLATE 
 */
function load_Countries(){
    global $TEMPLATE; 
    $country = new State(); 
    $countries = $country->getCountries();
    $TEMPLATE->assign('countries', $countries);
    $schooltype = new Schooltype();
    $schooltypes = $schooltype->getSchooltypes();
    $TEMPLATE->assign('schooltype', $schooltypes);
    
}

$institution = new Institution();
setPaginator('institutionPaginator', $TEMPLATE, $institution->getInstitutions('all'), 'institution_list', 'index.php?action=institution'); //set Paginator   

$TEMPLATE->assign('page_message', $PAGE->message);	
?>