<?php
/** Updatefile 02 - Test
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201805071649.php
* @copyright 2018 Fabin Werner
* @author Fabin Werner
* @date 2018.05.07 16:49
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

global $UPDATE;
$UPDATE         = new stdClass();
$UPDATE->info   = "Installiert neue Berechtigung (shortUserList). <br><br>vereinfachte Benutzerliste anzeigen (nur Login und Name)";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Berechtigungs-Update...<br>";
		$db= DB::prepare("INSERT INTO `capabilities` (
												`id`, `capability`, `name`, 
												`description`, `type`, `component`) VALUES (
												NULL, 'user:shortUserList', 'vereinfachte Benutzerliste anzeigen (Login und Name)', 
												'Ability to see short userlist (login and name)', 'read', 'curriculum');");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Add capability 'user:shortUserList' - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Add capability 'user:shortUserList' - failed.</b><br>";
        $UPDATE->installed = false;
    }     
}
