<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_reference_view.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.09.10 08:42
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
$USER           = $_SESSION['USER'];
$func           = $_GET['func'];
$schooltype_id  = null; 
$subject_id     = null; 
$curriculum_id  = null; 
$grade_id       = null; 
$content        = '';

switch ($func) {
    case 'terminal_objective':  $ter        = new TerminalObjective();
                                $ter->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $ter->load();  
                                $reference  = new Reference();
                                $references = $reference->get('reference_id', $_SESSION['CONTEXT']['terminal_objective']->context_id, $ter->id);
                                
                                $content    .= 'Zum Lernziel / Zur Kompetenz <strong>'.$ter->terminal_objective.'</strong> wurden die folgenden Bezüge gefunden:<br><hr>';
                                if (count($references) == 0){
                                    $content    .= 'Keine Bezüge vorhanden.';
                                }
                                /* FILTER */
                                $content .= render_filter();
                                foreach ($references as $ref) {
                                    $content .= render_reference_entry($ref, $_SESSION['CONTEXT']['terminal_objective']->context_id);
                                }
        break;
    case 'enabling_objective':  $ena        = new EnablingObjective();
                                $ena->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $ena->load();  
                                $reference  = new Reference();
                                $references = $reference->get('reference_id', $_SESSION['CONTEXT']['enabling_objective']->context_id, $ena->id);
                                
                                $content    .= 'Zum Lernziel / Zur Kompetenz <strong>'.$ena->enabling_objective.'</strong> wurden die folgenden Bezüge gefunden:<br><hr>';
                                if (count($references) == 0){
                                    $content    .= 'Keine Bezüge vorhanden.';
                                } 
                                /* FILTER */
                                $content .= render_filter();
                                foreach ($references as $ref) {
                                    $content .= render_reference_entry($ref, $_SESSION['CONTEXT']['enabling_objective']->context_id);
                                }
        break;
   
    default:
        break;
}


$html = Form::modal(array('target' => 'null',
                          'title'   => 'Bezüge zu anderen Lehr- /Rahmenplänen',
                          'content' => $content));

echo json_encode(array('html'=>$html));

function render_filter($schooltype_id  = null, $subject_id = null, $curriculum_id = null, $grade_id = null){
    global $USER;
    $c    = '<div class="row">';
    $schooltypes = new Schooltype();  // Load schooltype 
    $c    .= '<span class="col-sm-3 pull-left">'.Form::input_select('schooltype_id', '', $schooltypes->getSchooltypes(), 'schooltype', 'id', $schooltype_id , null,'', 'Nach Ausbildungsrichtung filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $subjects                   = new Subject();                                                      
    $subjects->institution_id   = $USER->institutions;
    $c     .= '<span class="col-sm-3 pull-left">'.Form::input_select('subject_id', '', $subjects->getSubjects(), 'subject, institution', 'id', $subject_id , null, '', 'Nach Fach filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $cur          = new Curriculum();
    $curriculum   = $cur->getCurricula('user', $USER->id);
    $c     .= '<span class="col-sm-3 pull-left">'.Form::input_select('curriculum_id', '', $curriculum, 'curriculum', 'id', $curriculum_id , null, '', 'Nach Lehr-/Rahmenplan filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $grades       = new Grade();    //Load Grades
    $c     .= '<span class="col-sm-3 pull-left">'.Form::input_select('grade_id', '', $grades->getGrades('institution',$USER->institution_id), 'grade, institution', 'id', $grade_id , null, '', 'Nach Klassenstufe filtern', 'col-xs-0', 'col-xs-12').'</span>';
    $c    .= '</div>';
    return $c;
}

function render_reference_entry($ref, $context_id){
    global $USER;
    $c  = '<div class="row">
           <div class="col-xs-12 col-sm-6 pull-left">';
            if (checkCapabilities('reference:add',    $USER->role_id, false, true)){
                $c .= '<a onclick="del(\'reference\', '.$ref->id.');" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="" data-original-title="Referenz löschen" style="margin-right:5px;"><i class="fa fa-trash"></i></a>';
                //$c .= '<a onclick="formloader(\'reference\', \'edit\', '.$ref->id.', {\'context_id\': \''.$context_id.'\'});" class="btn btn-default btn-xs pull-right" data-toggle="tooltip" title="" data-original-title="Referenz editieren" style="margin-right:5px;"><i class="fa fa-edit"></i></a>';
            }
            $c .= '<dt>Ausbildungsrichtung<dd>'.$ref->schooltype.'</dd></dt>
           <br><dt>Fach<dd>'.$ref->curriculum_object->subject.'</dd></dt>
           <br><dt>Lehrplan<dd>'.$ref->curriculum_object->curriculum.'</dd></dt>
           <br><dt>Klassenstufe<dd>'.$ref->grade.'</dd></dt>';
    if (isset($ref->content_object->content)){
        $c .= '<br><dt>Anregungen zur Unterrichtsgestaltung ';
        if (checkCapabilities('reference:add',    $USER->role_id, false, true)){
            $c .= '<a onclick="formloader(\'content\', \'edit\','.$ref->content_object->id.');" class="btn btn-default btn-xs pull-right" style="margin-right:5px;"><i class="fa fa-edit"></i></a>';
        }
        if ($ref->content_object->content != ''){
            $c .= '<dd> '.strip_tags($ref->content_object->content).'</dd></dt>';
        } 
    } 
    $c .= '</div><div class="col-xs-12 col-sm-3"><dt>Thema/Kompetenzbereich</dt>'.Render::objective(array('format' => 'reference', 'objective' => $ref->terminal_object, 'color')).'</div>';
    if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
      $c .= '<div class="col-xs-12 col-sm-3"><dt>Lernziel/Kompetenz</dt>'.Render::objective(array('format' => 'reference', 'type' => 'enabling_objective', 'objective' => $ref->enabling_object, 'border_color' => $ref->terminal_object->color)).'</div>';
    }
    $c .= '</div><hr style="clear:both;">';
    
    return $c;
}