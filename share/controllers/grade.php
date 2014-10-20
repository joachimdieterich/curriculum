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

if (isset($_GET['function'])) {
     switch ($_GET['function']) {
        case "newGrade": 
                $TEMPLATE->assign('showGradeForm', true); 
                 break;       
        default: break;
     }
}

// edit grade
if (isset($_GET['edit'])) {
    $TEMPLATE->assign('showGradeForm',      true);
    $TEMPLATE->assign('showeditGradeForm',  true);
    
    $edit_grade = new Grade();
    $edit_grade->id = $_GET['id'];
    $edit_grade->load();
     
    $TEMPLATE->assign('id',          $edit_grade->id);
    $TEMPLATE->assign('grade',       $edit_grade->grade);                
    $TEMPLATE->assign('description', $edit_grade->description);  
}

if($_POST){
    switch ($_POST) {
       case isset($_POST['deleteGrade']):                               
                        deleteGrade($USER->id, $_POST['id']);
                        break; 

       case isset($_POST['newGrade']): 
                        $TEMPLATE->assign('showGradeForm', true); 
                        break;

       //Formulardaten verarbeiten    
       case isset($_POST['addGrade']):
       case isset($_POST['updateGrade']):
                        $new_grade = new Grade();
                        if (isset($_POST['id'])){
                            $new_grade->id         = $_POST['id'];    
                        }
                        $new_grade->grade          = $_POST['grade'];
                        $new_grade->description    = $_POST['description'];
                        $new_grade->creator_id     = $USER->id;
                        $new_grade->institution_id = $_POST['institution'];

                        $gump = new Gump(); /* Validation */
                        $gump->validation_rules(array(
                        'grade'         => 'required',
                        'description'   => 'required'
                        ));
                        $validated_data = $gump->run($_POST);
                        
                        if($validated_data === false) {/* validation failed */
                            foreach($new_grade as $key => $value){
                            $TEMPLATE->assign($key, $value);
                            } 
                            $TEMPLATE->assign('v_error', $gump->get_readable_errors());     
                            $TEMPLATE->assign('showGradeForm', true); 
                        } else {/* validation successful */
                            if (isset($_POST['addGrade'])){
                                $new_grade->add();
                            }
                            if (isset($_POST['updateGrade'])){
                                $new_grade->update($USER->id);
                            }       
                        }
                        break;                    
}
    $TEMPLATE->assign('page_message', $PAGE->message);
}
/*******************************************************************************
 * END POST / GET 
 */
$grade = new Grade();
$grade->institution_id = $USER->institutions["id"];
setPaginator('gradePaginator', $TEMPLATE, $grade->getGrades(), 'grade_list', 'index.php?action=grade'); //set Paginator
include ('./../lang/'.$USER->language.'/grade.php');                       //Wählt die gesetzte Sprache aus //muss ganz unten stehen!
?>