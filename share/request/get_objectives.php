<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename get_objectives.php
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.12.28 15:34
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
$base_url  = dirname(__FILE__).'/../';
include($base_url.'setup.php');  //Läd Klassen, DB Zugriff und Funktionen
include(dirname(__FILE__).'/../login-check.php');  //check login status and reset idletimer
global $USER;
$USER      = $_SESSION['USER'];
$ref_id    = filter_input(INPUT_GET, 'dependency_id', FILTER_VALIDATE_INT);

switch (filter_input(INPUT_GET, 'format', FILTER_UNSAFE_RAW)) { //format is used to get case
    case 'terminal_objective':  $ter                = new TerminalObjective();
                                $obj                = $ter->getObjectives('curriculum', $ref_id);
                                $objective          = 'terminal_objective';
        break;  
    case 'enabling_objective_from_curriculum':  //! only enabling_objectives of the first terminal objective are returned --> to fill enabling_obj pulldown if terminal_obj pulldown is loaded over ajax                                
                                $ter                = new TerminalObjective();
                                $ter_obj            = $ter->getObjectives('curriculum', $ref_id);
                                $ena                = new EnablingObjective();
                                $obj                = $ena->getObjectives('terminal_objective', $ter_obj[0]->id);
                                $objective          = 'enabling_objective';
        break;
    case 'enabling_objective_from_terminal_objective':  
                                $ena                = new EnablingObjective();
                                $obj                = $ena->getObjectives('terminal_objective', $ref_id);
                                $objective          = 'enabling_objective';
        break;

    case 'enabling_objective':  $ena                = new EnablingObjective();
                                $ena->curriculum_id = $ref_id;
                                $obj                = $ena->getObjectives('curriculum', $ref_id);
                                
                                if (isset($only_first_terminal_objective)){
                                    $first_terminal_obj = $obj[0]->terminal_objective_id;
                                }
                                $objective          = 'enabling_objective';
    default:
        break;
}



$html      = '';

foreach ($obj as $value) {
    $html  .=  '<option label="'.strip_tags($value->$objective).'" value="'.$value->id.'"'; 
    if (filter_input(INPUT_GET, 'select_id', FILTER_VALIDATE_INT) == $value->id) { 
        $html  .= ' selected="selected"';    
    } 
    $html  .= '><span>'.strip_tags($value->$objective).'<span></option>';
}
echo json_encode(array('html'=>$html));