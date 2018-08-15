<?php
/** Updatefile[PATCH 24/24] split "Impressum / Datenschutz"
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201808011600.php
* @copyright 2018 Fabian Werner
* @author Fabian Werner
* @date 2018.08.01 16:00
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
$UPDATE->info   = "Erweiterung Datenschutz, Impressum, Nutzungsbedingungen.";

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Nutzungsbedingungen-Update...<br>";
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('imprint'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context 'imprint' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('imprint', 34, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding impress context to 'context' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding impress context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('privacy'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context 'privacy' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('privacy', 35, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding privacy context to 'context' - OK</b><br>";
						$UPDATE->installed = true;

        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding privacy context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }

    $db = DB::prepare('SELECT COUNT(cts.id) FROM content_subscriptions AS cts, context AS co WHERE co.context = ? AND co.context_id = cts.context_id');
    $db->execute(array('imprint'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context_subscription 'imprint' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
		    $db= DB::prepare("INSERT INTO `content` (`title`, `content`) VALUES ('Impressum', '<p>Impressum<p>');");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding imprint content to 'content' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">>Adding imprint content to 'content' - failed.</b><br>";
            $UPDATE->installed = false;
				}
				$db = DB::prepare('SELECT id FROM content WHERE title = ?');
				$db->execute(array('Impressum'));
				$content_id = $db->fetchColumn();

		    $db = DB::prepare('SELECT context_id FROM context WHERE context = ? ');
        $db->execute(array('imprint'));
				$context_id = $db->fetchColumn();

        $db= DB::prepare("INSERT INTO `content_subscriptions` (`content_id`, `context_id`, `file_context`, `reference_id`, `status`) VALUES (?, ?, 1, 0, 1);");
        if ($db->execute(array($content_id, $context_id))){
            $UPDATE->log .= "<b class=\"text-success\">Adding imprint subscription 'content_subscriptions' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding imprint subscription 'content_subscriptions' - failed.</b><br>";
            $UPDATE->installed = false;
				}
    }

    $db = DB::prepare('SELECT COUNT(cts.id) FROM content_subscriptions AS cts, context AS co WHERE co.context = ? AND co.context_id = cts.context_id');
    $db->execute(array('privacy'));
    if($db->fetchColumn() >= 1) { 
        $UPDATE->log .= "<b class=\"text-success\">Context_subscription 'privacy' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
		    $db= DB::prepare("INSERT INTO `content` (`title`, `content`) VALUES ('Datenschutz', '<p>Datenschutz<p>');");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding privacy content to 'content' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">>Adding privacy content to 'content' - failed.</b><br>";
            $UPDATE->installed = false;
				}
				$db = DB::prepare('SELECT id FROM content WHERE title = ?');
				$db->execute(array('Datenschutz'));
				$content_id = $db->fetchColumn();

		    $db = DB::prepare('SELECT context_id FROM context WHERE context = ? ');
        $db->execute(array('privacy'));
				$context_id = $db->fetchColumn();

        $db= DB::prepare("INSERT INTO `content_subscriptions` (`content_id`, `context_id`, `file_context`, `reference_id`, `status`) VALUES (?, ?, 1, 0, 1);");
        if ($db->execute(array($content_id, $context_id))){
            $UPDATE->log .= "<b class=\"text-success\">Adding privacy subscription 'content_subscriptions' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding privacy subscription 'content_subscriptions' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }
}
