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
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];

switch ($func) {
    case 'enabling_objective':  $ena        = new EnablingObjective();
                                $ena->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $ena->load();                         
        break;

    default:
        break;
}

$content    = '';
switch ($func) {
    case 'enabling_objective':   
                                if (!isset($ena->description) && !isset($ter->description)){    
                                  $content .= 'Keine Beschreibung vorhanden';
                                }
                                if ($func == 'enabling_objective'){
                                    $reference = new Reference();
                                    $references = $reference->get('reference_id', $_SESSION['CONTEXT']['enabling_objective']->context_id, $ena->id);
                                    $content    .= 'Zum Lernziel / Zur Kompetenz <strong>'.$ena->enabling_objective.'</strong> wurden die folgenden Bezüge gefunden:<br><hr>';
                                    foreach ($references as $ref) {
                                        $e = new EnablingObjective();
                                        if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id){
                                            $e->id  = $ref->reference_id;
                                            $e->load(); //todo: ? new query with get? to get all data with one query
                                            $t      = new TerminalObjective();
                                            $t->id  = $e->terminal_objective_id;
                                            $t->load();
                                            $c      = new Curriculum();         
                                            $c->id  = $e->curriculum_id;
                                            $c->load();
                                            $sc     = new Schooltype();
                                            $sc->load('id', $c->schooltype_id);
                                            $content .= '<div class="row">' 
                                                      . '<div class="col-xs-12 col-sm-6 pull-left"><dt>Ausbildungsrichtung<dd>'.$sc->schooltype.'</dd></dt>';
                                            $content .= '<br><dt>Fach<dd>'.$c->subject.'</dd></dt>';
                                            $content .= '<br><dt>Lehrplan<dd>'.$c->curriculum.'</dd></dt><br></div>';
                                            $content .= '<div class="col-xs-12 col-sm-3 ""><dt>Thema/Kompetenzbereich</dt>'.Render::objective(array('objective' => $t, 'color')).'</div>';
                                            $content .= '<div class="col-xs-12 col-sm-3 "><dt>Lernziel/Kompetenz</dt>'.Render::objective(array('type' => 'enabling_objective', 'objective' => $e, 'border_color' => $t->color)).'</div>';
                                            $content .= '</div><hr style="clear:both;">';
                                        }
                                    }
                                }
        break;
}



$html = Form::modal(array('target' => 'null',
                          'title'   => 'Bezüge zu anderen Lehr- /Rahmenplänen',
                          'content' => $content));

echo json_encode(array('html'=>$html));