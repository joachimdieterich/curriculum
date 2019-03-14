<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename p_print.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.09.25 14:54
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
include_once(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER   = $_SESSION['USER'];
$func   = filter_input(INPUT_GET, 'func',  FILTER_SANITIZE_STRING);
$id     = filter_input(INPUT_GET, 'val',   FILTER_SANITIZE_STRING); // kein INT --> System ID -1
switch ($func) {
    case "book":                $b                  = new Book();
                                $book               = $b->get('book', $id);
                                $content            = '';
                                foreach($book AS $bo){
                                    $c              = new Content();
                                    $c->load('id', $bo->content_id);
                                    $content       .= '<strong>'.$c->title.'</strong><br><br>'.$c->content;
                                    unset($c);
                                }
        break;
    case "content":             $c            = new Content();
                                $c->load('id', $id);           
                                $content = '<strong>'.$c->title.'</strong><br><br>'.$c->content;
        break;
    
    case "certificate":         $t = new Certificate();         break;
    case "curriculum":          $t = new Curriculum();          break;
    case "grade":               $t = new Grade();               break;
    case "group":               $t = new Group();               
                                /*$t->id   = intval($id);
                                $t->load();
                                */
                                $content = Render::table(array(  'header' => array('id'   => 'id', 
                                                'group' => 'Gruppe', 
                                                'description' => 'Beschreibung', 
                                                'grade'         => '(Klassen)stufe',  
                                                'semester'      => 'Lernzeitraum',
                                                'institution'   => 'Institution / Schule',
                                                'creation_time' => 'Erstellungsdatum',
                                                'username'      => 'Erstellt von'), 
                                              'data' => $t->getGroups('group', $USER->id,'groupP'),
                                              'width_class'   => 'row col-xs-12 col-sm-6 col-md-3 col-lg-3',
                                              'style'         => 'padding-left: 20px; padding-right: 20px;',
                                              'table_class'   => 'table table-bordered'));
        break;
    case "role":                $t = new Roles();               break;      
    case "semester":            $t = new Semester();            break;
    case "subject":             $t = new Subject();             break;
    case "user":                $t = new User();                break;
    case "institution":         $t = new Institution();         break;
    case "mail":                $t = new Mail();                
                                $t->id = intval($id);
                                $t->loadMail($id);
                                $content = Printer::mail(array('mail' => array($t)));
                                break;
    case "enablingObjectives":  $t = new EnablingObjective();   break;
    case "terminalObjectives":  $t = new TerminalObjective();   break;
    case "task":                $t = new Task();                break;
    case "courseBook":          $t       = new CourseBook();       
                                $t->id   = intval($id);
                                $t->load();
                                $content = Printer::coursebook(array('coursebook' => array($t)));
                               
        break;
    case "courseBookUserComplete":  
                                $t       = new CourseBook();    
                                $cbook   = $t->get('user', null, '');
                                $content = '';
                                foreach ($cbook as $cb) {
                                    $content .= Printer::coursebook(array('coursebook' => array($cb)));  
                                }
        break;
    
    case "paginator":           $content  = '<p style="text-align:right; padding-right:15px; font-size:50%;"><img style="float:right; margin-left:5px; width:12px;" alt="" src="../public/assets/images/logo_white_bg.png"  />'.$CFG->app_title.' ('.$CFG->version.') auf '.$CFG->base_url.' </p>';
                                $content .= '<h4 style="padding-left: 15px;">'.SmartyPaginate::getTitle($id).'</h4>';
                                $content .= RENDER::table(array('width_class'   => 'col-md-12',
                                                                'cell_style'    => 'padding:8px',
                                                                'data'          => SmartyPaginate::_getData($id),
                                                                'header'        => SmartyPaginate::getVisibleColumns($id)
                                                                ));
        break;
    case "walletView":          $wallet   = new Wallet($id); //todo: layout of pdf not like walletview
                                $wallet->get('user', $USER->id);
                                $content  = '<p style="text-align:right; padding-right:15px; font-size:50%;"><img style="float:right; margin-left:5px; width:12px;" alt="" src="../public/assets/images/logo_white_bg.png"  />'.$CFG->app_title.' ('.$CFG->version.') auf '.$CFG->base_url.' </p>';
                                $content .= '<h4 style="padding-left: 15px;">'.$wallet->title.'</h4>';
                                $content .= '<p>'.$wallet->description.'</p>';
                                $i        = false;
                                
                                foreach ($wallet->content as $v) {
                                    if ($i != $v->order_id){
                                        if ($i != false) {
                                            $content .= '</div>';
                                        }
                                        $content .= '<div class="row">';
                                        $i = $v->order_id;
                                    }
                                    $content .= RENDER::wallet_content($v);
                                }
        break;
    case "reference":           switch (filter_input(INPUT_GET, 'context', FILTER_SANITIZE_STRING)) {
                                case 'enabling_objective':  $ena        = new EnablingObjective();
                                                            $ena->id    = $id;
                                                            $ena->load();  
                                                            $reference  = new Reference();
                                                            $references = $reference->get('reference_id', $_SESSION['CONTEXT']['enabling_objective']->context_id, $ena->id);

                                                            $content    = 'Zur Kompetenz <strong>'.$ena->enabling_objective.'</strong> wurden die folgenden Bezüge gefunden:<br><hr>';
                                                            if (count($references) == 0){
                                                                $content    .= 'Keine Bezüge vorhanden.';
                                                            }
                                                          
                                                            foreach ($references as $ref) {
                                                                $e = new EnablingObjective();
                                                                switch ($ref->context_id) {
                                                                    case $_SESSION['CONTEXT']['enabling_objective']->context_id:
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
                                                                                $gr     = new Grade();
                                                                                $gr->load('id', $ref->grade_id);
                                                                                $ct     = new Content();
                                                                                $ct->get('reference', $ref->id);



                                                                                $content .= '<p><strong>Ausbildungsrichtung: </strong>'.$sc->schooltype.'<br>';
                                                                                $content .= '<strong>Fach: </strong>'.$c->subject.'<br>';
                                                                                $content .= '<strong>Lehrplan: </strong>'.$c->curriculum.'<br>';
                                                                                $content .= '<strong>Klassenstufe: </strong>'.$gr->grade.'<br>';
                                                                                if (isset($ct->content)){
                                                                                    $content .= '<strong>Hinweise: </strong>'.$ct->content.'<br></p>';
                                                                                }
                                                                                $content .= '<p><strong>Thema/Kompetenzbereich: </strong>'.Render::objective(array('objective' => $t, 'color')).'</p>';
                                                                                $content .= '<p><strong>Kompetenz: </strong>'.Render::objective(array('type' => 'enabling_objective', 'objective' => $e, 'border_color' => $t->color)).'</p>';
                                                                                $content .= '<hr>';


                                                                        break;
                                                                    case $_SESSION['CONTEXT']['terminal_objective']->context_id:
                                                                                $t      = new TerminalObjective();
                                                                                $t->id  = $ref->reference_id;
                                                                                $t->load();
                                                                                $c      = new Curriculum();         
                                                                                $c->id  = $t->curriculum_id;
                                                                                $c->load();
                                                                                $sc     = new Schooltype();
                                                                                $sc->load('id', $c->schooltype_id);
                                                                                $gr     = new Grade();
                                                                                $gr->load('id', $ref->grade_id);
                                                                                $ct     = new Content();
                                                                                $ct->get('reference', $ref->id);

                                                                                $content .= '<div class="row">' 
                                                                                          . '<div class="col-xs-12 col-sm-6 pull-left"><dt>Ausbildungsrichtung<dd>'.$sc->schooltype.'</dd></dt>';
                                                                                $content .= '<br><dt>Fach<dd>'.$c->subject.'</dd></dt>';
                                                                                $content .= '<br><dt>Lehrplan<dd>'.$c->curriculum.'</dd></dt>';
                                                                                $content .= '<br><dt>Klassenstufe<dd>'.$gr->grade.'</dd></dt>';
                                                                                if (isset($ct->content)){
                                                                                    $content .= '<br><dt>Hinweise<dd>'.$ct->content.'</dd></dt>';
                                                                                }
                                                                                $content .= '</div><div class="col-xs-12 col-sm-3 ""><dt>Thema/Kompetenzbereich</dt>'.Render::objective(array('objective' => $t, 'color')).'</div>';
                                                                                $content .= '</div><hr style="clear:both;">';
                                                                        break;

                                                                    default:
                                                                        break;
                                                                }

                                                            }
                                    break;

                                default:
                                    break;
                            }
        break;
    default: break;
}

$_SESSION['PAGE']->print          = new stdClass();
$_SESSION['PAGE']->print->content = $content;