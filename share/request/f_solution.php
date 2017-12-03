<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_solution.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.12.02 15:16
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
global $USER, $PAGE, $CFG;

$USER       = $_SESSION['USER'];
$edit       = checkCapabilities('file:editMaterial',    $USER->role_id, false, true); // DELETE / edit anzeigen
$header     = 'Material';
$m_license_icon = null; //to prevent error logs
$file       = new File();
$repo       = get_plugin('repository', 'sodis');
$func       = filter_input(INPUT_GET, 'func', FILTER_UNSAFE_RAW);
$content    = '';
switch ($func) {
    case 'solution':    $header                 = 'Eingereichte Lösungen';
                        $course                 = new Course();
                        $course->curriculum_id  = filter_input(INPUT_GET, 'curriculum_id', FILTER_VALIDATE_INT);
                        $members                = $course->members('group_id', filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT));
                        $user_ids               = implode(", ", array_column($members, 'id')); //require php 5.5
       
                        $solution_content       = new Content();
                        $solutions              = $solution_content->get('solution', filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), 'ORDER by ct.timecreated ASC', $user_ids);
         break;                
    default:
        break;
}

if (!$solutions){
    $content .= 'Es gibt keine Einreichung zum gewählten Lernziel.';
} else {
    /* Tab header */
    $file_context_count[1] = 0; // counter for file_context 1
    $file_context_count[2] = 0; // counter for file_context 2
    $file_context_count[3] = 0; // counter for file_context 3
    $file_context_count[4] = 0; // counter for file_context 4
    
    for($i = 0; $i < count($solutions); $i++) {
        $file_context_count[$solutions[$i]->file_context]++;
    }
    $content .= '<div class="nav-tabs-custom">';
    $active   = array( '1' => '', '2' => '', '3' => '','4' => '');
    foreach ($file_context_count as $key => $value) { // mark first tab with files as "active"
        if ($value > 0){
            $active[$key] = 'active';
            break;
        } else {
            $active[$key] = '';
        }
    }
    $content .= '<ul class="nav nav-tabs">';
    if ($file_context_count[1] != 0){
        $content .= '<li class="'.$active[1].'"><a href="#f_context_1" data-toggle="tab" >Global <span class="label label-primary">'.$file_context_count[1].'</span></a></li>';
    }  
    if ($file_context_count[2] != 0){
        $content .= '<li class="'.$active[2].'"><a href="#f_context_2" data-toggle="tab" >Institution <span class="label label-primary">'.$file_context_count[2].'</span></a></li>';
    }
    if ($file_context_count[3] != 0){
        $content .= '<li class="'.$active[3].'"><a href="#f_context_3" data-toggle="tab" >Gruppe <span class="label label-primary">'.$file_context_count[3].'</span></a></li>';
    }
    if ($file_context_count[4] != 0){
        $content .= '<li class="'.$active[4].'"><a href="#f_context_4" data-toggle="tab" >Persönlich <span class="label label-primary">'.$file_context_count[4].'</span></a></li>';
    }
    
    $content .='</ul>';
    /* tab content*/
    $content .='<div class="tab-content">';
    
    $file_context = 1;
    $s_boxes = '';
    for($i = 0; $i < count($solutions); $i++) {
        if ($solutions[$i]->file_context >= $file_context){ 
            $file_context       = $solutions[$i]->file_context+1; //file_context auf nächstes Level setzen
        }
        $s_boxes .= render_online_solution($solutions[$i]);
        /* context box */   
        /* generate tabs for each file context*/
        $close = false;
        if (count($solutions) == ($i+1) OR $solutions[$i+1]->file_context >= $file_context){ 
            $close = true;
        } 
        
        if ($close == true AND $s_boxes != ''){ //close file_context box // only generate tab-pane when there are $solutions (m_boxes)
            $content   .='<div class="tab-pane';
            if ($active[$file_context-1] == 'active' ){
                $content   .=' active';
            }
            $content   .='" id="f_context_'.($file_context-1).'">'.$s_boxes.'</div>';
            unset($s_boxes);
            $s_boxes = '';
        } 
    }
    
    $content   .='</div><!-- /.tab-content -->
                        </div><!-- /.nav-tab-custom -->';
}


if (filter_input(INPUT_GET, 'target', FILTER_SANITIZE_STRING)){
    $target = filter_input(INPUT_GET, 'target', FILTER_SANITIZE_STRING);
} else { $target = 'popup'; }
$html     = Form::modal(array('target'   => 'null',
                                 'title' => $header, 
                               'content' => $content, 
                            'background' => '#ecf0f5'));  
echo json_encode(array('html'=> $html, 'target' => $target));


function render_online_solution($sol){
    $c  = '<div class="row">
           <div class="col-xs-12 pull-left">
           <dt>'.$sol->creator.'<dd>'.$sol->timecreated.'</dd></dt>
           <br><dt>'.$sol->title.'<dd>'.$sol->content.'</dd></dt>';
    $c .= '</div></div><hr style="clear:both;">';
    
    return $c;
}