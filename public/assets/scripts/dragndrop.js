/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename dragndrop.js
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.12.20 13:10
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