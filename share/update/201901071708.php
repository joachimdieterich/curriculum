<?php
/** Updatefile 24 Adding capability
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201901071708.php
* @copyright 2019 Joachim Dieterich
* @author Joachim Dieterich
* @date 2019.12.07 17:08
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
$UPDATE->info   = "Adding new capability  <br><br> Neue Berechtigung für Schul-/Institutionstypen.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
   
    $db= DB::prepare("INSERT INTO `capabilities` (`capability`, `name`, `description`, `type`, `component`) VALUES
	('menu:readSchooltype', 'Schul-/Institutionstypenmenü anzeigen', 'Ability to see schooltype menu', 'read', 'curriculum');");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - failed.</b><br>";
        $UPDATE->installed = false;
    }                 
}