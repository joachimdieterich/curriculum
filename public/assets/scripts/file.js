/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename file.js
 * @copyright 2016 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2016.01.07 09:54
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


function fileChange(f, fName, fSize, fType, fProgress, fPercent)
{   
    if (typeof(f)==='undefined')            f           = 'fileA';
    if (typeof(fName)==='undefined')        fName       = 'fileName';
    if (typeof(fSize)==='undefined')        fSize       = 'fileSize';
    if (typeof(fType)==='undefined')        fType       = 'fileType';
    if (typeof(fProgress)==='undefined')    fProgress   = 'progress';
    if (typeof(fPercent)==='undefined')     fPercent    = 'prozent';
    var fileList = document.getElementById(f).files;                            //FileList Objekt aus dem Input Element mit der ID "fileA"
    var file = fileList[0];                                                     //File Objekt (erstes Element der FileList)    
 
    if(!file)                                                                   //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
        return;
 
    document.getElementById(fName).value = file.name;
    document.getElementById(fSize).innerHTML = 'Dateigröße: ' + file.size + ' B';
    document.getElementById(fType).innerHTML = 'Dateitype: ' + file.type;
    document.getElementById(fProgress).style.visibility = 'visible';
    document.getElementById(fProgress).value = 0;
    document.getElementById(fPercent).innerHTML = "0%";
}

var client = null;

function uploadFile(func, f, fProgress, fPercent)
{
    if (typeof(func)      ==='undefined') func        = 'import';
    if (typeof(f)         ==='undefined') f           = 'fileA';
    if (typeof(fProgress) ==='undefined') fProgress   = 'progress';
    if (typeof(fPercent)  ==='undefined') fPercent    = 'prozent';
    
    var file = document.getElementById(f).files[0];                             //Wieder unser File Objekt
    var formData = new FormData();                                              //FormData Objekt erzeugen
    
    client = new XMLHttpRequest();                                              //XMLHttpRequest Objekt erzeugen
    var prog = document.getElementById(fProgress);
 
    if(!file)
        return;
 
    prog.value = 0;
    prog.max = 100;
 
    formData.append("upload", file);                                            //Fügt dem formData Objekt unser File Objekt hinzu
 
    client.onerror = function(e) {
        alert("onError");
    };
 
    client.onload = function(e) {
        document.getElementById(fPercent).innerHTML = "100%";
        prog.value = prog.max;
    };
 
    client.upload.onprogress = function(e) {
	var p = Math.round(100 / e.total * e.loaded);
        document.getElementById(fProgress).value = p;            
        document.getElementById(fPercent).innerHTML = p + "%";
        if (p === 100 && func === 'import'){
            document.getElementById('curriculum_form').style.display = 'block';
            document.getElementById('upload_form').style.display = 'none';
        }
    };
    client.onloadend = function (e){
        c = new Array();
        c = setFormData(file.name);        
    }
	
    client.onabort = function(e) {
            alert("Upload abgebrochen");
    };
 
    client.open("POST", "../share/request/upload.php");
    client.send(formData);
}


function uploadAbort() {
	if(client instanceof XMLHttpRequest)
		client.abort();                                                 //Bricht die aktuelle Übertragung ab
}