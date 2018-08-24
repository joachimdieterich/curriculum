<?php
/** Updatefile 16 Adding new field type to terminalobjectives, create table objective_type
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201808212238.php
* @copyright 2018 Joachim Dieterich
* @author Daniel Behr, Joachim Dieterich
* @date 2018.08.21 22:38
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
$UPDATE->info   = "Adding new field type to terminalobjectives, create table objective_type  <br><br> Hinzufuegen einer Typ-Spalte bei terminalobjectives, erstelle neue Tabelle obective_type.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
    $db1= DB::prepare("CREATE TABLE `objective_type` ( `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `type` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;");
    if ($db1->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 1 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 1 - failed.</b><br>";
        $UPDATE->installed = false;
    }       
    
    $db2= DB::prepare("ALTER TABLE `terminalobjectives` ADD COLUMN `type_id` INT(11) UNSIGNED NULL AFTER `color`;");
    if ($db2->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 2 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 2 - failed.</b><br>";
        $UPDATE->installed = false;
    }       

    $db3= DB::prepare("ALTER TABLE `terminalobjectives` CHANGE `type_id` `type_id` INT(11) UNSIGNED NULL DEFAULT '1'; ");
    if ($db3->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 3 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 3 - failed.</b><br>";
        $UPDATE->installed = false;
    }         
    $db4= DB::prepare("INSERT INTO `objective_type` (`id`, `type`) VALUES
                                    (1, 'Kompetenz'),
                                    (2, 'Inhalt/Thema');");
    if ($db4->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 4 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 4 - failed.</b><br>";
        $UPDATE->installed = false;
    }         
    $db5= DB::prepare("UPDATE terminalobjectives SET `type_id` = 1");
    if ($db5->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 5 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 5 - failed.</b><br>";
        $UPDATE->installed = false;
    }         
}