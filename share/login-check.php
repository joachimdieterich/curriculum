<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename login-check.php - 
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2013.03.08 13:26
 * @license: 
*
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
*/
global $INSTITUTION, $USER; 

if(isset($_SESSION['timein']) ) {        // check to see if $_SESSION['timein'] is set 
    
    $session_life = time() - $_SESSION['timein'];
    
    if($session_life > ($INSTITUTION->institution_timeout)*60){ //*60 to get minutes
        $USER->userLogout();
        session_destroy(); 
        header('Location:index.php?action=login');   
    }
}
$_SESSION['timein'] = time();

?>