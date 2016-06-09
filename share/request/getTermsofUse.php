<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename getTermsofUse.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.12.16 09:01
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
$base_url               = dirname(__FILE__).'/../';
include($base_url.'config.php');  //Läd Klassen, DB Zugriff und Funktio
//include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen

echo '<head>
        <title>Zustimmungserklärung | '.$CFG->app_title.'</title>
        <meta charset="utf-8"> 
       
    </head>';

echo '<link rel="stylesheet" href="'.$CFG->base_url.'/public/assets/stylesheets/all.css" media="all">
      <div class="contentheader">Zustimmungserklärung</div>
      <div id="popupcontent">';
echo '<iframe src="'.$CFG->base_url.'public/assets/docs/curriculum_Terms_Of_Use_2015.pdf" style="width: 100%; height: 100%;">
      <a href="'.$CFG->base_url.'public/assets/docs/curriculum_Terms_Of_Use_2015.pdf"</a>;
      </iframe>';
echo '<br><br><p class="center">Lesen Sie diese Zustimmungserklärung sorgfältig. Sie müssen erst zustimmen, um diese Webseite zutzen zu können. Stimmen Sie zu?</p><br>';
echo '<form action="../../public/index.php?action=login" method="post"><input name="terms" type="hidden" value="terms" />'
   . '<p class="center"><input type="submit" name="Submit" value="Nein"/> <input type="submit" name="Submit" value="Ja"/></p>'
   . '</form></div></div>';