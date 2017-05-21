/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename script.js
* @copyright 2013 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.09.19 13:26
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

var req;

function formatBytes(bytes,decimals) {
   if(bytes === 0) return '0 Byte';
   var k = 1000; // or 1024 for binary
   var dm = decimals + 1 || 3;
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
   var i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function switchValue(ElementID){
    if (document.getElementById(ElementID).value === 'true'){
        document.getElementById(ElementID).value = 'false';
    } else {
        document.getElementById(ElementID).value = 'true';
    }
}

function toggle(){ 
    for(var i = 0, j = arguments[0].length; i < j; ++i) {
        if ($(document.getElementById(arguments[0][i])).hasClass("hidden")){
            $(document.getElementById(arguments[0][i])).removeClass("hidden");
        }
        $(document.getElementById(arguments[0][i])).addClass("visible");
    }
    for(var i = 0, j = arguments[1].length; i < j; ++i) {
        if ($(document.getElementById(arguments[1][i])).hasClass("visible")){
            $(document.getElementById(arguments[1][i])).removeClass("visible");
        }
        $(document.getElementById(arguments[1][i])).addClass("hidden");
    }	
}
function toggle_sidebar(){
    if ($(document.getElementById(arguments[0])).hasClass("sidebar-collapse")){
        $(document.getElementById(arguments[0])).removeClass("sidebar-collapse");
        $(document.getElementById(arguments[0])).removeClass("sidebar-open");
        $(document.getElementById(arguments[0])).removeClass("sidebar-mini");
    } else {
        $(document.getElementById(arguments[0])).addClass("sidebar-mini sidebar-collapse sidebar-open");
    }
}
/**
 * Get element height of element argument[0] 
 * Set element height of elements argument[1] (array)
 * @returns {undefined}
 */
function resizeBlocks(){
    h = $('#'+arguments[0]).height();
    for(var i = 0, j = arguments[1].length; i < j; ++i) {
        $('#'+arguments[1][i]).height(h);
    }
}
/* allgemeine definieren . arg 1 soll angezeigt werden und alle weiteren deaktiviert werden. Anz. der arg. soll variabel sein */
function toggle_input_size(){ 
    if (arguments[1] === false) {
        document.getElementById(arguments[0]).style.width = '25px';
    } else {
        document.getElementById(arguments[0]).style.width = '100px';
    }
}

function toggle_column(){ 
    if ($('td[name='+arguments[0]+']').hasClass("hidden")){
       $('td[name='+arguments[0]+']').removeClass("hidden");
       $("#cb_"+arguments[0]).prop("checked", true);            // Checkbox
    } else {
        $('td[name='+arguments[0]+']').addClass("hidden");
        $("#cb_"+arguments[0]).prop("checked", false);          // uncheck Checkbox
    }	
}

function loadmail(mail_id, mailbox) {
    document.getElementById('mailbox').innerHTML = '<div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div>';    
    var url = "../share/request/getMail.php?mailID="+ mail_id+"&box="+mailbox; 

    req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                mail(mail_id, mailbox);
            };
            req.open("GET", url, true);
            req.send(null);
        }
}

function mail(mail_id, mailbox) {
    if (req.readyState === 4) {  
        if (req.status === 200) {
            if (req.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                document.getElementById('mailbox').innerHTML = req.responseText;
                if ($('li[name*='+mailbox+']').hasClass("active")){ //deactivate all active li tags
                    $('li[name*='+mailbox+']').removeClass("active");
                }
                $('li[name='+mailbox+'_'+mail_id+']').addClass("active");
            } else {
                window.location.reload();
            } 
        }
    }   
}

function update_paginator(paginator){
    if (req.readyState === 4) {  
        if (req.status === 200) {
            if (req.responseText.length !== 0){
                $("[id^=row]").removeClass("bg-aqua");
                $("[id^="+paginator+"_]").prop("checked", false);
                response = JSON.parse(req.responseText);
                if (typeof(response.length) === 'undefined'){
                    document.getElementById('count_selection').innerHTML = 0; 
                    $("[id^="+paginator+"_]").prop("checked", false);
                    $("#p_unselect").prop("checked", false);
                    $(document.getElementById("span_unselect")).removeClass("visible");
                    $(document.getElementById("span_unselect")).addClass("hidden");
                } else {
                    document.getElementById('count_selection').innerHTML = response.length; 
                    for (i = 0; i < response.length; i++) { 
                        $("#"+paginator+"_"+response[i]).prop("checked", true);  
                        $("#row"+response[i]).addClass("bg-aqua");
                    }
                    $(document.getElementById("span_unselect")).removeClass("hidden");
                    $(document.getElementById("span_unselect")).addClass("visible");
                }
            }
        }
    }  
}
/**
 * 
 * @param {int} rowNr
 * @returns {undefined}
 */
