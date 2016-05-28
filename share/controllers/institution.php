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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $CFG, $USER, $TEMPLATE, $INSTITUTION;

$TEMPLATE->assign('byte',        convertMbToByte($CFG->post_max_size));     //Dateigröße in Byte
$TEMPLATE->assign('page_title',  'Institutionen');
$TEMPLATE->assign('breadcrumb',  array('Institutionen' => 'index.php?action=institution'));
$state                         = new State;
                
/*if (isset($_GET['function'])) {
                        loadSelectData();   // in allen Fällen laden
     switch ($_GET['function']) {
        case "new":     checkCapabilities('institution:add',    $USER->role_id);
                        $TEMPLATE->assign('showForm',   true); 
                        $TEMPLATE->assign('country_id',     $CFG->standard_country);
                        $TEMPLATE->assign('state_id',       $CFG->standard_state);
                        $TEMPLATE->assign('paginator_limit',$CFG->paginator_limit);
                        $TEMPLATE->assign('acc_days',       $CFG->acc_days);
                        $TEMPLATE->assign('timeout',        $CFG->timeout);
                        $TEMPLATE->assign('semester_id',    $INSTITUTION->semester_id); 
                        $TEMPLATE->assign('std_role',       $CFG->standard_role);
                        $TEMPLATE->assign('state',          $state->getStates('profile',$INSTITUTION->country_id));
                        $new_institution                   = new Institution();
                        $TEMPLATE->assign('id',     $new_institution->getNewId()); //neue Institutions ID ermitteln (wichtig für Logo Directory)
            break;
        case "edit":    checkCapabilities('institution:update',    $USER->role_id);
                        $TEMPLATE->assign('showForm',       true);
                        $TEMPLATE->assign('editBtn',        true);
                        
                        $edit_institution                   = new Institution();
                        $edit_institution->id               = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $edit_institution->load();
                        assign_to_template($edit_institution);
                        $TEMPLATE->assign('state_id',       $edit_institution->state_id);
                        $TEMPLATE->assign('state',          $state->getStates('profile', $edit_institution->country_id));            
            break;
        default: break;
     }
}*/

