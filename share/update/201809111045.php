<?php
/** Updatefile 16 Adding new field type to terminalobjectives, create table objective_type
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201809041458.php
* @copyright 2018 Joachim Dieterich
* @author Daniel Behr
* @date 2018.09.04 14:58
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
$UPDATE->info   = "Adding new table qualification to have previous experience.<br><br> Hinzufügen der Tabelle qualification um Vorefahrungen abbilden zu können.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
    $db1= DB::prepare("CREATE TABLE `curriculum`.`qualification` ( `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `experience_id` INT(11) UNSIGNED NOT NULL , `experience_context_id` INT(11) UNSIGNED NOT NULL , `qualification_id` INT(11) UNSIGNED NOT NULL , `qualification_context_id` INT(11) UNSIGNED NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8;");
    if ($db1->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 1 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 1 - failed.</b><br>";
        $UPDATE->installed = false;
    }        
}