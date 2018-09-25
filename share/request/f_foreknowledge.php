<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename _foreknowledge.php
* @copyright 2015 Joachim Dieterich
* @author Daniel Behr
* @date 2018.09.18 16:28
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
$base_url = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER     = $_SESSION['USER'];
$func     = $_GET['func']; //not used yet
$content = '';

#$script = '<script id="modal_script" src="{$media_url}scripts/d3.v3.min.js"></script> <script id="modal_script" src="{$media_url}scripts/treeDiagramm.js"></script>';


switch ($func){
    case "show": 
        $foreknowledge = Foreknowledge::getForeknowledge(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), $_SESSION['CONTEXT']['enabling_objective']->id);

        $header = "Benötigtes Vorwissen";
        $contentTer = "Benötigte Themen<br> <div class = 'row'> <div class = 'col-xs-12'>";
        $contentEna = "Benötigte Ziele<br><div class = 'row'> <div class = 'col-xs-12'>";
        foreach($foreknowledge as $f){
            error_log("HIER bin ich");
            switch ($f->foreknowledge_context_id){
                case $_SESSION['CONTEXT']['enabling_objective']->id:
                    $ena = new EnablingObjective();
                    $ena->id = $f->foreknowledge_id;
                    $ena->load();
                    //$content .= $f->foreknowledge_id . "+++" . $f->foreknowledge_context_id;//TODO: Am Ende löschen!
                    $contentEna .= RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $USER->id, "format" => "reference"]);

                    break;
                case $_SESSION['CONTEXT']['terminal_objective']->id:
                    $ter = new TerminalObjective();
                    $ter->id = $f->foreknowledge_id;
                    $ter->load();
                    //$content .= $f->foreknowledge_id . "+++" . $f->foreknowledge_context_id;//TODO: Am Ende löschen!
                    $contentTer .= RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $USER->id, "format" => "reference"]);

                    break;
                case $_SESSION['CONTEXT']['curriculum']->id:
                    //TODO: Curriculum
                    break;
                default:
                    break;
            }   
        }
        $content .= $contentTer . "</div></div>" . $contentEna . "</div></div>";
        $footer = '';
        break;
    
    case "set":
        $header = "Vorwissen festlegen";
        $cur        = new Curriculum();
        $ter        = new TerminalObjective();
        $ena        = new EnablingObjective();
        
        $reference_id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        $ena->id    = $reference_id;
        $ena->load();
        $type       = 'enabling_objective';
        
        $cur->id    = $ena->curriculum_id;
        $cur->load();   
        
        $ter->curriculum_id = $cur->id;
        $terminal_objectives = $ter->getObjectives('curriculum', $cur->id);
        
        $ena->curriculum_id  = $cur->id;
        $enabling_objectives = $ena->getObjectives('curriculum', $cur->id);
        
        
        $curricula  = new Curriculum();                             //Load curricula
        $curriculum_list = $curricula->getCurricula('user', $USER->id); 
        
        
        ### Start neue Darstellungsform

        $nodesToDo = array();
        $nodesDone = array();
        //topLevel
        $node_l0 = new Node();
        if (strlen($ena->enabling_objective)>20){
            $node_l0->name = substr(strip_tags($ena->enabling_objective), 0, 17) . '...';
        }else{
            $node_l0->name = $ena->enabling_objective;
        }
        
        $node_l0->parentName  = 'A';
        $node_l0->size        = 10000;
        $node_l0->link        = ''; 
        $node_l0->type        = $_SESSION['CONTEXT']['enabling_objective']->id;
        $node_l0->id          = $reference_id;
        $node_l0->children    = array();
        $node_l0->linkDistance = 0;
        $nodesToDo[] = $node_l0;
        error_log('Vor While ' . json_encode($nodesToDo));

        while (count($nodesToDo)>0) {
            $n = array_shift($nodesToDo);
            error_log('StartWhile ' . json_encode($n));
            $foreknowledge = Foreknowledge::getForeknowledge($n->id, $n->type);
            error_log('geladenes Vorwissen ' . json_encode($foreknowledge));
            foreach ($foreknowledge as $f){
                $newNode = new Node();
                error_log("ein Vorwissen " . json_encode($f));
                if ($f->foreknowledge_context_id == $_SESSION['CONTEXT']['enabling_objective']->id){ # Fall: Vorwissen ist enablObjective
                    $ena = new EnablingObjective();
                    $ena->id = $f->foreknowledge_id;
                    $ena->load();
                    $newNode->name = strip_tags($ena->enabling_objective);
                }else{ # Fall: Vorwissen ist terminalObjective
                    $ter = new TerminalObjective();
                    $ter->id = $f->foreknowledge_id;
                    $ter->load();
                    error_log("TYP " . $f->foreknowledge_id);
                    $newNode->name = strip_tags($ter->terminal_objective);
                }
                if (strlen($newNode->name)>20){
                    $newNode->name = substr($newNode->name, 0, 17) . '...';
                }
                $newNode->parentName  = $n->name;
                $newNode->size        = 10000;
                $newNode->link        = '';
                $newNode->type        = $f->foreknowledge_context_id;
                $newNode->id          = $f->foreknowledge_id;
                $newNode->linkDistance= $n->linkDistance + 1;
                $n->children[]        = $newNode;
                $nodesToDo[]          = $newNode;
            }
        }
        
        error_log(json_encode($node_l0));
        #$content .= json_encode($node_l0);
        
        ### Ende neue Darstellungsform
        
        
        
        /* Alte Darstellungsform
        #Anzeige des bisher festgelegten Vorwissens
        $foreknowledge = Foreknowledge::getForeknowledge(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), $_SESSION['CONTEXT']['enabling_objective']->id);
        $contentTer = "<p>Benötigte Themen<br> <div class = 'row'> <div class = 'col-xs-12'></p>";
        $contentEna = "<p>Benötigte Ziele<br><div class = 'row'> <div class = 'col-xs-12'></p>";
        foreach($foreknowledge as $f){
            error_log("HIER bin ich");
            switch ($f->foreknowledge_context_id){
                case $_SESSION['CONTEXT']['enabling_objective']->id:
                    $ena = new EnablingObjective();
                    $ena->id = $f->foreknowledge_id;
                    $ena->load();
                    //$content .= $f->foreknowledge_id . "+++" . $f->foreknowledge_context_id;//TODO: Am Ende löschen!
                    $contentEna .= RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $USER->id, "format" => "reference"]);

                    break;
                case $_SESSION['CONTEXT']['terminal_objective']->id:
                    $ter = new TerminalObjective();
                    $ter->id = $f->foreknowledge_id;
                    $ter->load();
                    //$content .= $f->foreknowledge_id . "+++" . $f->foreknowledge_context_id;//TODO: Am Ende löschen!
                    $contentTer .= RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $USER->id, "format" => "reference"]);

                    break;
                case $_SESSION['CONTEXT']['curriculum']->id:
                    //TODO: Curriculum
                    break;
                default:
                    break;
            }   
        }
        $content .= $contentTer . "</div></div>" . $contentEna . "</div></div>";
        */
        $content .= "<div id ='chart'> </div>";
        # Eingabeformular
        $content .= "<form id='form_foreknowledge'   method='post' action='../share/processors/fp_foreknowledge.php'>";
        $content .= "<input type = 'hidden' name = 'qualification_id' value = '" . $reference_id . "'/>";
        
        $content .= Form::input_select('curriculum', 'Lehrplan', $curriculum_list, 'curriculum', 'id', $cur->id, null, $onchange= 'getMultipleValues([\'objectives\', this.value, \'terminal\', \'terminal_objective\'], [\'objectives\', this.value, \'enable\', \'enabling_objective_from_curriculum\']);', $placeholder ='---', $class_left='col-sm-3', $class_right='col-sm-9', $disabled = '');
        
        #input_select($id, $label, $select_data, $select_label, $select_value, $input, $error, $onchange= '', $placeholder ='---', $class_left='col-sm-3', $class_right='col-sm-9', $disabled = '');
        $content .= Form::input_select('terminal', 'Thema', $terminal_objectives, 'terminal_objective', 'id', null, null, $onchange= 'getValues(\'objectives\', this.value, \'enable\', \'enabling_objective_from_terminal_objective\');', $placeholder ='---', $class_left='col-sm-3', $class_right='col-sm-9', $disabled = '');
                                        
        #$content .= Form::input_select_multiple(array('id' => 'enabling_objective_id', 'label' => 'Kompetenzen/ Lernziele', 'select_data' => $enabling_objectives, 'select_label' => 'enabling_objective', 'select_value' => 'id', 'input' => array($enabling_objective_id), 'error' => $error)); 
        $content .= Form::input_select_multiple(array('id' => 'enable', 'label' => 'Ziel', 'select_data' => $enabling_objectives, 'select_label' =>  'enabling_objective', 'select_value' => 'id', 'input' => null,'error' => null));
        
        
        $content .= "</form>";
        
        $footer = '<button type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_foreknowledge\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>Vorwissen hinzufügen</button>';        
}

$html     = Form::modal(array('target'    => 'null',
                              'title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

#echo json_encode(array('html'=>$html, 'script' => $script));
echo json_encode(array('html'=>$html));