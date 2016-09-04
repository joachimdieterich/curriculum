<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_preview_user.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.05.31 19:51
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
$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];

switch ($func) {
    case 'full':   $u       = new User();
                   $u->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));                  
        break;
    
    default:
        break;
}

$content    = '';  
/* Institutions/Roles */
$content   .= Render::box_widget(array('widget_title' => 'Institutionen',
                                       'widget_desc'  => 'Instituion | Rolle',
                                       'data'         => $u->institutions,
                                       'label'        => 'institution', 
                                       'badge'        => 'role',
                                       'bg_icon'      => 'fa fa-institution'));
/* Curricula / groups */
$content   .= Render::box_widget(array('widget_title' => 'Lehrpläne',
                                       'widget_desc'  => 'Lehrpläne | Lerngruppe',
                                       'data'         => $u->enrolments,
                                       'label'        => 'curriculum', 
                                       'badge'        => 'groups',
                                       'bg_icon'      => 'fa fa-th',
                                       'bg_color'     => 'yellow',
                                       'href'         => 'index.php?action=view&curriculum_id=__id__&group=__group_id__'));
$groups         = new Group(); 
/* Groups / Institution */
$content   .= Render::box_widget(array('widget_title' => 'Lerngruppe',
                                       'widget_desc'  => 'Lerngruppe | Institution',
                                       'data'         => $groups->getGroups('user', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)),
                                       'label'        => 'group', 
                                       'badge'        => 'institution_id',
                                       'bg_icon'      => 'fa fa-group',
                                       'bg_color'     => 'purple'));
 
$html = Form::modal(array('title'   => 'Überblick über den Benutzer <strong>'.$u->firstname.' '.$u->lastname.'</strong> ('.$u->username.')',
                          'content' => $content));

echo json_encode(array('html'=>$html));