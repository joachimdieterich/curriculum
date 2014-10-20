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
global $PAGE, $USER, $TEMPLATE;

if(isset($_GET['reset']) OR (isset($_POST['reset']))){
    resetPaginator('userPaginator');            
}

$showuser = false;  //zurücksetzen
$show_course = false; // zurücksetzen

$selected_curriculum = (isset($_GET['course']) && trim($_GET['course'] != '') ? $_GET['course'] : '_'); //'_' ist das Trennungszeichen 
$selected_curriculumforURL = $selected_curriculum;
$selected_user_id = (isset($_GET['userID']) && trim($_GET['userID'] != '') ? $_GET['userID'] : '');
$TEMPLATE->assign('selected_curriculum', $selected_curriculum);
$TEMPLATE->assign('selected_user_id', $selected_user_id);
list ($selected_curriculum, $selected_group) = explode('_', $selected_curriculum); //$selected_curriculum enthält curriculumid_groupid (zb. 32_24) wenn nur '_' gesetzt ist werden beide variabeln ''
$TEMPLATE->assign('sel_curriculum', $selected_curriculum); //only selected curriculum without group
$TEMPLATE->assign('sel_group_id', $selected_group); //only selected curriculum without group
if(isset($_POST['generateCertificate'])) {
    $vorlage = $_POST['certificate_html'];
    $certificate = new Pdf();
    //$certificate->content = $_POST['certificate_html'];
    $certificate->content = $vorlage;
    $certificate->curriculum_id = $_POST['sel_curriculum'];
    $certificate->user_id = $_POST['sel_user_id'];

    $certificate->generate_pdf('from_template');
}           

if ($selected_curriculum != '') { 
    $course_user = new User();
    $course_user->id = $USER->id;
    $users = $course_user->getUsers('course', $selected_curriculum);
    if (is_array($users)){
        $user_id_list = array_map(function($user) { return $user->id; }, $users); 
        setPaginator('userPaginator', $TEMPLATE, $users, 'results', 'index.php?action=certificate&course='.$selected_curriculumforURL); //set Paginator    
    } else { $showuser = true; }  
}    

/*******************************************************************************
 * End POST / GET
 */
$vorlage = '<p style="text-align: center;"><img src="../curriculumdata/userdata/102/logo.jpg" alt="" height="120" /></p>
                <div class="section">
                <div class="section">
                <div class="layoutArea">
                <div class="column">
                <p style="text-align: center;">Realschule Plus Landau | Schneiderstra&szlig;e 69 | 76829 Landau</p>
                <h1 style="text-align: center;"><strong>Zertifikat</strong></h1>
                <h2>MEDIENKOMP@SS</h2>
                <p>Sekundarstufe I</p>
                <h3>{Vorname} {Nachname}</h3>
                <p>hat erfolgreich die folgenden Module des Medienkom@sses abgeschlossen.</p>
                <!--Start-->
                <table>
                <tbody>
                <tr>
                <td>Ich kann&nbsp;</td>
                <td>mit Hilfestellung</td>
                <td>selbstst&auml;ndig</td>
                </tr>
                <tr>
                <td>{Thema}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <!--Ziel_Start-->
                <tr>
                <td>{Ziel}</td>
                <td>{Ziel_erreicht}</td>
                <td>{Ziel_offen}</td>
                </tr>
                <!--Ziel_Ende-->
                </tbody>
                </table>
                <!--Ende-->
                </div>
                </div>
                </div>
                </div>
                <p></p>
                <div class="column">
                <p>Landau, {Datum}</p>
                <p>&nbsp;</p>
                <p>__________________________________</p>
                <p>Joachim Dieterich</p>
                </div>';
$TEMPLATE->assign('certificate_html', $vorlage);

$TEMPLATE->assign('showuser', $showuser);
$TEMPLATE->assign('show_course', $show_course);


// Load courses
$courses = new Course(); 
$TEMPLATE->assign('courses', $courses->getCourse('admin', $USER->id)); 

$TEMPLATE->assign('page_title', 'Zertifikat einrichten');    
$TEMPLATE->assign('page_message', $PAGE->message);
?>