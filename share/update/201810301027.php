<?php
/** Updatefile 22 - Adding Context
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201810301027.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2018.10.30 10:27
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
$UPDATE->info   = "Update Add Print context";
if (isset($_GET['execute'])){
    $UPDATE->log = "Executing context update\n";
    $db= DB::prepare("INSERT INTO `context` (`id`, `context`, `context_id`, `path`) VALUES (37, 'print', 37, NULL);");
    if ($db->execute(array())){
        $UPDATE->log = "Executing context update successful installed <br><br>";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log = "Executing context update failed. More infos -> error.log ";
        $UPDATE->installed = false;
    }     
}

