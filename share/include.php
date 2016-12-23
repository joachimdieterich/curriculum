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

/* Klassen laden */
include('classes/absent.class.php');
include('classes/backup.class.php');
include('classes/block.class.php');
include('classes/capability.class.php');
include('classes/certificate.class.php');
include('classes/config.class.php');
include('classes/content.class.php');
include('classes/context.class.php');
include('classes/country.class.php');
include('classes/course.class.php'); 
include('classes/coursebook.class.php'); 
include('classes/cron.class.php');
include('classes/curriculum.class.php'); 
include('classes/db.class.php'); 
include('classes/enablingobjective.class.php'); 
include('classes/event.class.php'); 
include('classes/exception.class.php'); 
include('classes/file.class.php'); 

include('classes/grade.class.php');     
include('classes/group.class.php');  
include('classes/gump.class.php');                  //validator class
include('classes/help.class.php'); 
include('classes/institution.class.php'); 
include('classes/interval.class.php'); 
include('classes/license.class.php'); 
include('classes/log.class.php'); 
global $LOG;
$LOG = new Log();                                   // Funktion ist so  innerhalb des uploadframes sowie der einzelnen requests nutzbar
include('classes/mail.class.php');
include('classes/mailbox.class.php');               
include('classes/node.class.php');               
include('classes/pdf.class.php'); 
include('classes/plugin.class.php');     
include('classes/portfolio.class.php'); 
include('classes/printer.class.php'); 
include('classes/quiz_question.class.php'); 
include('classes/quiz_answer.class.php');
include('classes/roles.class.php');     
include('classes/schooltype.class.php'); 
include('classes/search.class.php'); 
include('classes/semester.class.php'); 
include('classes/state.class.php'); 
include('classes/statistic.class.php'); 
include('classes/subject.class.php');     
include('classes/task.class.php');     
include('classes/terminalobjective.class.php');
include('classes/upload.class.php');  
include('classes/user.class.php');      

/*Videostream*/
include('classes/video_stream.class.php');     

/*HTML Purifier*/
require 'libs/htmlpurifier-4.7.0-standalone/HTMLPurifier.standalone.php';     


require 'libs/rtf-html-php-master/rtf-html-php.php';