function checkrow(/*rowNr,link*/) {
    paginator = arguments[1];
    if (arguments.length >= 2) { 
        var url = "../share/processors/p_config.php?func=paginator_checkrow&val="+ arguments[0] +"&paginator="+paginator+"&reset="+arguments[2];     
    } else {
        var url = "../share/processors/p_config.php?func=paginator_checkrow&val="+ arguments[0] +"&paginator="+paginator;
    }

    req = XMLobject();
    if(req) {  
        req.onreadystatechange = function(){
            update_paginator(paginator);
        };
        req.open("GET", url, false); //false --> important for print function
        req.send(null);
    }
    
    if (arguments.length === 4) { //reload with given url 
        $(document).ajaxStart(function() { Pace.restart(); });
        $("#curriculum_content").parent().load(arguments[3] + "&ajax=true #curriculum_content"); //.parent to replace #curriculum_content
        $(document.getElementById("div_print_certificate")).removeClass("hidden");
        //window.location.assign(arguments[3]);        
    }
}
/*  Function without fixed layout
function floating_table(wrapper, defaultTop, headerHeight, main_sidebar_class, paginator, field_array, target, source, default_position){
    $("#"+wrapper).scroll(function(e) {
            var scrollTop = $(e.target).scrollTop();
            
            if ((scrollTop > defaultTop) && (small === false)){
                for(var i = 0, j = field_array.length; i < j; ++i) {
                    $('td[name='+paginator+'_col_'+field_array[i]+']').addClass("hidden");
                }
                
                $("#"+source).appendTo("#"+target);
                $("#"+target).css({'background-color': '#ecf0f5', 'webkit-transform':'translate3d(0,0,0)'});
                $('.'+main_sidebar_class).css({'top': scrollTop - headerHeight});
                small    = true;
            } 
            if ((scrollTop > defaultTop) && (small === true)){
                $('.'+main_sidebar_class).css({'top': scrollTop - headerHeight});
            } 
            
            if ((scrollTop < defaultTop) && (small === true)){
                small = false;
                $("#"+source).appendTo("#"+default_position);
                for(var i = 0, j = field_array.length; i < j; ++i) {
                    $('td[name='+paginator+'_col_'+field_array[i]+']').removeClass("hidden");
                }
                $('.'+main_sidebar_class).css({'top': 0});
            }
        });
}*/

/* floating_table with fixed header */
function floating_table(wrapper, defaultTop, paginator, field_array, target, source, default_position){
    $("#"+wrapper).scroll(function(e) {
            var scrollTop = $(e.target).scrollTop();
            
            if ((scrollTop > defaultTop-50) && (small === false)){
                for(var i = 0, j = field_array.length; i < j; ++i) {
                    $('td[name='+paginator+'_col_'+field_array[i]+']').addClass("hidden");
                }
                $("#"+source).appendTo("#"+target);
                $("#"+target).css({'background-color': '#ecf0f5', 'webkit-transform':'translate3d(0,0,0)'});
                small    = true;
            } 
             
            if ((scrollTop < defaultTop-50) && (small === true)){
                small = false;
                $("#"+source).appendTo("#"+default_position);
                for(var i = 0, j = field_array.length; i < j; ++i) {
                    $('td[name='+paginator+'_col_'+field_array[i]+']').removeClass("hidden");
                }
            }
        });
}
function checkfile(file){
    var ch = document.getElementById(file);
    if(ch) {
        if (ch.checked === false){
            ch.checked = true;
            document.getElementById('row'+file).className = 'filelist activefilenail';
        } else {
            ch.checked = false;
            document.getElementById('row'+file).className = 'filelist filenail';
        }  
    }
}

