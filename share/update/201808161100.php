<?php
/** Updatefile 11 - add information content
*
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201808161100.php
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
$UPDATE->info   = "Erweiterung Informationen";

$whatisit_id    = 1;
$howtowork_id   = 2;

if (isset($_GET['execute'])){
    $UPDATE->log = "Starte Informations-Update...<br>";
    $db = DB::prepare('SELECT COUNT(id) FROM context WHERE context = ?');
    $db->execute(array('information'));
    if($db->fetchColumn() >= 1) {
        $UPDATE->log .= "<b class=\"text-success\">Context 'information' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
        $db= DB::prepare("INSERT INTO `context` (`context`, `context_id`, `path`) VALUES ('information', 36, NULL);");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding information context to 'context' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding information context to 'context' - failed.</b><br>";
            $UPDATE->installed = false;
        }
    }

    $db = DB::prepare('SELECT COUNT(cts.id) FROM content_subscriptions AS cts, context AS co WHERE co.context = ? AND co.context_id = cts.context_id AND reference_id = ?');
    $db->execute(array('information', $whatisit_id));
    if($db->fetchColumn() >= 1) {
        $UPDATE->log .= "<b class=\"text-success\">Context_subscription 'information'-> 'whatIsIt' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
		    $db= DB::prepare("INSERT INTO `content` (`title`, `content`) VALUES ('whatIsIt', '<p>Was ist das curriculum<p>');");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding 'information'-> 'whatIsIt' content to 'content' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">>Adding 'information'-> 'whatIsIt' content to 'content' - failed.</b><br>";
            $UPDATE->installed = false;
				}
				$db = DB::prepare('SELECT id FROM content WHERE title = ?');
				$db->execute(array('whatIsIt'));
				$content_id = $db->fetchColumn();

		    $db = DB::prepare('SELECT context_id FROM context WHERE context = ? ');
        $db->execute(array('information'));
				$context_id = $db->fetchColumn();

        $db= DB::prepare("INSERT INTO `content_subscriptions` (`content_id`, `context_id`, `file_context`, `reference_id`, `status`) VALUES (?, ?, 1, ?, 1);");
        if ($db->execute(array($content_id, $context_id, $whatisit_id))){
            $UPDATE->log .= "<b class=\"text-success\">Adding 'information'-> 'whatIsIt' subscription 'content_subscriptions' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding 'information'-> 'whatIsIt' subscription 'content_subscriptions' - failed.</b><br>";
            $UPDATE->installed = false;
				}
    }

    $db = DB::prepare('SELECT COUNT(cts.id) FROM content_subscriptions AS cts, context AS co WHERE co.context = ? AND co.context_id = cts.context_id AND reference_id = ?');
    $db->execute(array('information', $howtowork_id));
    if($db->fetchColumn() >= 1) {
        $UPDATE->log .= "<b class=\"text-success\">Context_subscription 'information'-> 'HowToWork' exists already - OK</b><br>";
        $UPDATE->installed = true;
    } else {
		    $db= DB::prepare("INSERT INTO `content` (`title`, `content`) VALUES ('HowToWork', '<p>Wie arbeite ich mit dem curriculum<p>');");
        if ($db->execute(array())){
            $UPDATE->log .= "<b class=\"text-success\">Adding 'information'-> 'HowToWork' content to 'content' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">>Adding 'information'-> 'HowToWork' content to 'content' - failed.</b><br>";
            $UPDATE->installed = false;
				}
				$db = DB::prepare('SELECT id FROM content WHERE title = ?');
				$db->execute(array('HowToWork'));
				$content_id = $db->fetchColumn();

		    $db = DB::prepare('SELECT context_id FROM context WHERE context = ? ');
        $db->execute(array('information'));
				$context_id = $db->fetchColumn();

        $db= DB::prepare("INSERT INTO `content_subscriptions` (`content_id`, `context_id`, `file_context`, `reference_id`, `status`) VALUES (?, ?, 1, ?, 1);");
        if ($db->execute(array($content_id, $context_id, $howtowork_id))){
            $UPDATE->log .= "<b class=\"text-success\">Adding 'information'-> 'HowToWork' subscription 'content_subscriptions' - OK</b><br>";
            $UPDATE->installed = true;
        } else {
            $UPDATE->log .= "<b class=\"text-red\">Adding 'information'-> 'HowToWork' subscription 'content_subscriptions' - failed.</b><br>";
            $UPDATE->installed = false;
				}
    }

}