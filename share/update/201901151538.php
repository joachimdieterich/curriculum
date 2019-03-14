<?php
/** Set all Timestampfields with standard 0000-00-00 00:00:00 to Null an on update CURRENT_TIMESTAMP
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 20190151538.php
* @copyright 2019 Joachim Dieterich
* @author Daniel Behr
* @date 2019.01.15 15:38
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
$UPDATE->info   = "Set all Timestampfields with standard 0000-00-00 00:00:00 to Null an on update CURRENT_TIMESTAMP";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Update...<br>"; 
   
    $db= DB::prepare("ALTER TABLE `content` CHANGE `timemodified` `timemodified` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 1 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 1 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    $db= DB::prepare("UPDATE content SET timemodified = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 1a finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 1a finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
    $db= DB::prepare("ALTER TABLE `content_subscriptions` CHANGE `timemodified` `timemodified` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 2 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 2 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    $db= DB::prepare("UPDATE content_subscriptions SET timemodified = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 2a finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 2a finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
    
    $db= DB::prepare("ALTER TABLE curriculum_enrolments CHANGE expel_time expel_time TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 3 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 3 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    $db= DB::prepare("UPDATE curriculum_enrolments SET expel_time = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 3a finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 3a finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
    
    $db= DB::prepare("ALTER TABLE groups_enrolments CHANGE expel_time expel_time TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 4 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 4 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    $db= DB::prepare("UPDATE groups_enrolments SET expel_time = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 4a finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 4a finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
    
    $db= DB::prepare("ALTER TABLE institution_enrolments CHANGE expeled_time expeled_time TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL; ");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 5 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 5 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    $db= DB::prepare("UPDATE institution_enrolments SET expeled_time = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 5a finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 5a finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
    
    
    $db= DB::prepare("ALTER TABLE navigator CHANGE na_creation_time na_creation_time TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 6 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 6 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    $db= DB::prepare("UPDATE navigator SET na_creation_time = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 6a finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 6a finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
        
    $db= DB::prepare("ALTER TABLE quote_subscriptions CHANGE timemodified timemodified TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 7 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 7 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    $db= DB::prepare("UPDATE quote_subscriptions SET timemodified = NOW();");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 7 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 7 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
    
    
    
    
    
    $db= DB::prepare("ALTER TABLE `semester` CHANGE `begin` `begin` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL, CHANGE `end` `end` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT NULL;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Update 8 finished - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Update 8 finished - failed.</b><br>";
        $UPDATE->installed = false;
    } 
}