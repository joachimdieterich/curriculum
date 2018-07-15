<?php
/** Updatefile 02 - Test
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201802230849.php
* @copyright 2018 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.02.23 08:49
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
$UPDATE->info   = "Installiert Navigator (DB). <br><br>Mit dem Navigator lassen sich beliebige Navigationsstrukturen abbilden.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Navigator-Update...<br>";
    $db= DB::prepare("CREATE TABLE `navigator` (
                    `na_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `na_title` varchar(1024) NOT NULL DEFAULT '',
                    `na_context_id` int(11) unsigned NOT NULL,
                    `na_reference_id` int(11) unsigned NOT NULL,
                    `na_creation_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
                    `na_creator_id` int(11) unsigned NOT NULL,
                    `na_file_id` int(11) unsigned DEFAULT NULL,
                    PRIMARY KEY (`na_id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Create table 'navigator' - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Create table 'navigator' - failed.</b><br>";
        $UPDATE->installed = false;
    }     
    $db= DB::prepare("CREATE TABLE `navigator_view` (
                        `nv_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `nv_title` varchar(200) DEFAULT NULL,
                        `nv_description` varchar(2084) DEFAULT NULL,
                        `nv_navigator_id` int(11) unsigned NOT NULL,
                        `nv_creator_id` int(11) unsigned NOT NULL,
                        `nv_top_width_class` char(255) DEFAULT 'col-xs-12',
                        `nv_content_width_class` char(255) DEFAULT 'col-xs-12',
                        `nv_footer_width_class` char(255) DEFAULT 'col-xs-12',
                        PRIMARY KEY (`nv_id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Create table 'navigator_view' - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Create table 'navigator_view' - failed.</b><br>";
        $UPDATE->installed = false;
    }     
    $db= DB::prepare("CREATE TABLE `navigator_block` (
                        `nb_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `nb_title` varchar(200) DEFAULT NULL,
                        `nb_description` varchar(2048) DEFAULT NULL,
                        `nb_navigator_view_id` int(11) unsigned NOT NULL,
                        `nb_context_id` int(11) unsigned NOT NULL,
                        `nb_reference_id` int(11) unsigned NOT NULL,
                        `nb_position` char(255) NOT NULL DEFAULT '',
                        `nb_width_class` char(255) NOT NULL DEFAULT '',
                        `nb_target_context_id` int(11) unsigned DEFAULT NULL,
                        `nb_target_id` varchar(512) NOT NULL DEFAULT '',
                        `nb_file_id` int(11) unsigned DEFAULT NULL,
                        PRIMARY KEY (`nb_id`)
                      ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;");
    if ($db->execute(array())){
        $UPDATE->log .= "<b class=\"text-success\">Create table 'navigator_block' - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log .= "<b class=\"text-red\">Create table 'navigator_block' - failed.</b><br>";
        $UPDATE->installed = false;
    }     
    
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('navigator'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context 'navigator' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('navigator', 28, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding navigator context to 'context' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding navigator context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('file'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context 'file' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('file', 29, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding file context to 'context' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding file context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('navigator_view'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context 'navigator_view' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('navigator_view', 30, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding navigator_view context to 'context' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding navigator_view context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('navigator_block'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context 'navigator_block' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('navigator_block', 31, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding navigator_block context to 'context' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding navigator_block context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }
           
}



