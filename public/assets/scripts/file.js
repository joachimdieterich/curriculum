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


function fileChange(form, fSelector, fName, fSize, fType, fUpload, fProgress, fPercent)
{   
    if (typeof(form)==='undefined')         form        = '';
    if (typeof(fSelector)==='undefined')    fSelector   = form+'_fSelector';
    if (typeof(fName)==='undefined')        fName       = form+'_fName';
    if (typeof(fSize)==='undefined')        fSize       = form+'_fSize';
    if (typeof(fType)==='undefined')        fType       = form+'_fType';
    if (typeof(fUpload)==='undefined')      fUpload     = form+'_fUpload';
    if (typeof(fProgress)==='undefined')    fProgress   = form+'_fProgress';
    if (typeof(fPercent)==='undefined')     fPercent    = form+'_fPercent';
    var fileList = document.getElementById(fSelector).files;                            //FileList Objekt aus dem Input Element mit der ID "fileSelector"
    var file = fileList[0];                                                     //File Objekt (erstes Element der FileList)    
 
    if(!file)                                                                   //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
        return;
    
    document.getElementById(fSelector).style.visibility = 'hidden'; 
    
    document.getElementById(fName).innerHTML            = file.name;
    document.getElementById(fSize).innerHTML            = 'Dateigröße: ' + formatBytes(file.size, 1);
    document.getElementById(fType).innerHTML            = 'Dateityp: ' + file.type;
    
    $(document.getElementById(fName)).removeClass("hidden");
    $(document.getElementById(fSize)).removeClass("hidden");
    $(document.getElementById(fType)).removeClass("hidden");
    $(document.getElementById(fUpload)).removeClass("hidden");
    $(document.getElementById(fProgress)).removeClass("hidden");
    document.getElementById(fProgress+'_bar').style.width      = "0%";
    document.getElementById(fPercent).innerHTML         = "0%";
}

var client = null;

function uploadFile(form, func, fSelector, fName, fProgress, fPercent)
{
    if (typeof(form)      ==='undefined') form              = '';
    if (typeof(func)      ==='undefined') func              = form+'_import';
    if (typeof(fSelector) ==='undefined') fSelector         = form+'_fSelector';
    if (typeof(fName)     ==='undefined') fName             = form+'_fName';
    if (typeof(fProgress) ==='undefined') fProgress         = form+'_fProgress';
    if (typeof(fPercent)  ==='undefined') fPercent          = form+'_fPercent';
    if (typeof(fAbort)    ==='undefined') fAbort            = form+'_fAbort';
    if (func === 'import'){
        $(document.getElementById('curriculumForm')).removeClass("hidden");
    }
    $(document.getElementById(form+'_fAbort')).removeClass("hidden");

    
    var file        = document.getElementById(fSelector).files[0];              //Wieder unser File Objekt
    var formData    = new FormData();                                 //FormData Objekt erzeugen
    
    client = new XMLHttpRequest();                                              //XMLHttpRequest Objekt erzeugen
    var prog = document.getElementById(fProgress);
 
    if(!file)
        return;
 
    prog.value = 0;
    prog.max = 100;
 
    formData.append("upload",       file);                                            //Fügt dem formData Objekt unser File Objekt hinzu
    if (typeof(document.getElementById('uploadform')) !== 'undefined'){               // append formData in uploadframe
        formData.append("curID",        document.getElementById('curID').value); 
        formData.append("terID",        document.getElementById('terID').value); 
        formData.append("enaID",        document.getElementById('enaID').value); 
        formData.append("target",       document.getElementById('target').value); 
        formData.append("format",       document.getElementById('format').value); 
        formData.append("multiple",     document.getElementById('multiple').value); 
        formData.append("context",      document.getElementById('context').value); 
        formData.append("title",        document.getElementById('title').value); 
        formData.append("description",  document.getElementById('description').value);
        formData.append("license",      document.getElementById('license').value);
        formData.append("file_context", document.getElementById('file_context').value);
    }
    client.onerror = function(e) {
        alert("onError");
    };
 
    client.onload = function(e) {
        document.getElementById(fPercent).innerHTML = "100%";
        prog.value = prog.max;
    };
 
    client.upload.onprogress = function(e) {
	var p = Math.round(100 / e.total * e.loaded);
        
        document.getElementById(fProgress+'_bar').style.width = p+'%';
        document.getElementById(fPercent).innerHTML = p + "%";
        
        if (p === 100){
            
            if (func === 'import'){
                document.getElementById(form).style.display = 'none';
                $(document.getElementById('bImport')).removeClass("hidden");
            }
        }
    };
    client.onloadend = function (e){
        alert(document.getElementById('target').value);
        $.nmTop().close();          // close dialog
        if (func === 'import'){
            c = new Array();
            c = setFormData(file.name);   
            document.getElementById('importFileName').value = document.getElementById(fName).innerHTML; // allgemeiner lösen
        }
    };
	
    client.onabort = function(e) {
            alert("Upload abgebrochen");
    };
 
    client.open("POST", "../share/processors/fp_upload.php");
    client.send(formData);
}


function uploadAbort() {
	if(client instanceof XMLHttpRequest)
		client.abort();                                                 //Bricht die aktuelle Übertragung ab
}