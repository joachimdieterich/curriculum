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
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or     
 * (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful,       
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
 * GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 * 
 */

/*
 *  Load classes 
 */

include_once('classes/authenticate.class.php');
include_once('classes/backup.class.php');
include_once('classes/capability.class.php');
include_once('classes/cron.class.php');
include_once('classes/config.class.php');
include_once('classes/course.class.php'); 
include_once('classes/country.class.php');
include_once('classes/curriculum.class.php'); 
include_once('classes/db.class.php'); 
include_once('classes/enablingobjective.class.php'); 
include_once('classes/exception.class.php'); 
include_once('classes/file.class.php'); 
include_once('classes/grade.class.php');     
include_once('classes/group.class.php');  
include_once('classes/institution.class.php'); 
include_once('classes/log.class.php'); 
include_once('classes/pdf.class.php'); 
include_once('classes/portfolio.class.php'); 
include_once('classes/user.class.php');     
include_once('classes/upload.class.php');     
include_once('classes/mailbox.class.php');     
   
include_once('classes/roles.class.php');     
include_once('classes/schooltype.class.php');     
include_once('classes/subject.class.php');     
include_once('classes/state.class.php');     
include_once('classes/semester.class.php'); 
include_once('classes/terminalobjective.class.php'); 

include_once('classes/gump.class.php');     //validator class
?>
