<?php
/** Updatefile 13 - Enhancing UI for reference selector
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201808131404.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.08.13 14:05:00
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
$UPDATE->info   = "Enhancing UI for reference selector. <br><br> Optimiert die Benutzeroberfläche.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update... <br>";
    $db= DB::prepare("CREATE TABLE `config_curriculum` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `curriculum_id` int(11) unsigned NOT NULL,
  `context_id` int(11) unsigned DEFAULT NULL,
  `reference_id` int(11) unsigned DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update failed.</b><br>";
        $UPDATE->installed = false;
    }      
}                      