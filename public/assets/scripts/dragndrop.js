/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename dragndrop.js
 * @copyright 2015 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.12.20 13:10
 * @license 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details:                          
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */


function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev, input_name) {
    ev.dataTransfer.setData("text", ev.target.id);
    
    if ((typeof(document.getElementsByName(input_name)) != 'undefined' && document.getElementsByName(input_name) != null)) { //inputvalue für Antwort löschen, damit eine evtl. vorher eingefügte ID entfernt wird. (kommt vor, wenn eine gesetzte Antwort zurückgenommen wird)
        document.getElementsByName(input_name)[0].value = '';
    }
}

function drop(ev) {
    ev.preventDefault();

    var data        = ev.dataTransfer.getData("text");
    var parentDiv   = document.getElementById(data).parentNode;
    var scopy       = document.getElementById(data);
    var tcopy       = ev.target;
    
    if (ev.target.tagName == 'IMG'){
        ev.target.parentNode.appendChild(scopy);
        ev.target.parentNode.removeChild(ev.target);
        document.getElementById(parentDiv.id).appendChild(tcopy);
    } else {
        ev.target.appendChild(scopy);
    }
    
}

function drop_answer() {
    drop(arguments[0]);
    document.getElementsByName(arguments[1])[0].value = arguments[0].dataTransfer.getData("text");
}