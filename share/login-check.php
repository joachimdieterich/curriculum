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
* This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
*                                                                       
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
*                                                                       
* http://www.gnu.org/copyleft/gpl.html      
*/
global $CFG, $USER; 
if(isset($_SESSION['USER']->id)) {                          // Angemeldet?
    $session_life       = time() - $_SESSION['timein'];     // Errechne vergangene Zeit seit letzter Aktion
    if($session_life > ($CFG->timeout)*60){                 // *60 = Minuten
        logout();                                           // Session abgelaufen
    }
    $_SESSION['timein'] = time();                           // Session nicht abgelaufen -> neue Zeitsignatur setzen
} else {
    header('Location:index.php?action=login');              // Nicht angemeldet --> login zeigen.
}

function logout(){                                          // Benutzer abmelden
    global $USER;
    $USER->userLogout();                                    // Schreibt Zeitsignatur 
    session_destroy();                                      // Session löschen
    header('Location:index.php?action=login');              // Nicht angemeldet --> login zeigen.
}