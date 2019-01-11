<?php
/** Updatefile 28 Adding enrolment_keys table
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201812281827.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.12.28 18:28
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
$UPDATE->info   = "Adding new table 'enrolment_keys'. <br><br> Neue Tabelle 'enrolment_keys'.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
    $db1 = DB::prepare("CREATE TABLE `enrolment_keys` (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `key` varchar(250) NOT NULL,
                        `context_id` int(11) unsigned NOT NULL,
                        `reference_id` int(11) unsigned NOT NULL,
                        `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `expire_time` timestamp NULL DEFAULT NULL,
                        `creator_id` int(11) unsigned NOT NULL,
                        PRIMARY KEY (`id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                      ");
    if ($db1->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update Step 1 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - failed.</b><br>";
        $UPDATE->installed = false;
    }       
}