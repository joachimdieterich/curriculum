<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename getMailAnswerBtn.php
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

global $USER;
$USER       = $_SESSION['USER'];

$mail       = new Mail();
$mail->id   = filter_input(INPUT_GET, 'mailID', FILTER_VALIDATE_INT);
$mail->loadMail($mail->id);
echo '&receiver_id='.$mail->sender_id.'&subject=RE:'.$mail->subject;