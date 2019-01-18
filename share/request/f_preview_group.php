<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_preview_group.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.09.04 09:35
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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];

switch ($func) {
    case 'full':  $g       = new Group();
                  $g->load('id', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));     
        break;
    
    default:    
        break;
}
$curriculum = new Curriculum();

$content    = Render::box_widget(array('widget_title' => 'Lehrpläne',
                                       'data'         => $curriculum->getCurricula('group', $g->id),
                                       'label'        => 'curriculum', 
                                       'widget_desc'  => 'Lehrpläne der Lerngruppe',
                                       'bg_color'     => 'purple',
                                       'bg_icon'      => 'fa fa-th'));
$users      = new User();
$u_list     = $users->getGroupMembers('group', $g->id);
$list       = array();
foreach((array)$u_list AS $ul){
    $users      = new User();
    $users->load('id', $ul, false);
    $list[] = clone $users;
}
$group = $_SESSION['CONTEXT']['group']->context_id;
$content   .= Render::box_widget(array('widget_title' => 'Benutzer',
                                       'data'         => $list,
                                       'label'        => 'firstname, lastname', 
                                       'widget_desc'  => 'Mitglieder der Lerngruppe',
                                       'bg_icon'      => 'fa fa-user',
                                       'bg_badge'     => 'bg-gray ',
                                       'badge'        => true,
                                       'badge_title'  => 'ausschreiben',
                                       'onclick_badge'=> "processor('expel', 'userfromgroup', __id__, { 'ref_id': '$g->id', 'reload': 'false', 'callback': 'replaceElementByID', 'element_Id': 'w_row___id__'});"));

$html       = Form::modal(array('title'   => 'Überblick Lerngruppe <strong>'.$g->group.'</strong>',
                                'content' => $content));

echo json_encode(array('html'=>$html));