function raiseEvent (eventType, elementID){ 
    var o = document.getElementById(elementID); 
    if (document.createEvent) { 
        var evt = document.createEvent("Events"); 
        evt.initEvent(eventType, true, true); 
        o.dispatchEvent(evt); 
    } 
    else if (document.createEventObject) 
    {
        var evt = document.createEventObject(); 
        o.fireEvent('on' + eventType, evt); 
    } 
    o = null;
} 

/* XMLObject fuer SQL Abfragen*/
function XMLobject() {
    var request = false;

    if(window.XMLHttpRequest) {
        try { 
        request = new XMLHttpRequest();
        } catch(e) {
            request = false;
            }
    } else if(window.ActiveXObject) {
        try {
        request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
            request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                request = false;
                }
            }   
        }

    return request;
}

function process(){
    $('#popup').show(); 
    if (req.readyState === 4) {  
        if (req.status === 200) {   
           if (req.responseText.length !== 0){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen --> auf 0 geaendert - testen!
                response = JSON.parse(req.responseText);
                if (typeof(response.target)!=='undefined'){  // if target is defined show response in target div
                    popup = response.target;
                    $('#'+popup).show(); 
                } else {
                    popup = 'popup';
                }
                
                if (document.getElementById(popup)){
                    document.getElementById(popup).innerHTML = response.html;
                    /*if (typeof(response.cssclass)!=="undefined"){
                        $(document.getElementById(popup)).addClass(response.cssclass);
                    }*/
                    if (typeof(response.zindex)!=="undefined"){
                        document.getElementById(popup).style.zIndex = response.zindex;
                    }
                    
                    if (typeof(response.script)!=="undefined"){ /* loads js for popup*/
                        document.getElementById(popup).innerHTML = document.getElementById(popup).innerHTML+response.script;
                    }
                    
                    raiseEvent('load', popup);
                    /* Set focus to first editable input field */
                    $(document).ready(function() {
                        $("form:first *:input,select,textarea").filter(":not([readonly='readonly']):not([disabled='disabled']):not([type='hidden'])").first().focus();
                    });
                } else {
                    alert(req.responseText); //unschoen, aber #popup ist vom modalframe aus nicht verfuegbar
                }    
           } else {
               window.location.reload();
           }
        }
    }  
}

/**
 * 
 * @param {string} URL
 * @param {string} target
 */
function openLink(URL, target) {
    if (URL !== ''){ //Sortiert "leere" Anfragen aus.
        window.open(URL, target);
    } 
}
/**
 * 
 * @param {string} ID
 * @param {boolean} checked
 */
function unmask(ID,checked){
    if (checked){
        document.getElementById(ID).type = 'text';
    } else {
        document.getElementById(ID).type = 'password'; 
    }
}


function hideFile() { //nach dem l\u00f6schen wird das thumbnail ausgeblendet
    if (req.readyState === 4) {  
        if (req.status === 200) {    
           if (req.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                if (document.getElementById('thumb_'+arguments[0])) {
                    document.getElementById('thumb_'+arguments[0]).style.display='none'; 
                }
           } else {
               window.location.reload();
           }
        }
    }   
}

