<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename setFormData.php
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.06.03 10:28
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
include(dirname(__FILE__).'/../setup.php');  // Klassen, DB Zugriff und Funktionen

global $USER;
$USER   = $_SESSION['USER'];
$file     = filter_input(INPUT_GET, 'file',           FILTER_UNSAFE_RAW);

$c = new Curriculum();
$c->loadImportFormData($file);
echo json_encode($c);
/*
<html>
<head>
<title>setFormData</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
    $('#c_curriculum',  top.document).innerHTML = '<?php $c->curriculum; ?>';    
    $('#c_description', top.document).innerHTML = '<?php $c->description; ?>';
    set_select($('#c_grade', top.document), '<?php $c->grade_id; ?>',  'value');
    set_select($('#c_subject', top.document), '<?php $c->subject_id; ?>', 'value');
    set_select($('#c_schooltype', top.document), '<?php $c->schooltype_id; ?>', 'value');
    set_select($('#c_state', top.document), curriculum[i].getAttribute("state_id"), 'value');
    set_select($('#c_country', top.document), curriculum[i].getAttribute("country_id"), 'value');
    set_select($('#c_icon', top.document), '<?php $c->icon_id; ?>',  'value');
    alert('ferddisch');
</script>
</head>
<body></body>
<html>
 * +/
 */