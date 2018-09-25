<?php
/**
* Grade object can add, update, delete and get data from grade db
* 
* @abstract This file is part of curriculum - http://www.joachimdieterich.de
* @package core
* @filename grade.class.php
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2013.03.10 10:58
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
class Foreknowledge {  
    
    /**
     * add grade
     * @return mixed 
     */
    public static function getForeknowledge($qualification_id, $qualification_context_id){
        $db = DB::prepare('SELECT fk.foreknowledge_id, fk.foreknowledge_context_id FROM foreknowledge AS fk WHERE fk.qualification_context_id = ? AND fk.qualification_id = ?');
        $db->execute(array($qualification_context_id, $qualification_id));
        $erg = array();
        while ($result = $db->fetchObject()){
            $erg[] = $result;
        }
        return $erg;
                    
    }
    
    public static function insertTerminalObjective($terminal_objective_id, $qualification_id){
        $db = DB::prepare('INSERT INTO `foreknowledge` (`foreknowledge_id`, `foreknowledge_context_id`, `qualification_id`, `qualification_context_id`) VALUES (?, ?, ?, ?);');
        $db->execute(array($terminal_objective_id, $_SESSION['CONTEXT']['terminal_objective']->id, $qualification_id, $_SESSION['CONTEXT']['enabling_objective']->id));
        
    }
    
    public static function insertEnablObjective($enabling_objective_id, $qualification_id){
        $db = DB::prepare('INSERT INTO `foreknowledge` (`foreknowledge_id`, `foreknowledge_context_id`, `qualification_id`, `qualification_context_id`) VALUES (?, ?, ?, ?);');
        $db->execute(array($enabling_objective_id, $_SESSION['CONTEXT']['enabling_objective']->id, $qualification_id, $_SESSION['CONTEXT']['enabling_objective']->id));
        
    }
    
    public static function checkForeknowledge($foreknowledge_id, $foreknowledge_context_id, $qualification_id, $qualification_context_id){
        $db = DB::prepare('SELECT * FROM foreknowledge AS fk WHERE fk.foreknowledge_id = ? '
                . 'AND fk.foreknowledge_context_id = ? '
                . 'AND fk.qualification_id = ? '
                . 'AND fk.qualification_context_id = ?;');
        $db->execute(array($foreknowledge_id, $foreknowledge_context_id, $qualification_id, $qualification_context_id));
        if ($db->rowCount()>0){
            return True;
        }else{
            return False;
        }
    }
}