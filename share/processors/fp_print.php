<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_print.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.10.17 21:49
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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER, $CFG;
$USER   = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$func       = filter_input(INPUT_POST, 'func',  FILTER_SANITIZE_STRING);
$id         = filter_input(INPUT_POST, 'id',    FILTER_VALIDATE_INT);
$content    = ''; // prevent blank screen on empty results
$pagebreak  = false;
$files      = new File();

switch ($func) {
    case 'curriculum':  $ter_obj  = new TerminalObjective();         //load terminal objectives
                        $ter      = $ter_obj->getObjectives('curriculum', $id, true);    
                        if (isset($_POST['print_curriculum'])){ /* print curriculum content*/
                                $cur      = new Curriculum();
                                $cur->id  = $id;
                                $cur->load();
                                $content .= '<h1>'.$cur->curriculum.'</h1>';
                                $content .= '<img src="'.$CFG->access_id_url.$cur->icon_id.'"  style="width:100%;" ></p>';
                                $content .= '<small>'.$cur->description.'</small>';
                            
                            foreach ($ter as $ter_value){
                                $content .= '<pagebreak orientation="landscape"/><div style="padding:5px;background:'.$ter_value->color.'">'.strip_tags($ter_value->terminal_objective).'</div>';
                                $content .= '<table style="width:100%; border-collapse: collapse;border: 1px solid #EBEBEB;">';
                                $content .= '<thead><tr style="background: #F2DBDB"><td>Lernziel / Kompetenz</td><td>Bezüge / Überfachliche Bezüge</td><td>Materialien</td></tr></thead>';
                                $content    .= '<tr><td valign="top" style="width:30%;border: 1px solid '.$ter_value->color.';"><small>'.strip_tags($ter_value->description).'</small></td>';
                                /* Bezüge */
                                $content    .= '<td valign="top" style="width:40%;border: 1px solid '.$ter_value->color.';"><small>'. render_reference('terminal_objective', $ter_value->id).'</small></td>';
                                /*  Material  */
                                $content    .= '<td valign="top" style="width:30%;border: 1px solid '.$ter_value->color.';"><small>'.render_material('terminal_objective', $ter_value->id).'</small></td>';
                                $content    .= '</td></tr>';
                                
                                foreach($ter_value->enabling_objectives AS $ena_value){
                                    $content    .= '<tr><td valign="top" style="width:30%;border: 1px solid #EBEBEB;"><small>'.strip_tags($ena_value->enabling_objective).'</small></td>';
                                    /* Bezüge */
                                    $content    .= '<td valign="top" style="width:40%;border: 1px solid #EBEBEB;"><small>'.render_reference('enabling_objective', $ena_value->id).'</small></td>';
                                    /*  Material  */
                                    $content    .= '<td valign="top" style="width:30%;border: 1px solid #EBEBEB;"><small>'.render_material('enabling_objective', $ena_value->id).'</small></td>';;
                                    
                                    $content    .= '</td></tr>';
                                }
                                $content .= '</table>';
                            }
                            
                            if (count($ter) > 0){
                                $content .= '<pagebreak orientation="portrait"/>';
                            }
                        }
                        if (isset($_POST['print_content'])){ /* print curriculum content*/
                            if ($pagebreak){
                                $content .= '<pagebreak>';
                                $pagebreak = false;
                            }
                            $content           .= '<h1>Digitalisierte Texte des Lehrplans</h1>';
                            $con                = new Content();
                            $content_entries    = $con->get('curriculum', $id );
                            foreach ($content_entries AS $content_entry){
                                $content .= '<p><strong>'.$content_entry->title.'</strong><br />';
                                $content .= ''.$content_entry->content.'</p>';
                            }
                            if (isset($content_entry)){
                                if (count($content_entry) > 0){
                                    $pagebreak = true;   
                                }
                            }
                        }
                        if (isset($_POST['print_reference'])){ // print reference
                           $content .= RENDER::print_reference(array('id' => $id, 'pagebreak' => $pagebreak, 'quotes' => true));
                        }
                        if (isset($_POST['print_glossar'])){ // print glossar
                            if ($pagebreak){
                                $content .= '<pagebreak>';
                                $pagebreak = false;
                            }
                            $content .= '<h1>Glossar</h1>';
                            $content .= '<columns column-count="2" vAlign="justify" column-gap="25" />';
                            $glossar_entries = json_decode(PRINTER::glossar(array('curriculum_id' => $id)));
                            foreach ($glossar_entries AS $gl_entry){
                                $content .= '<p><strong>'.$gl_entry->title.'</strong><br />';
                                $content .= ''.$gl_entry->content.'</p><br />';
                            }
                            if (count($glossar_entries) > 0){
                                $pagebreak = true;   
                            }
                        }
                        if (isset($_POST['print_files'])){ // print files
                            if ($pagebreak){
                                $content .= '<pagebreak>';
                                $pagebreak = false;
                            }
                            $content .= '<h1>Angehängte Dateien</h1>';
                            $content .= '<columns column-count="1" vAlign="justify" column-gap="0" />';
                            
                            $files_entries = $files->getFiles('curriculum', $id, '', array('cur'=> true));
                            foreach ($files_entries AS $fl_entry){
                                $content .= '<p><strong>'.$fl_entry->title.'</strong><br />';
                                $content .= ''.$fl_entry->description.'</p><br />';
                                $content .= '<img src="'.$CFG->access_id_url.$fl_entry->id.'"  style="width:100%;" ></p>';
                                $license  = new License();
                                $l        = $license->get();
                                $content .= '<p ><small>Autor: '.$fl_entry->author.' | Lizenz: '.$l[$fl_entry->license]->license.'</p>';
                            }
                            if (count($files_entries) > 0){
                                $pagebreak = true;   
                            }
                        }
                        
                        if (isset($_POST['print_material'])){ // print files
                            if ($pagebreak){
                                $content .= '<pagebreak>';
                                $pagebreak = false;
                            }
                            $content .= '<h1>Materialien</h1>';
                            $content .= '<columns column-count="1" vAlign="justify" column-gap="0" />';
                            foreach ($ter AS $ter_entries){
                                $content    .= '<div style="padding:5px;background:'.$ter_value->color.'">'.strip_tags($ter_entries->terminal_objective).'</div>';
                                $ena_obj                = new EnablingObjective();         //load enabling objectives
                                $ena_obj->curriculum_id = $id;
                                $ena                    = $ena_obj->getObjectives('terminal_objective', $ter_entries->id);
                                foreach ($ena as $ena_entries) {
                                    $content    .= '<div style="padding:5px;background:'.$ter_value->color.'">'.strip_tags($ena_entries->enabling_objective).'</div>';
                                    
                                    $material_entries  = $files->getFiles('enabling_objective', $ena_entries->id, '', array('externalFiles' => false));
                                    $content    .= '<ul>';
                                        foreach($material_entries AS $f){
                                            $content .= '<li>'.$f->title.' ('.$f->filename.')</li>';
                                        }
                                    $content    .= '</ul>';
                                }
                            }
                            if (count($material_entries) > 0){
                                $pagebreak = true;   
                            }
                        }
                        
        break;

    default:
        break;
}

