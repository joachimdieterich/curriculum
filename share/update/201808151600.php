<?php
/** Updatefile 10 - Erweiterung der subjects-Tabelle um schooltype
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201808151600.php
* @copyright 2018 Fabian Werner
* @author Fabian Werner
* @date 2018.02.23 08:49
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
$UPDATE->info   = "Erweiterung der subjects-Tabelle um schooltype";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update der Fächer-Tabelle...<br>";
    $db = DB::prepare('ALTER TABLE subjects ADD schooltype_id int(11) unsigned NULL');
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - failed.</b><br>";
        $UPDATE->installed = false;
    }
}
if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Berechtigungs-Update...<br>";
    $db= DB::prepare("INSERT INTO `capabilities` (
                        `id`, `capability`, `name`, 
                        `description`, `type`, `component`) VALUES (
                        NULL, 'subject:addglobalsubject', 'globales Fach hinzufügen', 
                        'Ability to add global subjects', 'write', 'curriculum');");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Add capability 'subject:addglobalsubject' - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Add capability 'subject:addglobalsubject' - failed.</b><br>";
        $UPDATE->installed = false;
    }
}
