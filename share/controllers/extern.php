<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename extern.php
* @copyright  2017 Joachim Dieterich  {@link http://www.joachimdieterich.de}
* @date 2017.02.05 12:52
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
global $CFG, $TEMPLATE, $PAGE; 


$user       = new User();
$message    = '';
if(isset($_GET['teacher']) AND isset($_GET['student']) AND isset($_GET['ena_id']) AND isset($_GET['status']) AND isset($_GET['token'])) {
    $user->load('id', filter_input(INPUT_GET, 'teacher', FILTER_VALIDATE_INT), false);
    $ena             = new EnablingObjective();
    $ena->id         = filter_input(INPUT_GET, 'ena_id', FILTER_VALIDATE_INT); 
    $ena->load();
    
    if ($ena->setAccomplishedStatus('extern', filter_input(INPUT_GET, 'student', FILTER_VALIDATE_INT), filter_input(INPUT_GET, 'teacher', FILTER_VALIDATE_INT), filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING), filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING))){
        $info = 'Lernstand zum Lernziel / zur Kompetenz <br><br>'
                .$ena->enabling_objective.' <br><br>'
                . 'wurde erfolgreich gesetzt. <br><br>'
                . 'Sie können das Fenster jetzt schließen.<br><br>';
    } else {
        $info = '<strong>Es ist ein Fehler aufgetreten.</strong><br><br>'
                . 'Lernstand zum Lernziel / zur Kompetenz <br><br>'
                . $ena->enabling_objective.' <br><br>konnte nicht gesetzt werden.<br><br> '
                . 'Das kann mehrere Gründe haben: <br>'
                . '- Lernstand wurde bereits gesetzt.<br>'
                . '-Es handelt sich um einen manipulierten Link.<br><br>'
                . 'Sollte das Problem wiederholt auftreten, wenden Sie sich bitte an den Administrator.<br><br>';
    }
    
    $TEMPLATE->assign('acc_info',  $info);
}


$TEMPLATE->assign('page_title',  'Externe Eingabe');
$TEMPLATE->assign('breadcrumb',  array('Externe Eingeabe' => 'index.php?action=login'));