$_SESSION['FORM']                  = null;                     // reset Session Form object

$_SESSION['PAGE']->print          = new stdClass();
$_SESSION['PAGE']->print->content = $content;


header('Location:'.$_SESSION['PAGE']->target_url);

function render_material($dependency, $id){
    switch ($dependency) {
        case 'enabling_objective':  $content = '';
                                    $files             = new File();
                                    $material_entries  = $files->getFiles('enabling_objective', $id, '', array('externalFiles' => false));
                                    $content    .= '<small><ul>';
                                        foreach($material_entries AS $f){
                                            $content .= '<li>'.$f->title.' ('.$f->filename.')</li>';
                                        }
                                    $content    .= '</ul></small>';
            break;

        default:
            break;
    }
    if (isset($content)) return $content;
    
}
function render_reference_entry($ref){
    $content  = '<p><small><strong>Lehrplan</strong><br> '.$ref->curriculum_object->curriculum.' ('.$ref->curriculum_object->subject.', '.$ref->grade.')<br>
                 <strong>Thema</strong><br> '.strip_tags($ref->terminal_object->terminal_objective).'<br>';
    if ($ref->context_id == $_SESSION['CONTEXT']['enabling_objective']->context_id) {
        $content .= ' <strong>Lernziel/Kompetenz</strong><br> '.strip_tags($ref->enabling_object->enabling_objective).'<br>';
    }
    
    if (isset($ref->content_object->content)){
        if ($ref->content_object->content != ''){
            $content .= '<small><strong>Hinweise</strong></small><br>'.strip_tags($ref->content_object->content).'<br>';
        }
    }
    $content .= '</p>';
    return $content;
}

function render_reference($dependency, $id){
    switch ($dependency) {
        case 'terminal_objective':
            $reference  = new Reference();
            $references = $reference->get('reference_id', $_SESSION['CONTEXT']['terminal_objective']->context_id, $id);
            $content    = '';
            $count_ref  = count($references);
            $i          = 0;
            foreach ($references as $ref) {
                $i++;
                $content .= render_reference_entry($ref);
                if ($i < $count_ref) { $content .= '<hr style="width:100%; padding-bottom:5px; color: #EBEBEB;">'; }
            } 
           break;
        case 'enabling_objective':
            $reference = new Reference();
            $references = $reference->get('reference_id', $_SESSION['CONTEXT']['enabling_objective']->context_id, $id);
            $content = '';
            $count_ref = count($references);
            $i = 0;
            foreach ($references as $ref) {
                $i++;
                $content .= render_reference_entry($ref);
                if ($i < $count_ref) { $content .= '<hr style="width:100%; padding-bottom:5px; color: #EBEBEB;">'; }
            } 
           break;
        default:
           break;
    }
    if (isset($content)) return $content;
}