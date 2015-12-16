<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename certificate.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2014.07.30 22:43
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
global $PAGE, $USER, $TEMPLATE;

if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case "new":     checkCapabilities('certificate:add',    $USER->role_id);
                        $TEMPLATE->assign('showForm',           true); 
                break;   
        case "edit":    checkCapabilities('certificate:add',    $USER->role_id);
                        $TEMPLATE->assign('showForm',           true);
                        $TEMPLATE->assign('editBtn',            true);

                        $edit_certificate       = new Certificate();
                        $edit_certificate->id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                        $edit_certificate->load();
                        $TEMPLATE->assign('institution_id',            $edit_certificate->institution_id);
                        assign_to_template($edit_certificate);  
                break;   
             
        default: break;
     }
}


if($_POST){
    $new_certificate = new Certificate();
    if (isset($_POST['id'])){
        $new_certificate->id         = filter_input(INPUT_POST, 'id',          FILTER_VALIDATE_INT);  
    }
    $new_certificate->certificate    = filter_input(INPUT_POST, 'certificate', FILTER_SANITIZE_STRING);
    $new_certificate->description    = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $new_certificate->template       = filter_input(INPUT_POST, 'template',    FILTER_UNSAFE_RAW);
    $new_certificate->creator_id     = $USER->id;
    $new_certificate->institution_id = filter_input(INPUT_POST, 'institution_id', FILTER_VALIDATE_INT);

    $gump = new Gump();                 /* Validation */
    $_POST = $gump->sanitize($_POST);   //sanitize $_POST
    $gump->validation_rules(array(
    'certificate'   => 'required',
    'description'   => 'required',
    'template'      => 'required'
    ));
    $validated_data = $gump->run($_POST);

    if($validated_data === false) {/* validation failed */
        assign_to_template($new_certificate);  
        $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
        $TEMPLATE->assign('showForm', true); 
    } else {/* validation successful */
        if (isset($_POST['addCertificate']))    { $new_certificate->add(); }
        if (isset($_POST['updateCertificate'])) { $new_certificate->update(); }       
    }      
} 
/******************************************************************************
 * End POST / GET
 */
$TEMPLATE->assign('page_title', 'Zertifikat einrichten');    

$certificate = new Certificate();
$certificate->institution_id = $USER->institutions;

$p_options = array('delete' => array('onclick' => "del('certificate',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('certificate:delete', $USER->role_id, false)),
                    'edit'  => array('href'    => 'index.php?action=certificate&function=edit&id=__id__'),
                                     'capability' => checkCapabilities('certificate:update', $USER->role_id, false));
$p_config =   array('certificate' => 'Titel des Zertifikat-Vorlage', 
                  'description'   => 'Beschreibung', 
                  'institution'   => 'Institution', 
                  'creation_time' => 'Erstellungs-Datum',
                  'username'      => 'Erstellt von',
                  'p_options'     => $p_options);
setPaginator('certificateP', $TEMPLATE, $certificate->getCertificates('certificateP'), 'ct_val', 'index.php?action=certificate', $p_config); //set Paginator