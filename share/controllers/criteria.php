<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package badges
* @filename criteria.php
* @copyright  2015 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2015.04.02 21:40
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
global $TEMPLATE; 
          
$TEMPLATE->assign('page_title', 'Kriterien');
$TEMPLATE->assign('my_username', ''); //Loginname setzen für header setzen --> Leer
$ter_id     = filter_input(INPUT_GET, 't', FILTER_UNSAFE_RAW);
$ena_id     = filter_input(INPUT_GET, 'e', FILTER_UNSAFE_RAW);

$criteria = new Badges();
if ($ter_id AND $ena_id){
    $a = $criteria->getCriteria($ter_id, $ena_id);
} else {
    $a = 'Es sind keine Kriterien vorhanden.';
}
$TEMPLATE->assign('criteria',   $a);
$TEMPLATE->assign('message',    '');    //Achtung, nicht $PAGE-> da Sessionabhängig! die Session wird  nach der Anmeldung erzeugt