/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename file.js
* @copyright 2016 Joachim Dieterich
* @author Joachim Dieterich
* @date 2016.01.07 09:54
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
        $(document.getElementById('form_curriculum')).removeClass("hidden");
    }
    $(document.getElementById(form+'_fAbort')).removeClass("hidden");

    var file        = document.getElementById(fSelector).files[0];              //Wieder unser File Objekt
    var formData    = new FormData();                                 //FormData Objekt erzeugen
    
    client = new XMLHttpRequest();                                              //XMLHttpRequest Objekt erzeugen
    var prog = document.getElementById(fProgress);
 
    if(!file)
        return;
 
    prog.value = 0;
    prog.max   = 100;
 
    formData.append("upload",       file);                                            //Fügt dem formData Objekt unser File Objekt hinzu
    if (typeof(document.getElementById('uploadform')) !== 'undefined'){               // append formData in uploadframe
        formData.append("action",        document.getElementById('action').value); 
        formData.append("ref_id",        document.getElementById('ref_id').value); 
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
        $.nmTop().close();          // close dialog
        if (func === 'import'){
            c = new Array();
            c = setFormData(file.name);   
            document.getElementById('importFileName').value = document.getElementById(fName).innerHTML; // allgemeiner lösen
        } else {
            target = document.getElementById('target').value;
            //alert(client.responseText);
            if($("#"+target).get(0)){
                document.getElementById(target).value       = client.responseText;
                $("#"+target).trigger('change');
             }
             if (document.getElementById('context').value === 'terminal_objective' || document.getElementById('context').value === 'enabling_objective'){
                parent.window.location.reload();
             }
        }
    };
	
    client.onabort = function(e) {
            alert("Upload abgebrochen");
    };
 
    client.open("POST", "../share/processors/fp_upload.php");
    client.send(formData);
}

function uploadURL()
{
    var formData = new FormData();                                 //FormData Objekt erzeugen
    client       = new XMLHttpRequest();                                              //XMLHttpRequest Objekt erzeugen
    if (typeof(document.getElementById('uploadform')) !== 'undefined'){               // append formData in uploadframe
        formData.append("action",       document.getElementById('action').value); 
        formData.append("ref_id",       document.getElementById('ref_id').value); 
        formData.append("target",       document.getElementById('target').value); 
        formData.append("format",       document.getElementById('format').value); 
        formData.append("multiple",     document.getElementById('multiple').value); 
        formData.append("context",      document.getElementById('context').value); 
        formData.append("title",        document.getElementById('title').value); 
        formData.append("description",  document.getElementById('description').value);
        formData.append("license",      document.getElementById('license').value);
        formData.append("file_context", document.getElementById('file_context').value);
        formData.append("fileURL",      document.getElementById('fileURL').value);
    }
    client.onerror = function(e) { alert("onError"); };
    
    client.onloadend = function (e){
        $.nmTop().close();          // close dialog
        target = document.getElementById('target').value;
        if($("#"+target).get(0)){
           document.getElementById(target).value       = client.responseText;
           $("#"+target).trigger('change');
        }
        if (document.getElementById('context').value === 'terminal_objective' || document.getElementById('context').value === 'enabling_objective'){
            parent.window.location.reload();
        }
    };
 
    client.open("POST", "../share/processors/fp_upload.php");
    client.send(formData);
}


function uploadAbort() {
	if(client instanceof XMLHttpRequest)
		client.abort();                                                 //Bricht die aktuelle Übertragung ab
}