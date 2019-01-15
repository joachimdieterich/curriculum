<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename f_description.php
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
include_once(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER       = $_SESSION['USER'];
$func       = $_GET['func'];
$content    = '';
$header     = 'Beschreibung';
$ter        = new TerminalObjective();
switch ($func) {
    case 'curriculum':          $cur        = new Curriculum();
                                $cur->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $cur->load(false);
                                $content .= $cur->description;
        break;
    case 'wallet':              $wa        = new Wallet();
                                $wa->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $wa->load(false);
                                $content .= $wa->description;
        break;

    case 'terminal_objective':  
                                $ter->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                //$_SESSION['anchor'] = 'ter_'.$ena->id;
        break;
    case 'enabling_objective':  $ena        = new EnablingObjective();
                                $ena->id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                                $ena->load();  
                                $ter->id    = $ena->terminal_objective_id;  
                                //$_SESSION['anchor'] = 'ena_'.$ena->id;
        break;

    default:
        break;
}


switch ($func) {
    case 'terminal_objective':  
    case 'enabling_objective':  $ter->load();
                                $header = 'Beschreibung<br><small><b>Kompetenz</b><br>'.strip_tags($ter->terminal_objective).'</small><br>';
                                if (isset($ena)){
                                   $header .= '<small>- '.strip_tags($ena->enabling_objective).'</small>';
                                }
                                if ($ter->description AND $ter->description != ''){         $content .=  $ter->description; } 
                                if (isset($ena->description)){  $content .= '<br>'.$ena->description; } 
                                if (!isset($ena->description) && !isset($ter->description)){    
                                  $content .= 'Keine Beschreibung vorhanden';
                                }
        break;
}



$html = Form::modal(array('target' => 'null',
                          'title'   => $header,
                          'content' => $content));

echo json_encode(array('html'=>$html));