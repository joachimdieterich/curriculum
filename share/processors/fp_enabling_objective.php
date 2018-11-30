<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* FormProcessor
* @package core
* @filename fp_enablingObjective.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.04.16 10:28
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
if (!isset($_SESSION['PAGE']->target_url)){     //if target_url is not set -> use last PAGE url
    $_SESSION['PAGE']->target_url       = $_SESSION['PAGE']->url;
}
$enabling_objective                     = new EnablingObjective();

$purify                                 = HTMLPurifier_Config::createDefault();
$purify->set('Core.Encoding', 'UTF-8'); // replace with your encoding
$purify->set('HTML.Doctype', 'HTML 4.01 Transitional'); // replace with your doctype
$purifier                               = new HTMLPurifier($purify);

$enabling_objective->enabling_objective = $purifier->purify(filter_input(INPUT_POST, 'enabling_objective', FILTER_UNSAFE_RAW));
$enabling_objective->description = $purifier->purify(filter_input(INPUT_POST, 'description', FILTER_UNSAFE_RAW));

$gump = new Gump();    /* Validation */
$_POST = $gump->sanitize($_POST);       //sanitize $_POST

$enabling_objective->terminal_objective_id  = $_POST['terminal_objective_id'];
$enabling_objective->curriculum_id          = $_POST['curriculum_id'];
$enabling_objective->repeat_interval        = $_POST['repeat_interval'];

$gump->validation_rules(array(
'curriculum_id'             => 'required',
'enabling_objective'        => 'required'
));
$validated_data = $gump->run($_POST);
if($validated_data === false) {/* validation failed */
    $_SESSION['FORM']            = new stdClass();
    $_SESSION['FORM']->form      = 'enabling_objective';
    foreach($enabling_objective as $key => $value){
        $_SESSION['FORM']->$key  = $value;
    } 
    $_SESSION['FORM']->error     = $gump->get_readable_errors();
    $_SESSION['FORM']->func      = $_POST['func'];
} else {
    if ($_POST['func'] == 'edit'){
        $enabling_objective->id  = $_POST['enabling_objective_id'];
        $enabling_objective->update();
        $ena_id                  = $enabling_objective->id; 
    } else {
        $ena_id                  = $enabling_objective->add(); 
    }
    $_SESSION['anchor']          = 'ena_'.$ena_id;           // set anchor to jump to new enabling objective
    $_SESSION['FORM']            = null;                     // reset Session Form object
    $curriculum                  = $_POST['curriculum_id'];   
}
if (isset($CFG->settings->repository->omega)){ // prüfen, ob Repository Plugin vorhanden ist. //todo global solution use file_subscription
    $repo = $CFG->settings->repository->omega;
    if (filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW)){
        $repo->setReference('enabling_objective', $ena_id, filter_input(INPUT_POST, 'reference', FILTER_UNSAFE_RAW));
    } else {
        $repo->setReference('enabling_objective', $ena_id); // damit update übernommen wird
    }
}


header('Location:'.$_SESSION['PAGE']->target_url);