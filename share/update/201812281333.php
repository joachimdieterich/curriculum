<?php
/** Updatefile 24 Adding capability
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201812281333.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.12.28 13:33
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
$UPDATE->info   = "Adding additional Fields to institution table  <br><br> Neue Felder in der Instituionstabelle (Adresse und Kontakt).";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
    $db0= DB::prepare("ALTER TABLE `institution` ADD COLUMN `street` varchar(250) DEFAULT NULL AFTER `description`;");
    $db0->execute(array());
    $db1= DB::prepare("ALTER TABLE `institution` ADD COLUMN `postalcode` varchar(16) DEFAULT NULL AFTER `street`;");
    $db1->execute(array());
    $db2= DB::prepare("ALTER TABLE `institution` ADD COLUMN `city` varchar(250) DEFAULT NULL AFTER `postalcode`;");
    $db2->execute(array());
    $db3= DB::prepare("ALTER TABLE `institution` ADD COLUMN `phone` varchar(25) DEFAULT NULL AFTER `city`;");
    $db3->execute(array());
    $db4= DB::prepare("ALTER TABLE `institution` ADD COLUMN `email` varchar(250) DEFAULT NULL AFTER `phone`;");
    
    if ($db4->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 1 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 1 - failed.</b><br>";
        $UPDATE->installed = false;
    }               
}