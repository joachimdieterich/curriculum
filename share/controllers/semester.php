<?php
/**
*  This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename semester.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
* @license: 
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

if($_GET){
    switch ($_GET) {
        case isset($_GET['edit']):  $TEMPLATE->assign('showSemesterForm', true);
                                    $TEMPLATE->assign('showeditSemesterForm', true);

                                    $edit_semester = new Semester();
                                    $edit_semester->id = $_GET['id'];
                                    $edit_semester->load();

                                    $TEMPLATE->assign('id',             $edit_semester->id);
                                    $TEMPLATE->assign('semester',       $edit_semester->semester );                
                                    $TEMPLATE->assign('description',    $edit_semester->description);
                                    $TEMPLATE->assign('begin',          $edit_semester->begin);
                                    $TEMPLATE->assign('end',            $edit_semester->end);
            break;
        
        case isset($_GET['newSemester']): 
                                     $TEMPLATE->assign('showSemesterForm', true); 
            break;
        default:
            break;
    }
}

             
if($_POST){
    switch ($_POST) {
       case isset($_POST['addSemester']):
       case isset($_POST['updateSemester']):
                    $new_semester = new Semester();
                    if (isset($_POST['id'])){
                        $new_semester->id             = $_POST['id'];    
                    }
                    $new_semester->semester       = $_POST['semester'];    
                    $new_semester->description    = $_POST['description'];  
                    $new_semester->begin          = $_POST['begin'];  
                    $new_semester->end            = $_POST['end']; 
                    $new_semester->creator_id     = $USER->id;  
                    $new_semester->institution_id = $_POST['institution'];
 
                    $gump = new Gump(); /* Validation */
                    $gump->validation_rules(array(
                    'semester'        => 'required',
                    'description'     => 'required',
                    'begin'           => 'required',
                    'end'             => 'required'
                    ));
                    $validated_data = $gump->run($_POST);
                    
                    if($validated_data === false) {/* validation failed */
                            foreach($new_semester as $key => $value){
                            $TEMPLATE->assign($key, $value);
                            } 
                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                            $TEMPLATE->assign('showSemesterForm', true); 
                        } else {/* validation successful */
                            if (isset($_POST['addSemester'])){
                                $new_semester->add();
                            }
                            if (isset($_POST['updateSemester'])){
                                $new_semester->update();
                            }       
                    }  
            break;
    }   
    $TEMPLATE->assign('page_message', $PAGE->message);
}
/*******************************************************************************
 * END POST / GET 
 */ 

$TEMPLATE->assign('page_title','Lernzeiträume verwalten');
$semesters = new Semester();
$semesters->institution_id = $USER->institutions["id"]; 
setPaginator('semesterPaginator', $TEMPLATE, $semesters->getSemesters(), 'semester_list', 'index.php?action=semester'); //set Paginator

?>