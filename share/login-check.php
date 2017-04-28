<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename login-check.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.08 13:26
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
global $CFG, $USER, $PAGE; 
if(isset($_SESSION['USER']->id)) {                          // Angemeldet?
    $session_life       = time() - $_SESSION['timein'];     // Errechne vergangene Zeit seit letzter Aktion    
    if($session_life > ($CFG->settings->timeout)*60){                 // *60 = Minuten
        logout();                                           // Session abgelaufen
    }
    $_SESSION['timein'] = time();                           // Session nicht abgelaufen -> neue Zeitsignatur setzen
} else {
    $_SESSION['wantsurl'] = $_SERVER['REQUEST_URI'];
    header('Location:index.php?action=login');              // Nicht angemeldet --> login zeigen.
}

function logout(){                                          // Benutzer abmelden
    global $USER;
    $USER->userLogout();                                    // Schreibt Zeitsignatur 
    //session_destroy();                                    // Session löschen
    header('Location:index.php?action=login');              // Nicht angemeldet --> login zeigen.
}