function setStatusColor(ena_id, status){
    red    = 'fa fa-circle-o';
    green  = 'fa fa-circle-o';
    orange = 'fa fa-circle-o';
    white  = 'fa fa-circle-o';
    bg     = 'bg-white';
    
    switch (true) {
        case (status === 'x0'): red    = 'fa fa-check-circle-o';
                                bg     = 'bg-red';
            break;
        case (status === '0x'): red  = 'fa fa-circle';
            break;
        case (status === '00'): red    = 'fa fa-check-circle';
                                bg     = 'bg-red';
            break;
        case (status === '01'): red    = 'fa fa-circle';
                                green  = 'fa fa-check-circle-o';
                                bg     = 'bg-green';
            break;
        case (status === '02'): red    = 'fa fa-circle';
                                orange  = 'fa fa-check-circle-o';
                                bg     = 'bg-orange';
            break;
        case (status === '03'): red    = 'fa fa-circle';
                                white  = 'fa fa-check-circle-o';
                                bg     = 'bg-white';
            break;
        case  (status === 'x1'): green  = 'fa fa-check-circle-o';
                                bg     = 'bg-green';
            break;
        case (status === '1x'): green  = 'fa fa-circle';
            break;
        case (status === '10'): red    = 'fa fa-check-circle-o';
                                green  = 'fa fa-circle';
                                bg     = 'bg-red';
            break;
        case (status === '11'): green  = 'fa fa-check-circle';
                                bg     = 'bg-green';
            break;
        case (status === '12'): green  = 'fa fa-circle';
                                orange = 'fa fa-check-circle-o';
                                bg     = 'bg-orange';
            break;
        case (status === '13'): green  = 'fa fa-circle';
                                white  = 'fa fa-check-circle-o';
                                bg     = 'bg-white';
            break;
        case  (status === 'x2'): orange = 'fa fa-check-circle-o';
                                bg     = 'bg-orange';
            break;
        case  (status === '2x'): orange = 'fa fa-circle';
            break;
        case (status === '20'): orange = 'fa fa-circle';
                                red    = 'fa fa-check-circle-o';
                                bg     = 'bg-red';
            break;
        case (status === '21'): orange = 'fa fa-circle';
                                green  = 'fa fa-check-circle-o';
                                bg     = 'bg-green';
            break;
        case (status === '22'): orange = 'fa fa-check-circle';
                                bg     = 'bg-orange';
            break;
        case (status === '23'): orange = 'fa fa-circle';
                                white  = 'fa fa-check-circle-o';
                                bg     = 'bg-white';
            break;
        case (status ===  '3'): white  = 'fa fa-check-circle-o';
            break;
        case (status === '3x'): white  = 'fa fa-circle';
            break;
        case (status === 'x3'): white  = 'fa fa-check-circle-o';
            break;
        case (status === '30'): white  = 'fa fa-circle';
                                red    = 'fa fa-check-circle-o';
                                bg     = 'bg-red';
            break;
        case (status === '31'): white  = 'fa fa-circle';
                                green  = 'fa fa-check-circle-o';
                                bg     = 'bg-green';
            break;
        case (status === '32'): white  = 'fa fa-circle';
                                orange = 'fa fa-check-circle-o';
                                bg     = 'bg-orange';
            break;
        case (status === '33'): white  = 'fa fa-check-circle';
                                bg     = 'bg-white';
            break;

        default:
            break;
    }

    document.getElementById(ena_id+"_green").className  = 'margin-r-5 '+green+' text-green pointer_hand';
    document.getElementById(ena_id+"_orange").className = 'margin-r-5 '+orange+' text-orange pointer_hand';
    document.getElementById(ena_id+"_white").className  = 'margin-r-5 '+white+' text-gray pointer_hand';
    document.getElementById(ena_id+"_red").className    = 'margin-r-5 '+red+' text-red pointer_hand';
    $(document.getElementById("ena_header_"+ena_id)).alterClass('bg-*', 'bg-'+status);
}


//SetAccomplishedObjectives
function setAccomplishedObjectives(creatorID, userID, enablingObjectiveID, statusID){       
    var url = "../share/processors/p_setAccObjectives.php?userID="+ userID +"&creatorID="+ creatorID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState===4 && req.status===200){
                setStatusColor(enablingObjectiveID, statusID);
            }
        };
        req.open("GET", url, true);
        req.send(null);
    }
}
/**
 * 
 * @param {string} url
 * @returns {html}
 */
function getRequest(url){
    req = XMLobject();
    
    if(req) {        
        req.onreadystatechange = process;
        req.open("GET", url); // ! security ! im Formular muss ueberprueft werden, ob user die Daten (bez. auf id) sehen darf
        req.setRequestHeader('Content-Type', 'application/json; charset=utf-8'); //Kann Meldung "Automatically populating $HTTP_RAW_POST_DATA is deprecated " in der Konsole erzeugen: Loesung: always_populate_raw_post_data = -1 //in der php.ini auf -1 setzen. 
        req.send();
    }   
}

function curriculumdocs(link) {
    window.open(link, '_blank', '')
}

/*
 * Form Loader
 * Opens Modal
 * called by user or $(document).ready in base.tpl (if valitation of form failed)
 * @returns {undefined}
 */
