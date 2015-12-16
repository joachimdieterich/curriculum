<?php
/** 
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename include.php
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.07.25 22:37
 * @license
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html
 */

/* Klassen laden */
include('classes/backup.class.php');
include('classes/capability.class.php');
include('classes/certificate.class.php');
include('classes/country.class.php');
include('classes/course.class.php'); 
include('classes/cron.class.php');
include('classes/curriculum.class.php'); 
include('classes/db.class.php'); 
include('classes/enablingobjective.class.php'); 
include('classes/exception.class.php'); 
include('classes/file.class.php'); 
include('classes/grade.class.php');     
include('classes/group.class.php');  
include('classes/gump.class.php');                  //validator class
include('classes/institution.class.php'); 
include('classes/jwt.class.php');                   //jwt class
include('classes/log.class.php'); 
global $LOG;
$LOG = new Log();                                   // Funktion ist so  innerhalb des uploadframes sowie der einzelnen requests nutzbar
include('classes/mail.class.php');
include('classes/mailbox.class.php');               
include('classes/pdf.class.php'); 
include('classes/quiz_question.class.php'); 
include('classes/quiz_answer.class.php'); 
include('classes/render.class.php');     
include('classes/roles.class.php');     
include('classes/schooltype.class.php'); 
include('classes/semester.class.php'); 
include('classes/state.class.php'); 
include('classes/statistic.class.php'); 
include('classes/subject.class.php');     
include('classes/terminalobjective.class.php');
include('classes/upload.class.php');  
include('classes/user.class.php');     

/*badgkit-api*/
include('classes/badges/badgekitconnection.class.php');     //BadgekitConnection Class
include('classes/badges/badges.class.php');
include('classes/badges/issuing.class.php');

/*omega plugin*/
include('plugins/omega.class.php');

//include('libs/form-2.8/Form.php');