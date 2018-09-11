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
$UPDATE->info   = "Adding new table context_files so that multiple contexts have access to one file.<br><br> Hinzufügen der Tabelle context_files, sodass mehrere contexte Zugriff auf ein file haben.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
    $db1= DB::prepare("CREATE TABLE `curriculum`.`context_files` ( `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT , `context_id` INT(11) UNSIGNED NOT NULL , `files_id` INT(10) UNSIGNED NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8;");
    if ($db1->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 1 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 1 - failed.</b><br>";
        $UPDATE->installed = false;
    }       
    
    $erfolg = true;
    $db2 = DB::prepare("SELECT c.context_id, f.id FROM context AS c, files AS f WHERE c.context_id = f.context_id;");
    if (!$result = $db2->execute(array())){
        $erfolg = false;
    }else{
        while ($result = $db2->fetchObject()){
            $db3 = DB::prepare("INSERT INTO context_files (context_id, files_id) VALUES (?,?)");
            if (!$db3->execute(array($result->context_id, $result->id))){
                $erfolg = false;
            }
        }
    }
    if ($erfolg){
        $UPDATE->log .= "<b class=\"text-success\">Update finished 2 - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished 2 - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
 
}