<?php
/** Updatefile 12 - Adding new schooltype
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201807151607.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.07.15 16:08:00
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
$UPDATE->info   = "Alter field name in navigator_block. <br><br> Benennt Feld <strong>nb_target</strong> in <strong>nb_target_id</strong> um.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update... <br>";
    $db= DB::prepare("ALTER TABLE `navigator_block` CHANGE `nb_target` `nb_target_id` VARCHAR(512) DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update Step 1 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - Step 1 failed.</b><br>";
        $UPDATE->installed = false;
    }      
    $db= DB::prepare("UPDATE `navigator_block` SET `nb_reference_id` = `nb_target_id` WHERE `nb_context_id` = 2;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update Step 2 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - Step 2 failed.</b><br>";
        $UPDATE->installed = false;
    }  
    $db= DB::prepare("UPDATE `navigator_block` SET `nb_target_context_id` = NULL WHERE `nb_context_id` = 2;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update Step 3 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - Step 3 failed.</b><br>";
        $UPDATE->installed = false;
    }  
    $db= DB::prepare("UPDATE `navigator_block` SET `nb_target_id` = NULL WHERE `nb_context_id` = 2;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update Step 4 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update finished - Step 4 failed.</b><br>";
        $UPDATE->installed = false;
    }   
}                    