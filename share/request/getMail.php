<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename getMail.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.06 08:42
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

$base_url = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen 
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER           = $_SESSION['USER'];

$mail           = new Mail();
$mail->id       = filter_input(INPUT_GET, 'mailID', FILTER_VALIDATE_INT);

$mail->loadMail($mail->id, true);                                                               // Mail laden uns status setzen -> gelesen
Render::mail($mail, filter_input(INPUT_GET, 'box', FILTER_SANITIZE_STRING));                    // Render mail

$correspondence = $mail->loadCorrespondence($mail->id, $mail->sender_id, $mail->receiver_id);   // Render correspondence
for($i = 1; $i < count($correspondence); $i++) {
    Render::mail($correspondence[$i]); 
}