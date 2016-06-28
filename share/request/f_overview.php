<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_overview.php
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
$content   .= '<div class="col-md-6 ">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-primary">
                <h3 class="widget-user-username">Institutionen</h3>
                <h5 class="widget-user-desc">Instituion | Rolle</h5>
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">';
                foreach($u->institutions AS $i){
                    $content   .= '<li><a href="#">'.$i->institution.' <span class="pull-right badge bg-geen">'.$i->role.'</span></a></li>';
                } 
 $content   .= '</ul>
              </div>
            </div><!-- /.widget-user -->
        </div><!-- /.col -->';
/* Curricula / groups */
$content   .= '<div class="col-md-6 ">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-yellow">
                <h3 class="widget-user-username">Kurse</h3>
                <h5 class="widget-user-desc">Lehrpläne | Gruppe</h5>
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">';
                if (isset($u->enrolments)){
                    foreach($u->enrolments AS $e){
                        $content   .= '<li><a href="index.php?action=view&curriculum_id='.$e->id.'&group='.$e->group_id.'">'.$e->curriculum.' <span class="pull-right badge bg-geen">'.$e->groups.'</span></a></li>';
                    }
                }     
 $content   .= '</ul>
              </div>
            </div><!-- /.widget-user -->
        </div><!-- /.col -->
        </div>';

$html = Form::modal(array('title'   => 'Übersicht über den Benutzer <strong>'.$u->firstname.' '.$u->lastname.'</strong> ('.$u->username.')',
                          'content' => $content));

echo json_encode(array('html'=>$html));