function formloader(/*form, func, id, []*/){
    if (typeof(arguments[3]) !== 'undefined'){
        getRequest("../share/request/f_"+ arguments[0] +".php?func="+ arguments[1] +"&id="+ arguments[2]+"&"+jQuery.param(arguments[3]));        
    } else {
        getRequest("../share/request/f_"+ arguments[0] +".php?func="+ arguments[1] +"&id="+ arguments[2]);
    }
}

function processor(/*proc, func, val, []*/){
    if (typeof(arguments[3]) !== 'undefined'){
        var url = "../share/processors/p_"+ arguments[0] +".php?func="+ arguments[1] +"&val="+ arguments[2]+"&"+jQuery.param(arguments[3]);
    } else {
        var url = "../share/processors/p_"+ arguments[0] +".php?func="+ arguments[1] +"&val="+ arguments[2];
    }
    req = XMLobject();
    if(req) {  
        req.onreadystatechange =  window.location.reload();
        req.open("GET", url, false); //false --> important for print function
        req.send(null);
    }
}

function comment(/*func reference_id, context_id, text, (parent_id)*/){
    if (typeof(arguments[4]) !== 'undefined'){
        var url = "../share/processors/p_comment.php?func="+ arguments[0] +"&ref_id="+ arguments[1] + "&context_id=" + arguments[2] + "&text=" + arguments[3] +  "&parent_id=" + arguments[4];
    } else {
        var url = "../share/processors/p_comment.php?func="+ arguments[0] +"&ref_id="+ arguments[1] + "&context_id=" + arguments[2] + "&text=" + arguments[3];
    }
    req = XMLobject();
    if(req) {  
        req.onreadystatechange =  window.location.reload();
        req.open("GET", url, false); //false --> important for print function
        req.send(null);
    }
}
/**
 * delete a dataset in a db-table
 **/
