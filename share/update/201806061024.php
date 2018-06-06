<?php
/** Updatefile 10 - Adding quote class - subscription table
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201806061024.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.06.06 10:25:00
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
$UPDATE->info   = "Adding quote class - subscription table. <br><br> Fügt Zitat-Klasse hinzu - subscription Tabelle.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>";
    $db= DB::prepare("CREATE TABLE `quote_subscriptions` (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `quote_id` int(11) NOT NULL,
                        `context_id` int(11) NOT NULL,
                        `file_context` int(11) NOT NULL,
                        `reference_id` int(11) NOT NULL,
                        `timemodified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                        `status` int(11) NOT NULL DEFAULT '1',
                        `creator_id` int(11) DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        KEY `rel_con_content_id` (`content_id`),
                        KEY `rel_content_context_id` (`context_id`),
                        KEY `rel_content_reference_id` (`reference_id`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - failed.</b><br>";
        $UPDATE->installed = false;
    }      
}