if ($_POST){
     switch ($_POST) {       
        case isset($_POST['add']):
        case isset($_POST['update']):   $gump   = new Gump();
                                        $_POST  = $gump->sanitize($_POST);           //sanitize $_POST
                                        $gump->validation_rules(array(
                                                        'institution'     => 'required',
                                                        'description'     => 'required'
                                                        ));
                                        $validated_data = $gump->run($_POST);
                                        if (!isset($_POST['state'])){ $_POST['state'] = 1; }
                                        if($validated_data === false) {/* validation failed */
                                            $TEMPLATE->assign('showForm',       true);
                                            //assign_to_template($_POST);
                                            //loadSelectData();
                                            $TEMPLATE->assign('v_error',        $gump->get_readable_errors());  
                                            //ERROR übergeben
                                            $TEMPLATE->assign('form_data', $_POST); /* daten für das formular bereitstellen*/
                                            $TEMPLATE->assign('form_function', $_GET['function']);
                                            //$TEMPLATE->assign('state',          $state->getStates('profile', $_POST['country_id']));
                                            if (isset($_POST['update'])){       $TEMPLATE->assign('editBtn',        true); } 
                                        } else {
                                            $new_institution = new Institution(); 
                                            if (isset($_POST['id'])){
                                                $new_institution->id            = filter_input(INPUT_POST, 'id',                FILTER_VALIDATE_INT);
                                            }
                                            $new_institution->institution       = filter_input(INPUT_POST, 'institution',       FILTER_SANITIZE_STRING);
                                            $new_institution->description       = filter_input(INPUT_POST, 'description',       FILTER_SANITIZE_STRING);
                                            $new_institution->schooltype_id     = filter_input(INPUT_POST, 'schooltype_id',     FILTER_VALIDATE_INT);
                                            $new_institution->country_id        = filter_input(INPUT_POST, 'country_id',        FILTER_VALIDATE_INT);
                                            $new_institution->state_id          = filter_input(INPUT_POST, 'state_id',          FILTER_VALIDATE_INT);
                                            $new_institution->creator_id        = $USER->id; // system user
                                            $new_institution->confirmed         = 1;  // institution is confirmed
                                            $new_institution->paginator_limit   = filter_input(INPUT_POST, 'paginator_limit',   FILTER_VALIDATE_INT);
                                            $new_institution->std_role          = filter_input(INPUT_POST, 'std_role',          FILTER_VALIDATE_INT);
                                            $new_institution->csv_size          = filter_input(INPUT_POST, 'csv_size',          FILTER_VALIDATE_INT);
                                            $new_institution->avatar_size       = filter_input(INPUT_POST, 'avatar_size',       FILTER_VALIDATE_INT);
                                            $new_institution->material_size     = filter_input(INPUT_POST, 'material_size',     FILTER_VALIDATE_INT);
                                            $new_institution->acc_days          = filter_input(INPUT_POST, 'acc_days',          FILTER_VALIDATE_INT);
                                            $new_institution->timeout           = filter_input(INPUT_POST, 'timeout',           FILTER_VALIDATE_INT);
                                            $new_institution->semester_id       = filter_input(INPUT_POST, 'semester_id',       FILTER_VALIDATE_INT);
                                            $new_institution->file_id           = filter_input(INPUT_POST, 'file_id',           FILTER_VALIDATE_INT);
                                            if (isset($_POST['add'])){                                    
                                                if (isset($_POST['btn_newSchooltype'])){ 
                                                    $new_schooltype = new Schooltype();
                                                    $new_schooltype->schooltype = filter_input(INPUT_POST, 'new_schooltype',    FILTER_SANITIZE_STRING);
                                                    $new_schooltype->description= filter_input(INPUT_POST, 'schooltype_description',FILTER_SANITIZE_STRING);
                                                    $new_schooltype->country_id = filter_input(INPUT_POST, 'country',           FILTER_VALIDATE_INT);
                                                    $new_schooltype->state_id   = filter_input(INPUT_POST, 'state',             FILTER_VALIDATE_INT);
                                                    $new_schooltype->creator_id =  $USER->id; 
                                                    $_POST['schooltype_id']     = $new_schooltype->add(); 
                                                }
                                                $new_institution->schooltype_id = filter_input(INPUT_POST, 'schooltype_id',    FILTER_VALIDATE_INT);

                                                $institution_id                 = $new_institution->add();
                                                $USER->enroleToInstitution($institution_id); 
                                                $TEMPLATE->assign('institution_id', $institution_id);  
                                            }
                                            if (isset($_POST['update'])){ $new_institution->update(); }  
                                        }      
            break;
            default: break;
     }    
}             

/**
 * load countries and schooltypes
 * @global object $TEMPLATE 
 */
function loadSelectData(){
    global $TEMPLATE; 
    $country        = new State(); 
    $TEMPLATE->assign('countries', $country->getCountries());
    $schooltype     = new Schooltype();
    $TEMPLATE->assign('schooltype', $schooltype->getSchooltypes());
    $roles          = new Roles(); 
    $TEMPLATE->assign('roles',      $roles->get());                  //getRoles
}


$p_options = array('delete' => array('onclick'    => "del('institution',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('institution:delete', $USER->role_id, false),
                                     'icon'       => 'fa fa-minus'),
                    'edit'  => array('href'       => 'index.php?action=institution&function=edit&id=__id__',
                                     'capability' => checkCapabilities('institution:update', $USER->role_id, false),
                                     'icon'       => 'fa fa-edit'));
$p_view =   array('id'            => 'checkbox', 
                  'institution'   => 'Institution', 
                  'description'   => 'Beschreibung', 
                  'schooltype_id' => 'Schultyp',
                  'state_id'      => 'Bundesland/Region',
                  'de'            => 'Land',
                  'creation_time' => 'Erstellungsdatum',
                  'username'      => 'Administrator',
                  'p_options'     => $p_options);
$institution = new Institution();
setPaginator('institutionP', $TEMPLATE, $institution->getInstitutions('all', 'institutionP'), 'in_val', 'index.php?action=institution', $p_view); //set Paginator   