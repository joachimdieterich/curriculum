<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_terminalObjective.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.06.01 17:09
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

    /*public function getSubjects($paginator =''){
        global $USER;
        $order_param    = orderPaginator($paginator, array('id' => 'sub',
                                                           'subject' => 'sub',
                                                        'description'    => 'sub',
                                                        'subject_short'  => 'sub',
                                                        'institution'    => 'ins')); 
       
        $subjects       = array();
        $db             = DB::prepare('SELECT SQL_CALC_FOUND_ROWS sub.*, ins.institution 
                                       FROM subjects AS sub, institution AS ins 
                                       WHERE (sub.institution_id  = ANY (SELECT institution_id FROM institution_enrolments WHERE institution_id = ins.id AND user_id = ?) OR sub.institution_id = 0)
                                       AND sub.institution_id= ins.id '.$order_param);
        $db->execute(array($USER->id));
        while($result = $db->fetchObject()) { 
                $this->id                   = $result->id;
                $this->subject              = $result->subject;
                $this->subject_short        = $result->subject_short;
                $this->description          = $result->description;
                $this->creation_timestamp   = $result->creation_time;
                $this->creator_id           = $result->creator_id;
                $this->institution_id       = $result->institution_id;
                $this->institution          = $result->institution;
                $subjects[] = clone $this;
        } 
        if ($paginator != ''){ 
            set_item_total($paginator); //set item total based on FOUND ROWS()
        }
        if (isset($subjects)) {    
            return $subjects;
        } else {return $result;}
    }*/



$base_url   = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER                   = $_SESSION['USER'];
$terminal_objective_id  = '';
$terminal_objective     = '';
$description            = '';
$curriculum_id          = '';
$reference              = '';
$color                  = '#3cc95b';
$error                  = '';
$ter_objective          = new TerminalObjective(); 
$func                   = $_GET['func'];
$type_id = 1;           // Dient als Vorauswahl für die Type-Selection
switch ($func) {
    case 'edit':    $ter_objective->id            = $_GET['id'];
                    $terminal_objective_id        = $ter_objective->id;
                    $ter_objective->load();                                 //Läd die bestehenden Daten aus der db
                    foreach ($ter_objective as $key => $value){
                        $$key = $value;
                    }
                    
                    if (isset($CFG->settings->repository->omega)){ // prüfen, ob omega Repository Plugin vorhanden ist. //todo global solution for plugins
                        $reference  = $CFG->settings->repository->omega->getReference('terminal_objective', $ter_objective->id);
                    }
                    $header                       = 'Thema bearbeiten';           
        break;
    case 'new':     $ter_objective->curriculum_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $curriculum_id                = $ter_objective->curriculum_id;
                    $header                       = 'Thema hinzufügen';
        break;
    
    default:
        break;
}

/* if validation failed, get formdata from session*/
if (isset($_SESSION['FORM'])){
    if (is_object($_SESSION['FORM'])) {
        foreach ($_SESSION['FORM'] as $key => $value){
            $$key = $value;
    }
}
}

$content = '<form id="form_terminal_objective" method="post" action="../share/processors/fp_terminal_objective.php">
<input type="hidden" name="terminal_objective_id" id="terminal_objective_id" value="'.$terminal_objective_id.'"/> 
<input type="hidden" name="curriculum_id" id="curriculum_id" value="'.$curriculum_id.'"/>
<input type="hidden" name="func" id="func" value="'.$func.'"/>'; 
$content .= Form::input_textarea('terminal_objective', 'Thema', $terminal_objective, $error, 'z.B. ');
$content .= Form::input_textarea('description', 'Beschreibung', $description, $error, 'z.B. ');
$content .= Form::input_select('type_id', 'Typ', $ter_objective->getType(), 'type', 'id', $type_id , $error, '$(\'#form_terminal_objective_submit_btn\').html($( \'#type_id option:selected\' ).text()+\' speichern\');');
$content .= Form::input_text('reference', 'Externe Referenz', $reference, $error, 'Beschreibung');
$content .= Form::input_color(array('id' => 'color', 'rgb' => $color, 'error' => $error));
$content .= '</form>';
$footer   = '<button id="form_terminal_objective_submit_btn" type="submit" class="btn btn-primary pull-right" onclick="document.getElementById(\'form_terminal_objective\').submit();"><i class="fa fa-floppy-o margin-r-5"></i>'.$header.'</button>';

$html     = Form::modal(array('title'     => $header,
                              'content'   => $content, 
                              'f_content' => $footer));  

$script = "<script id='modal_script'>
        $.getScript('".$CFG->smarty_template_dir_url."plugins/colorpicker/bootstrap-colorpicker.min.js', function (){
        $('.color-picker').colorpicker();
        });</script>";
echo json_encode(array('html'=> $html, 'script' => $script));