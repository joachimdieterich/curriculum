<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package backup
 * @filename cc_constants.php
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

//Header
define("CC_HEADER", '<?xml version="1.0" encoding="UTF-8"?>');                  // Fileheader
define("CC_SIGNATURE", '<!--Curriculum Common Cartridge generator-->');         // Sinature

//Namespaces
define("CC_XMLNS", 'xmlns="http://www.imsglobal.org/xsd/imsccv1p1/imscp_v1p1"');                 //xmlns
define("CC_LOMIMSCC", 'xmlns:lomimscc="http://ltsc.ieee.org/xsd/imsccv1p1/LOM/manifest"');       //xmlns:lomimscc
define("CC_XSI", 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"');                       //xmlns:xsi
define("CC_SCHEMALOCATION", 'xsi:schemaLocation="http://www.imsglobal.org/xsd/imsccv1p1/imscp_v1p1 http://www.imsglobal.org/profile/cc/ccv1p1/ccv1p1_imscp_v1p2_v1p0.xsd http://ltsc.ieee.org/xsd/imsccv1p1/LOM/manifest http://www.imsglobal.org/profile/cc/ccv1p1/LOM/ccv1p1_lommanifest_v1p0.xsd http://ltsc.ieee.org/xsd/imsccv1p1/LOM/resource http://www.imsglobal.org/profile/cc/ccv1p1/LOM/ccv1p1_lomresource_v1p0.xsd"'); //xsi:schemaLocation

//Discussion Namespaces
define("CC_XMLNS_DISCUSSION", 'xmlns="http://www.imsglobal.org/xsd/imsccv1p1/imsdt_v1p1"');                 //xmlns:dt
define("CC_SCHEMALOCATION_DISCUSSION", 'xsi:schemaLocation="http://www.imsglobal.org/xsd/imsccv1p1/imsdt_v1p1 http://www.imsglobal.org/profile/cc/ccv1p1/ccv1p1_imsdt_v1p1.xsd"'); //xsi:schemaLocation

//Weblink Namespaces
define("CC_XMLNS_WEBLINK", 'xmlns="http://www.imsglobal.org/xsd/imsccv1p1/imswl_v1p1"');                 //xmlns:wl
define("CC_SCHEMALOCATION_WEBLINK", 'xsi:schemaLocation="http://www.imsglobal.org/xsd/imsccv1p1/imswl_v1p1 http://www.imsglobal.org/profile/cc/ccv1p1/ccv1p1_imswl_v1p1.xsd"'); //xmlns:xsi:schemaLocation

//Metadata
define("CC_SCHEMA", 'IMS Common Cartridge');                                    //Schema
define("CC_Version", '1.1.0');                                                  //Version

//Discussion
define("CC_DISCUSSION_TITLE", '<title>Nachrichtenforum</title>');                                        //Discussion Title
define("CC_DISCUSSION_DESCRIPTION", '<text texttype="text/plain">Nachrichten und Ankündigungen</text>'); //Discussion Description

?>