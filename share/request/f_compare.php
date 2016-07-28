<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_compare.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.05.31 19:51
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
    case 'group':   $ena      = new EnablingObjective();
                    $ena->id  = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                    $ena->load();
                    $acc_0    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 0);                 
                    $acc_1    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 1);                 
                    $acc_2    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 2);                 
                    $acc_3    = $ena->getAccomplishedUsers(filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT), 3);                         
        break;
    
    default:
        break;
}

$content    = '';
if (isset($ena->enabling_objective)){  $content .= '<div class="col-md-12 "><p>Übersicht zum Lernziel:'.filter_input(INPUT_GET, 'group_id', FILTER_VALIDATE_INT).'<br><strong>'.$ena->enabling_objective.'</strong></p></div>'; } 

/* Ziel erfolgreich erreicht */
if ($acc_1){
    $content .= '<div class="col-md-6 "><div class="box box-success box-solid">
                    <div class="box-header with-border">
                    <div class="box-title">Ziel erreicht</div>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">';
                        foreach($acc_1 AS $value) {
                            $user     = new User();
                            $content .= '<li><a href="">'.$user->resolveUserId($value).'</a></li>';
                        }   
    $content .= '</ul></div></div></div>';  
} 
if ($acc_2){
    $content .= '<div class="col-md-6 "><div class="box box-warning box-solid">
                    <div class="box-header with-border">
                    <div class="box-title">Ziel mit Hilfe erreicht</div>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">';
                        foreach($acc_2 AS $value) {
                            $user     = new User();
                            $content .= '<li><a href="">'.$user->resolveUserId($value).'</a></li>';
                        }   
    $content .= '</ul></div></div></div>';  
} 
if ($acc_3){
    $content .= '<div class="col-md-6 "><div class="box box-default box-solid">
                    <div class="box-header with-border">
                    <div class="box-title">Ziel noch nicht bearbeitet</div>
                    </div>
                        <ul class="nav nav-stacked">';
                        foreach($acc_3 AS $value) {
                            $user     = new User();
                            $content .= '<li><a href="">'.$user->resolveUserId($value).'</a></li>';
                        }   
    $content .= '</ul></div></div></div>';  
} 
if ($acc_0){
    $content .= '<div class="col-md-6 "><div class="box box-danger box-solid">
                    <div class="box-header with-border">
                    <div class="box-title">Ziel nicht erreicht</div>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">';
                        foreach($acc_0 AS $value) {
                            $user     = new User();
                            $content .= '<li><a href="">'.$user->resolveUserId($value).'</a></li>';
                        }   
    $content .= '</ul></div></div></div>';  
} 

$html = Form::modal(array('title'   => 'Lernstand der Gruppe',
                          'content' => $content));

echo json_encode(array('html'=>$html));