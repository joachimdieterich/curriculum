<?php
/**
 *  This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename config.php
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

global $CFG, $USER, $TEMPLATE, $PAGE, $LOG;
  $LOG->add($USER->id, 'view', $PAGE->url, 'allConfig'); 
  $language = read_folder_directory('./../lang/', 'thisDir');
  $TEMPLATE->assign('language', $language);
  
if($_POST) {
  if (checkCapabilities('config:Institution', $USER->role_id)){
      $upd_config = new Config('institution', $USER->institutions["id"]);
                    /* user settings */
                    $upd_config->user_id                        = $_POST['user_id'];
                    $upd_config->user_semester                  = $_POST['user_semester'];
                    $upd_config->user_language                  = $language[$_POST['user_language']]['dir'];
                    $upd_config->user_paginator_limit           = $_POST['user_paginator_limit'];
                    $upd_config->user_acc_days                  = $_POST['user_acc_days'];
                    
                    /* institution settings */
                    $upd_config->institution_id                 = $_POST['institution_id'];
                    $upd_config->institution_paginator_limit    = $_POST['institution_paginator_limit'];
                    $upd_config->institution_standard_role      = $_POST['institution_std_role'];
                    $upd_config->institution_standard_country   = $_POST['institution_standard_country'];
                    $upd_config->institution_standard_state     = $_POST['institution_standard_state'];
                    $upd_config->institution_csv_size           = $_POST['institution_csv_size'];
                    $upd_config->institution_avatar_size        = $_POST['institution_avatar_size'];
                    $upd_config->institution_material_size      = $_POST['institution_material_size'];
                    $upd_config->institution_acc_days           = $_POST['institution_acc_days'];
                    $upd_config->institution_language           = $language[$_POST['institution_language']]['dir'];
                    $upd_config->institution_timeout            = $_POST['institution_timeout'];
                    
                    $gump = new Gump(); /* Validation */
                    $gump->validation_rules(array(
                    'user_id'                       => 'required',
                    'user_semester'                 => 'required',
                    'user_language'                 => 'required',
                    'user_paginator_limit'          => 'required|integer',
                    'user_acc_days'                 => 'required|integer',
                    'institution_id'                => 'required|integer',
                    'institution_paginator_limit'   => 'required|integer',
                    'institution_std_role'          => 'required',
                    'institution_standard_country'  => 'required|integer',
                    'institution_standard_state'    => 'required|integer',
                    'institution_csv_size'          => 'required|integer',
                    'institution_avatar_size'       => 'required|integer',
                    'institution_material_size'     => 'required|integer',
                    'institution_acc_days'          => 'required|integer',
                    'institution_language'          => 'required',
                    'institution_timeout'           => 'required|integer'
                    ));
                    $validated_data = $gump->run($_POST);
                    
                    if($validated_data === false) {/* validation failed */
                        foreach($upd_config as $key => $value){
                        $TEMPLATE->assign($key, $value);
                        } 
                        $TEMPLATE->assign('v_error', $gump->get_readable_errors()); 
                        echo 'true';
                    } else {/* validation successful */
                        if ($upd_config->update('institution')){
                            $PAGE->message[] = 'Einstellungen wurden gespeichert.';
                        }
                    }
  } else if (checkCapabilities('config:mySettings', $USER->role_id)){
      $upd_config = new Config('institution', $USER->institutions["id"]);
                    $upd_config->user_id                    = $_POST['user_id'];
                    $upd_config->user_semester              = $_POST['user_semester'];
                    $upd_config->user_language              = $language[$_POST['user_language']]['dir'];
                    $upd_config->user_paginator_limit       = $_POST['user_paginator_limit'];
                    $upd_config->user_acc_days               = $_POST['user_acc_days'];

                    $gump = new Gump(); /* Validation */
                    $gump->validation_rules(array(
                    'user_id'                  => 'required',
                    'user_semester'            => 'required',    
                    'user_language'            => 'required',
                    'user_paginator_limit'     => 'required|integer',
                    'user_acc_days'            => 'required|integer'
                    ));
                    $validated_data = $gump->run($_POST);

                    if($validated_data === false) {/* validation failed */
                        foreach($upd_config as $key => $value){
                        $TEMPLATE->assign($key, $value);
                        } 
                        $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                    } else {/* validation successful */
                            $upd_config->update('user');    
                    }
  }
} 
/************************************************************************************
 * END POST / GET 
 */   
    
    $config = new Config('institution', $USER->institutions["id"]);     //Load default values
    while (list($key, $value) = each($config)) { 
        $TEMPLATE->assign($key, $value);
    }
    $state = new State();
    $TEMPLATE->assign('countries', $state->getCountries());             //getCountries
    $state->load($config->institution_standard_country);
    $TEMPLATE->assign('states', $state->getStates());                   //getStates
    $roles = new Roles(); 
    $TEMPLATE->assign('roles', $roles->get());                          //getRoles
    $TEMPLATE->assign('byte',convertMbToByte($CFG->post_max_size));     //Dateigröße in Byte
    $TEMPLATE->assign('message',$PAGE->message);
    $TEMPLATE->assign('page_title', 'Systemeinstellungen von curriculum');
?>