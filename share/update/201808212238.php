<?php
/** Updatefile 14 - Adding new Fields to file-table
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201808212238.php
* @copyright 2018 Joachim Dieterich
* @author Daniel Behr
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
$UPDATE->info   = "Add new fields for to curriculum (e.g. publisher, author...)  <br><br> Hinzufuegen neuer Felder um weitere Informationen zum Lehrplan speichern zu können(z.B. Verlag, Autor).";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>";
    $db1= DB::prepare("ALTER TABLE `curriculum` ADD COLUMN `publisher` VARCHAR(255) NULL AFTER `color`;");
    if ($db1->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 1 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 1 - failed.</b><br>";
        $UPDATE->installed = false;
    }       
    
    $db2= DB::prepare("ALTER TABLE `curriculum` ADD COLUMN `publishingCompany` VARCHAR(255) NULL AFTER `publisher`;");
    if ($db2->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 2 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 2 - failed.</b><br>";
        $UPDATE->installed = false;
    }       

    $db3= DB::prepare("ALTER TABLE `curriculum` ADD COLUMN `place` VARCHAR(255) NULL AFTER `publishingCompany`;");
    if ($db3->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 3 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 3 - failed.</b><br>";
        $UPDATE->installed = false;
    }       

    $db4= DB::prepare("ALTER TABLE `curriculum` ADD COLUMN `date` VARCHAR(255) NULL AFTER `place`;");
    if ($db4->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 4 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 4 - failed.</b><br>";
        $UPDATE->installed = false;
    }       
}