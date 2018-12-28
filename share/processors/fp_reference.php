<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_reference.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.09.09 14:01
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
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}

$reference = new Reference(); 

$purify                                 = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier                               = new HTMLPurifier($purify);

$reference->description = $purifier->purify(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));

$gump                  = new Gump();    /* Validation */
$_POST                 = $gump->sanitize($_POST);       //sanitize $_POST
// todo alle Regeln definieren
$gump->validation_rules(array(  
'terminal_objective_id'  => 'required',
/*'enabling_objective_id'  => 'required', */
'grade_id'      => 'required'
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'reference';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    if (isset($_POST['id'])){
        $reference->id              = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    } 
    
    $reference->grade_id            = filter_input(INPUT_POST, 'grade_id', FILTER_VALIDATE_INT);
    $reference->file_context        = filter_input(INPUT_POST, 'file_context', FILTER_VALIDATE_INT);
    $reference->context_id          = filter_input(INPUT_POST, 'context_id', FILTER_VALIDATE_INT);//$_SESSION['CONTEXT']['enabling_objective']->context_id; //By now, reference_source an only be enabling_objective
    $reference->source_context_id   = $reference->context_id;
    $reference->source_reference_id = $_POST['reference_id'];
    switch ($reference->context_id) {
        case $_SESSION['CONTEXT']['enabling_objective']->context_id: $_SESSION['anchor'] = 'ena_'.$_POST['reference_id'];           // set anchor to jump to new enabling objective
            break;
        case $_SESSION['CONTEXT']['terminal_objective']->context_id: $_SESSION['anchor'] = 'ter_'.$_POST['reference_id'];           // set anchor to jump to new terminal objective
            break;

        default:
            break;
    }
    
    if (isset($_POST['enabling_objective_id'])){
        $reference->context_id          = $_SESSION['CONTEXT']['enabling_objective']->context_id; 
        $reference->target_context_id   = $reference->context_id;
        foreach ($_POST['enabling_objective_id'] as $ena_id) { 
            $reference->target_reference_id = $ena_id; 
            if ($reference->add()){
                $_SESSION['PAGE']->message[] = array('message' => 'Referenz hinzufgefügt', 'icon' => 'fa-link text-success');
            } else {
                $_SESSION['PAGE']->message[] = array('message' => 'Referenz konnte nicht hinzugefügt werden', 'icon' => 'fa-link text-warning');
            }
        }
    } else if (isset($_POST['terminal_objective_id'])){
        $reference->context_id          = $_SESSION['CONTEXT']['terminal_objective']->context_id; 
        $reference->target_context_id   = $reference->context_id;
        $reference->target_reference_id = $_POST['terminal_objective_id'];
        if ($reference->add()){
            $_SESSION['PAGE']->message[] = array('message' => 'Referenz hinzufgefügt', 'icon' => 'fa-link text-success');
        } else {
            $_SESSION['PAGE']->message[] = array('message' => 'Referenz konnte nicht hinzugefügt werden', 'icon' => 'fa-link text-warning');
        }
    }
    
    /*switch ($_POST['func']) {
        case 'new':     if ($reference->add()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Referenz hinzufgefügt', 'icon' => 'fa-link text-success');
                        }               
            break;
        case 'edit':    if ($reference->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Referenz erfolgreich aktualisiert', 'icon' => 'fa-link text-success');
                        }
            break;

        default:
            break;
    }*/
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);