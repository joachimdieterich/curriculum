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
$UPDATE->info   = "Adding new tables curriculum_blocks and objectives and the columns context_id and reference_id to table curriculum, so that personal curriculums will be possible.<br><br> Hinzufügen der Tabellen curriculum_blocks und objectives und der Spalten context_id und reference_id in curriculum um individuelle Lehrpläne zu ermöglichen.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
    $db1= DB::prepare("ALTER TABLE `curriculum` ADD `context_id` INT(11) UNSIGNED NOT NULL DEFAULT '2' AFTER `date`, ADD `reference_id` INT(11) UNSIGNED NULL AFTER `context_id`");
    if ($db1->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 1 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 1 - failed.</b><br>";
        $UPDATE->installed = false;
    }        
    
    $db2= DB::prepare("CREATE TABLE `curriculum`.`curriculum_blocks` ( `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(128) NOT NULL , `curriculum_id` INT(11) UNSIGNED NOT NULL , `order_id` INT(5) UNSIGNED NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8;");
    if ($db2->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 2 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 2 - failed.</b><br>";
        $UPDATE->installed = false;
    }     
    
    $db3= DB::prepare("CREATE TABLE `curriculum`.`objectives` ( `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `curriculum_blocks_id` INT(11) UNSIGNED NOT NULL , `x_order_id` INT(2) UNSIGNED NOT NULL COMMENT 'x-position of object in block' , `y_order_id` INT(2) UNSIGNED NOT NULL COMMENT 'y-position of object in block' , `typ` INT(11) UNSIGNED NOT NULL , `ref_id` INT(11) UNSIGNED NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8;");
    if ($db3->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 3 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 3 - failed.</b><br>";
        $UPDATE->installed = false;
    }     
    
}