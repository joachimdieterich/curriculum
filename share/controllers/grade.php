<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename grade.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.08 13:26
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
global $USER, $TEMPLATE;

if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case 'new':   checkCapabilities('grade:add',      $USER->role_id);   // USER berechtigt?
                        $TEMPLATE->assign('showForm',       true); 
            break;
        case 'edit':  checkCapabilities('grade:update',   $USER->role_id);   // USER berechtigt?
                        $TEMPLATE->assign('showForm',       true); 
                        $TEMPLATE->assign('editBtn',        true); 

                        $edit_grade         = new Grade();
                        $edit_grade->id     = filter_input(INPUT_GET, 'id',         FILTER_VALIDATE_INT);
                        $edit_grade->load();
                        $TEMPLATE->assign('institution_id', $edit_grade->institution_id);
                        assign_to_template($edit_grade);
            break;
        default: break;
    }
}

if($_POST ){
    switch ($_POST) {
       case isset($_POST['add']):   
       case isset($_POST['update']):
                                    $new_grade = new Grade();
                                    if (isset($_POST['id'])){
                                        $new_grade->id         = filter_input(INPUT_POST, 'id',             FILTER_VALIDATE_INT);    
                                    }
                                    $new_grade->grade          = filter_input(INPUT_POST, 'grade',          FILTER_SANITIZE_STRING);
                                    $new_grade->description    = filter_input(INPUT_POST, 'description',    FILTER_SANITIZE_STRING);
                                    $new_grade->creator_id     = $USER->id;
                                    $new_grade->institution_id = filter_input(INPUT_POST, 'institution_id',    FILTER_VALIDATE_INT);

                                    $gump = new Gump();                             /* Validation */
                                    $sanitized_post = $gump->sanitize($_POST);      //sanitize $_POST
                                    $gump->validation_rules(array(
                                    'grade'         => 'required',
                                    'description'   => 'required'
                                    ));
                                    $validated_data = $gump->run($sanitized_post);
                                    if($validated_data === false) {                 /* validation failed */
                                        assign_to_template($new_grade);
                                        $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                                        $TEMPLATE->assign('showForm', true); 
                                    } else {                                        /* validation successful */
                                        if (isset($_POST['add']))   { $new_grade->add(); }
                                        if (isset($_POST['update'])){ $new_grade->update($USER->id); }       
                                    }   
            break;
        default: break;
    }   
}
                                       
/****** END POST / GET ******/
$grade                  = new Grade();
$grade->institution_id  = $USER->institutions;
$TEMPLATE->assign('page_title', 'Klassenstufen');  

$p_options = array('delete' => array('onclick' => "del('grade',__id__, $USER->id);", 
                                     'capability' => checkCapabilities('grade:delete', $USER->role_id, false)),
                    'edit'  => array('href'    => 'index.php?action=grade&function=edit&id=__id__'),
                                     'capability' => checkCapabilities('grade:update', $USER->role_id, false));
$p_config =   array('id'         => 'checkbox',
                    'grade'       => 'Klassenstufe', 
                    'description' => 'Beschreibung', 
                    'institution' => 'Institution', 
                    'p_options'   => $p_options);
setPaginator('gradeP', $TEMPLATE, $grade->getGrades('gradeP'), 'gr_val', 'index.php?action=grade', $p_config); 