function del() {
    if (confirm("Datensatz wirklich l\u00f6schen?")) {
        var url = "../share/processors/p_delete.php?db="+arguments[0]+"&id="+ arguments[1]; 
        
        req = XMLobject();
        if(req) {      
            if (arguments[0] === 'message'){                                                    // Mail aus Liste entfernen und geloeschte Mail ausblenden
              document.getElementById(arguments[2]+'_'+arguments[1]).style.display='none';
              document.getElementById('mailbox').style.display='none';
            } else if (arguments[0] === 'file'){
                var id = arguments[1];
                req.onreadystatechange = function(){
                    hideFile(id);
                };
            } else {
               req.onreadystatechange = process; //Dialog mit Meldungen zeigen 
               //Reload erfolgt ueber Submit des Popups req.onreadystatechange = reloadPage; //window.location.reload() wichtig, damit Aenderungen angezeigt werden
            }
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function removeMaterial(){
    if (confirm("Datei wirklich l\u00f6schen?")) {
        var url     = "../share/request/deleteFile.php?fileID="+ arguments[0];         //Link unterscheidet sich von den anderen Funktionen, da diese Funktion ((nur)vom upload_frame aufgerufen wird
        var fileID  = arguments[0];
        req         = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                    hideFile(fileID);
                };
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function deleteFile() {
    if (confirm("Datei wirklich l\u00f6schen?")) {
        var url     = "deleteFile.php?fileID="+ arguments[0];         //Link unterscheidet sich von den anderen Funktionen, da diese Funktion ((nur)vom upload_frame aufgerufen wird
        var fileID  = arguments[0];
        req         = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                    hideFile(fileID);
                };
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function expelUser() {
    if (confirm("Benutzer wirklich aus der Lerngruppe ausschreiben?")) {
        var url     = "../share/request/expelUser.php?groupsID="+ arguments[0] +"&userID="+ arguments[1]; 
        getRequest(url);
    }
}

function expelFromInstituion() {
    if (confirm("Benutzer wirklich aus der Institution ausschreiben?")) { 
        var url     = "../share/request/expelFromInstitution.php?institutionID="+ arguments[0] +"&userID="+ arguments[1]; 
        getRequest(url);
    }
}

function getMultipleValues(){
    for(var i = 0, j = arguments.length; i < j; ++i) {
        getValues(arguments[i][0],arguments[i][1],arguments[i][2],arguments[i][3],arguments[i][4]);    
    }   
}
/**
 * get values
 * argument[0] = get_[filename]
 * argument[1] = id of depenency
 * argument[2] = id of target field
 * argument[3] = format
 * argument[4] = id of selection
 */
function getValues(){      
    if (arguments[3]){
        var url = "../share/request/get_"+ arguments[0] +".php?dependency_id="+ arguments[1] +"&name="+ arguments[2] +"&format="+ arguments[3] +"&select_id="+ arguments[4];
    } else {
        var url = "../share/request/get_"+ arguments[0] +".php?dependency_id="+ arguments[1] +"&name="+ arguments[2] +"&format="+ arguments[3] ;
    }
    target = arguments[2]; // set target --> to be able to pass value to setValues
    
    req    = XMLobject();
    if(req) {        
        req.onreadystatechange = function(){
                    setValues(target);
                };
        req.open("GET", url, false); // use 
        req.send(null);
    }
}
/* called by getValues*/
function setValues() {
    if (req.readyState === 4) {  
        if (req.status === 200) { 
            if (req.responseText.length !== 0){ 
                response = JSON.parse(req.responseText);
                if (document.getElementById(arguments[0])){
                    document.getElementById(arguments[0]).innerHTML = response.html;
                    /* update chosen-select if present */
                    if ($('#'+arguments[0]).hasClass("chosen-select")){
                        $('#'+arguments[0]).trigger('chosen:updated');
                    }
                    /* update select2 if present */
                    if ($('#'+arguments[0]).hasClass("select2")){
                        $('#'+arguments[0]).select2();
                    }
                }  
            }
        }
    }   
}

//wenn checkbox angeklickt, dann wird element mit id angezeigt
function checkbox_addForm(){//arguments checked, style, id, invers_id -> if checked id is 'block' invers_id is 'none''
    if (arguments[0]) {
        document.getElementById(arguments[2]).style.display=arguments[1];
        document.getElementById(arguments[3]).style.display='none';
    } else {
        document.getElementById(arguments[2]).style.display='none';
        document.getElementById(arguments[3]).style.display=arguments[1];
    }
}

function hideMaterial(){
    document.getElementById('popup').style.visibility='hidden';
}

function checkPaginatorRow(paginatorName, rowNr) {
    var ch = document.getElementById(paginatorName+''+rowNr);
    if(ch) {
        if (ch.checked === false){
            ch.checked = true;
            document.getElementById('row'+rowNr).className = 'activecontenttablerow';
        } else {
            ch.checked = false;
            document.getElementById('row'+rowNr).className = 'contenttablerow';
        }  
    }
}

//Fileuploadframe ausblenden
function hideUploadframe(){
       document.getElementById('uploadframe').style.display = 'none';
}

function confirmDialog(text) {
    if (confirm(text)){
        return true; 
    } else {
        return false;
    }
}

//icon function
function showSubjectIcon(path, icon){
    document.getElementById('icon_img').style.background =  "url('"+path+icon+"') center center";
}

function sendForm(form_id, file){
    var form = $('#'+form_id);
    var data = form.serialize();
    $.post('../share/request/'+file, data, function(response) {
        $('#'+form_id+'').html(response)            
    });
    return false;           
    
}

function updateFileHits(){
    var url = "../share/request/updateFileHits.php?fileID="+ arguments[0];

    req = XMLobject();
    if(req) {        
            req.open("GET", url, true);
            req.send(null);
        }  
}

function resizeModal(){
    if ($('.modal-content').height() > window.innerHeight){
        $('.modal-content').height(window.innerHeight - 50);
        $('.modal-body').height(($('.modal-content').height()) - ($('.modal-header').height()) - ($('.modal-footer').height()) - 50 -31); // calc modal-body height for scrolling, -50 for margins - 31 (header padding
    }
    
    if (document.getElementById('modal-preview') !== null){
     document.getElementById('modal-preview').style.width  = "100%";
     document.getElementById('modal-preview').style.height = "100%";
    }
}

/**
 * Activates scripts in modals
 * @returns {undefined}
 */
function popupFunction(e){
    $("body").addClass("modal-open");                                           //prevent scrolling on body tag
    $("#overlay").addClass("overlay");                                           //darken background
    
    var script = $("#"+e).children("script").text();
    eval(script);                                   // aktiviert scripte im Element e
    resizeModal();                                                              // resize modal 
    
    textareas = document.getElementsByTagName("textarea");                      // Replace the <textarea id="editor1"> with a CKEditor instance, using default configuration
    for (var i = 0, len = textareas.length; i < len; i++) {
        CKEDITOR.dtd.$removeEmpty['i'] = false;
        CKEDITOR.replace(textareas[i].id, { toolbarStartupExpanded : false});
        CKEDITOR.on('instanceReady',function(){
            resizeModal();      // if ckeditor is used, then modal has to be resized after ckeditor is ready
        }); 
    }
    $(".select2").select2();
    /*var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Keine Treffer!'},
      '.chosen-select-width'     : {width:"95%"}
    };
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }*/
}

/**
 * Important Hack for webkit browsers to remove audio / video data from cache.
 * If not called after closing Player, next loading of player will be very! slow
 * remove audio + video + stop all the downloading
 * assumes $video and $audio are jQuery selectors for <video> and <audio> tags.
 * @returns {undefined}
 */  
function removeMedia() {
  $.each($('audio,video'), function () {
    this.pause();
    this.src = '';
    $(this).children('source').prop('src', '');
    this.remove();
  });
}

function setFormData() {
    var url     = "../share/processors/p_setFormData.php?file="+ arguments[0];   
    
    req         = XMLobject();
    if(req) {        
        req.onloadend = function(){
            if (req.responseText.length > 1){
                c = JSON.parse(req.responseText);
                
                $('#curriculum', top.document).val(c.curriculum);
                $('#description', top.document).val(c.description);
                set_select('grade_id',       c.grade_id,         'value', 'top');
                set_select('subject_id',     c.subject_id,       'value', 'top');
                set_select('schooltype_id',  c.schooltype_id,    'value', 'top');
                set_select('state_id',       c.state_id,         'value', 'top');
                set_select('country_id',     c.country_id,       'value', 'top');
                set_select('icon_id',        c.icon_id,          'value', 'top');
            }   
        }
    };
    req.open("GET", url, true);
    req.send(null);  
}

function val_exist_in_select(element, val){
    for (i = 0; i < document.getElementById(element).length; ++i){
        if (document.getElementById(element).options[i].value == val){
            return true;
        } else { return false; }
    }
}

function set_select(element, val, field, level) {
    if (typeof(field)==='undefined')            field           = 'innerHTML';
    if (typeof(level)==='undefined')            level           = 'document';
    if (level === 'top'){
        var sel = top.document.getElementById(element);
    } else {
        var sel = document.getElementById(element);
    }
    
    for(var i = 0, j = sel.options.length; i < j; ++i) {
        if (field === 'innerHTML'){
            if(sel.options[i].innerHTML === val) {
               sel.selectedIndex = i;
               break;
            }
        } 
        if (field === 'value'){
            if(sel.options[i].value === val) {
               sel.selectedIndex = i;
               break;
            }
        }
    }
}

function closePopup(id){
    if (arguments[0]){
        popup = arguments[0];
    } else {
        popup = 'popup';
    }
    if (popup != 'null'){       // only reload if target not 'null'
        processor('reset', '', '');
    } else {
        popup = 'popup';        
    }
    removeMedia();  // Important to empty audio element cache in webkit browsers. see description on function
    $('#'+popup).hide();  
    $("body").removeClass("modal-open"); //reactivate scrolling on body
    $("#overlay").removeClass("overlay");                                           //remove darken background
    document.getElementById(popup).style.zIndex = 3000; // reset zIndex;
    document.getElementById(popup).innerHTML    = '<div class="modal-dialog"><div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div>';    
}
/**
 * 
 * @param {string} id
 * @returns {Boolean}
 */
function printById(id) {
    var contents = document.getElementById(id).innerHTML;
    var frame1 = document.createElement('iframe');
    frame1.name = "frame1";
    frame1.style.position = "absolute";
    frame1.style.top = "-1000000px";
    document.body.appendChild(frame1);
    var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
    frameDoc.document.open();
    frameDoc.document.write('<html><head><title>DIV Contents</title>');
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(contents);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        document.body.removeChild(frame1);
    }, 500);
    return false;
}