<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_preview_institution.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.08.19 09:57
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
    default:    $i     = new Institution(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
        break;
}

$content    = '<div class="nav-tabs-custom"> <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Lerngruppen / Personen</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Aufgaben / Notizen</a></li>
            </ul>
<div class="tab-content">
              <div class="tab-pane active" id="tab_1"><div class="row">';
$groups         = new Group(); 
/* Groups / Institution */
$content   .= Render::box_widget(array('widget_title' => 'Lerngruppe',
                                       'widget_desc'  => 'Lerngruppe',
                                       'data'         => $groups->getGroups('institution', $i->id),
                                       'label'        => 'group', 
                                       'bg_icon'      => 'fa fa-group',
                                       'bg_color'     => 'yellow'));
$users = new User();
$u = $users->userList('institution_overview', '', false, $i->id);
$content   .= Render::box_widget(array('widget_title' => 'Benutzer',
                                       'data'         => $u,
                                       'badge'        => 'role_name',
                                       'label'        => 'firstname, lastname', 
                                       'widget_desc'  => 'Mitglieder der Lerngruppe',
                                       'bg_icon'      => 'fa fa-user',
                                       'bg_color'     => 'blue'));
$content   .='</div></div><!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">';
                $t  = new Task();
$content   .= Render::todoList($t->get('institution', $i->id), 'institution', $i->id).
              '</div>
              <!-- /.tab-pane -->
             </div>
             </div><!-- /.nav-tab-custom -->';

$html = Form::modal(array('title'   => 'Überblick <strong>'.$i->institution.'</strong> ',
                          'content' => $content));

echo json_encode(array('html'=>$html));