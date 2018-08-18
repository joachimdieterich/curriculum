<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_navigator_item.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.07.15 18:37
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
$USER            = $_SESSION['USER'];
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}

$purify                                 = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier                               = new HTMLPurifier($purify);
$navigator_item = new Navigator(); 
$navigator_item->nb_target_context_id   = NULL;
$navigator_item->nb_target_id           = NULL;
$navigator_item->nb_description         = strip_tags($purifier->purify(filter_input(INPUT_POST, 'nb_description', FILTER_UNSAFE_RAW)));

$gump            = new Gump();    /* Validation */
$_POST           = $gump->sanitize($_POST);       //sanitize $_POST
// todo alle Regeln definieren
$gump->validation_rules(array(
'nb_context_id'  => 'required',
'nb_position'    => 'required',
'nb_width_class' => 'required',
'nb_visible'     => 'required',
));

$validated_data  = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'navigator_item';
    foreach($_POST as $key => $value){                                         
        $_SESSION['FORM']->$key  = $value;
    }
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func']; 
} else {
    
    if (isset($_POST['nb_id'])){ 
        $navigator_item->nb_id            = filter_input(INPUT_POST, 'nb_id',                FILTER_VALIDATE_INT);
    }
    $navigator_item->nb_context_id        = filter_input(INPUT_POST, 'nb_context_id',        FILTER_VALIDATE_INT);
    $navigator_item->nb_title             = filter_input(INPUT_POST, 'nb_title',             FILTER_SANITIZE_STRING);
    $navigator_item->nb_reference_id      = filter_input(INPUT_POST, 'nb_reference_id',        FILTER_VALIDATE_INT);
    $navigator_item->nb_navigator_view_id = filter_input(INPUT_POST, 'nb_navigator_view_id', FILTER_VALIDATE_INT);
    
    $navigator_item->nb_position          = filter_input(INPUT_POST, 'nb_position',          FILTER_SANITIZE_STRING);
    $navigator_item->nb_width_class       = filter_input(INPUT_POST, 'nb_width_class',       FILTER_SANITIZE_STRING);
    $navigator_item->nb_visible           = filter_input(INPUT_POST, 'nb_visible',           FILTER_VALIDATE_INT);
    
    if (isset($_POST['nb_target_context_id'])){ 
        $navigator_item->nb_target_context_id = filter_input(INPUT_POST, 'nb_target_context_id', FILTER_VALIDATE_INT);
        $navigator_item->nb_target_id         = filter_input(INPUT_POST, 'nb_target_id',         FILTER_VALIDATE_INT);
    }
    if (isset($_POST['nb_file_id'])){
        $navigator_item->file_id              = filter_input(INPUT_POST, 'file_id',              FILTER_VALIDATE_INT);
    }
    
    
    switch ($_POST['func']) {
        case 'new':    
            
            break;
        case 'edit':    if ($navigator_item->update()){
                            $_SESSION['PAGE']->message[] = array('message' => 'Navigator Block erfolgreich aktualisiert', 'icon' => 'fa-th text-success');
                        }
            break;

        default:
            break;
    }
    
    $_SESSION['FORM']            = null;                     // reset Session Form object 
}

header('Location:'.$_SESSION['PAGE']->target_url);