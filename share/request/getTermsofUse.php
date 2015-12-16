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
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
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