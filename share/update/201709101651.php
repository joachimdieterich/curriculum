<?php
/** Updatefile 01 - Test
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename 201709101652.php
* @copyright 2017 Joachim Dieterich
* @author Joachim Dieterich
* @date 2017.09.10 16:025
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
$UPDATE->info   = "Testupdate: Nr1´";
if (isset($_GET['execute'])){
    $UPDATE->log = "Executing Update-Test 01\n";
    //$db= DB::prepare("INSERT INTO `context` (`id`, `context`, `context_id`, `path`) VALUES (25, 'glossar', 25, NULL);");
    if (true){
        $UPDATE->log = "Executing Update-Test 01 successful installed <br><br> Updates die im Ordner /share/update/ als *.php Datei liegen werden automatisch auf dieser Seite erfasst und können von hier installiert werden.";
        $UPDATE->installed = true;
    } else {
        $UPDATE->log = "Executing Update-Test 01 failed. More infos -> error.log ";
        $UPDATE->installed = false;
    }     
            

}

