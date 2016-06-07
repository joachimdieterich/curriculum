/** This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename script.js
 * @copyright 2013 Joachim Dieterich
 * @author Joachim Dieterich
 * @date 2015.09.19 13:26
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

function toggle(){ // 
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

function toggle_input_size(){ // allgemeine definieren . arg 1 soll angezeigt werden und alle weiteren deaktiviert werden. Anz. der arg. soll variabel sein
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
/**
 * 
 * @param {int} rowNr
 * @returns {undefined}
 */
function checkrow(/*rowNr,link*/) {
    if (arguments.length === 1) { // Auswahl eines Benutzers
        var ch = document.getElementById(arguments[0]);
        
        if(ch) {
            if (ch.checked === false){
                ch.checked = true; 
                document.getElementById('row'+arguments[0]).className = 'activecontenttablerow';
            } else {
                ch.checked = false;
                document.getElementById('row'+arguments[0]).className = 'contenttablerow';
            }  
        }
    }
    if (arguments.length === 4) { //multiple Auswahl über die Checkboxen (Lernstand)
        var values = new Array();           //Array of all checked values
        $.each($('[name="'+arguments[1]+'"]:checked'), function() {
            //alert($(this).val());
            if ($(this).val() !== 'none'){
                values.push($(this).val());                             
            }
          });
          window.location.assign(arguments[3]+'&'+arguments[2]+'_sel_id='+values);        
    }
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

/* XMLObject für SQL Abfragen*/
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
           if (req.responseText.length !== 0){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen --> auf 0 geändert - testen!
                response = JSON.parse(req.responseText);
                if (document.getElementById('popup')){
                    document.getElementById('popup').innerHTML = response.html
                    if (typeof(response.class)!=='undefined'){
                        $(document.getElementById('popup')).addClass(response.class);
                    }
                    
                    if (typeof(response.script)!=='undefined'){ // loads js for popup
                        document.getElementById('popup').innerHTML = document.getElementById('popup').innerHTML+response.script;  
                    }
                    raiseEvent('load', 'popup');
                } else {
                    alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                }    
           } else {
               window.location.reload();
           }
        }
    }  
}

function scriptloader(script){
    //alert(script);
    //script;
}

/*function answer() {
    $('#popup').show(); 
    if (req.readyState === 4) {  
        if (req.status === 200) {   
           if (req.responseText.length !== 0){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen --> auf 0 geändert - testen!
                
                if (document.getElementById('popup')){
                     document.getElementById('popup').innerHTML = req.responseText;  
                     $('#popup').show(); 
                     raiseEvent('load', 'popup');
                } else {
                    alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                }    
           } else {
               window.location.reload();
           }
        }
    }   
}*/

function reloadPage() {
    if (req.readyState === 4) { 
        if (req.status === 200) { 
        window.location.reload();
        }
    } 
}    

/**
 * 
 * @param {string} URL
 * @param {string} target
 * @returns {}
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
 * @returns {undefined}
 */
function unmask(ID,checked){
    if (checked){
        document.getElementById(ID).type = 'text';
    } else {
       document.getElementById(ID).type = 'password'; 
    }
}


function hideFile() { //nach dem löschen wird das thumbnail ausgeblendet
    if (req.readyState === 4) {  
        if (req.status === 200) {
            
           if (req.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
               if (req.responseText !== 'OK'){alert(req.responseText);} //unschön, aber #popup ist vom modalframe aus nicht 
               if (document.getElementById('row_filelastuploadbtn'+arguments[0])) {
                   document.getElementById('row_filelastuploadbtn'+arguments[0]).style.visibility='hidden'; 
               }
               if (document.getElementById('row_curriculumfilesbtn'+arguments[0])) {          
                   document.getElementById('row_curriculumfilesbtn'+arguments[0]).style.visibility='hidden';
               }
               if (document.getElementById('row_solutionfilesbtn'+arguments[0])) {          
                   document.getElementById('row_solutionfilesbtn'+arguments[0]).style.visibility='hidden';
               }
               if (document.getElementById('row_avatarfilesbtn'+arguments[0])) {          
                   document.getElementById('row_avatarfilesbtn'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('row_myfilesbtn'+arguments[0])) {          
                   document.getElementById('row_myfilesbtn'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('material_btn'+arguments[0])) {          
                   document.getElementById('material_btn'+arguments[0]).style.visibility='hidden';           
               }
               if (document.getElementById('material_'+arguments[0])) {          
                   document.getElementById('material_'+arguments[0]).style.visibility='hidden';           
               }
           } else {
               window.location.reload();
           }
        }
    }   
}

function setAccomplishedObjectives(creatorID, userID, paginatorfirst, paginatorLimit, terminalObjectiveID, enablingObjectiveID, groupID){        
    var statusID = Number(document.getElementById(terminalObjectiveID+'_'+enablingObjectiveID).innerHTML); //convert html to int
    switch(statusID) {
    case 0:     statusID = 1;
        break;
    case 1:     statusID = 2;
        break;
    case 2:     statusID = 3;
        break;
    case 3:     statusID = 0;
        break;
    }
    
    var url = "../share/request/setAccObjectives.php?userID="+ userID +"&creatorID="+ creatorID +"&paginatorfirst="+ paginatorfirst +"&terminalObjectiveID="+ terminalObjectiveID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;

    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState===4 && req.status===200){
                if (req.responseText.length !== 1){
                }
                switch(statusID) {
                case 0:     $(document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID)).addClass("boxred");
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=0;
                    break;
                case 1:     $(document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID)).removeClass("boxred");
                            $(document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID)).addClass("boxgreen");
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=1;
                    break;
                case 2:     $(document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID)).removeClass("boxgreen");
                            $(document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID)).addClass("boxorange");
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=2;
                    break;
                case 3:     $(document.getElementById(terminalObjectiveID+"style"+enablingObjectiveID)).removeClass("boxorange");
                            document.getElementById(terminalObjectiveID+"_"+enablingObjectiveID).innerHTML=3;
                    break;
                }
                
            }
        };
            req.open("GET", url, true);
            req.send(null);
        }  
}

//SetAccomplishedObjectivesBySolution
function setAccomplishedObjectivesBySolution(creatorID, userID, enablingObjectiveID, statusID){       
   /*var text = '';
   if (statusID === 0){
        text = 'Soll Ziel gesperrt werden?';
   } else { 
        text = 'Soll Ziel freigeschaltet werden?';
    }
   if (confirm(text)){*/
    var url = "../share/request/setAccObjectives.php?userID="+ userID +"&creatorID="+ creatorID +"&enablingObjectiveID="+ enablingObjectiveID+"&statusID="+statusID;
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = function (){
            if (req.readyState===4 && req.status===200){
                switch(statusID) {
                case 0:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkgreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkwhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkactiveredbtn pointer_hand';     
                    break;
                case 1:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkactivegreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkwhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkredbtn pointer_hand';     
                    break;
                case 2:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkgreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkactiveorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkwhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkredbtn pointer_hand';     
                    break;
                case 3:     document.getElementById(enablingObjectiveID+"_green").className     = 'space-left checkgreenbtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_orange").className    = 'space-left checkorangebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_white").className     = 'space-left checkactivewhitebtn pointer_hand';
                            document.getElementById(enablingObjectiveID+"_red").className       = 'space-left checkredbtn pointer_hand';     
                    break;
                }
            }
        };
            req.open("GET", url, true);
            req.send(null);
        }
    //}
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
        req.open("GET", url); // ! security ! im Formular muss überprüft werden, ob user die Daten (bez. auf id) sehen darf
        req.setRequestHeader('Content-Type', 'application/json; charset=utf-8'); //Kann Meldung "Automatically populating $HTTP_RAW_POST_DATA is deprecated " in der Konsole erzeugen: Lösung: always_populate_raw_post_data = -1 //in der php.ini auf -1 setzen. 
        req.send();
    } 
       
}

/**
 * shows description
 * @param string description
 * @returns html
 */
function showDescription(/*terID, enaID*/) {
    if (arguments.length === 1) {
        var url = "../share/request/showDescription.php?terminalObjectiveID="+ arguments[0]; 
    } else if (arguments.length === 2) {
        var url = "../share/request/showDescription.php?terminalObjectiveID="+ arguments[0]+"&enablingObjectiveID="+ arguments[1]; 
    }
    getRequest(url);
}

/**
 * Show material of a objective
 **/
function showMaterial() {
    if (arguments.length === 3) {
       var url = "../share/request/showMaterial.php?edit="+arguments[0]+"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2];
    } else if (arguments.length === 2) {
       var url = "../share/request/showMaterial.php?edit="+arguments[0]+"&terminalObjectiveID="+ arguments[1];
    }
    getRequest(url);
}


/**
 * show Learner who have complete this objective
 * @param {int} group
 * @param {int} enablingObjectiveID
 * @returns {html}
 */
function getHelp(group, enablingObjectiveID) {
    getRequest("../share/request/getHelp.php?group="+ group +"&enablingObjectiveID="+ enablingObjectiveID);
}


function curriculumdocs(link) {
    window.open(link, '_blank', '')
}
/**
 * Add topic
 * @param {int} curriculumID
 * @returns {html}
 */
function addterminalObjective(curriculumID) {  
    getRequest("../share/request/terminalObjective.php?curriculum_id="+ curriculumID);
}
 
/**
 * Add objective
 * @param {int} curriculumID
 * @param {int} terminalObjectiveID
 * @returns {html}
 */
function addenablingObjective(curriculumID, terminalObjectiveID) {
    var url = "../share/request/enablingObjective.php?curriculumID="+ curriculumID+"&terminalObjectiveID="+ terminalObjectiveID;
    getRequest(url);
}



function editObjective() {
    if (arguments.length === 2) {
       var url = "../share/request/terminalObjective.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]+ "&edit=true"; 
    }
    else if (arguments.length === 3) {
       var url = "../share/request/enablingObjective.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2] + "&edit=true"; 
    }
    getRequest(url);
}

/*
 * Form Loader
 * Opens Modal
 * called by user or $(document).ready in base.tpl (if valitation of form failed)
 * @returns {undefined}
 */
function formloader(form, func, id){
    getRequest("../share/request/f_"+ form +".php?func="+ func +"&id="+ id);
}

function order() {
    if (arguments.length === 4) {
        var url = "../share/request/orderObjectives.php?order="+ arguments[0] +"&orderID="+ arguments[1]+"&curriculumID="+ arguments[2] +"&terminalObjectiveID="+ arguments[3];
    } else if (arguments.length === 5) {
        var url = "../share/request/orderObjectives.php?order="+ arguments[0] +"&orderID="+ arguments[1]+"&curriculumID="+ arguments[2] +"&terminalObjectiveID="+ arguments[3]+"&enablingObjectiveID="+ arguments[4]; 
    }
    req = XMLobject();
    if(req) {        
        req.onloadend = window.location.reload();
        req.open("GET", url, true);
        req.send(null);
    }
}
/**
 * delete a dataset in a db-table
 **/
function del() {
    if (confirm("Datensatz wirklich löschen?")) {   //Meldung "Wirklich löschen?"
        var url = "../share/request/delete.php?db="+arguments[0]+"&id="+ arguments[1]; 
        
        req = XMLobject();
        if(req) {      
            if (arguments[0] === 'message'){                                                    // Mail aus Liste entfernen und gelöschte Mail ausblenden
              document.getElementById(arguments[2]+'_'+arguments[1]).style.display='none';
              document.getElementById('mailbox').style.display='none';
            } else {
               req.onreadystatechange = process; //Dialog mit Meldungen zeigen 
               //Reload erfolgt über Submit des Popups req.onreadystatechange = reloadPage; //window.location.reload() wichtig, damit Änderungen angezeigt werden
            }
            req.open("GET", url, true);
            req.send(null);
        }
    }
}

function removeMaterial(){
    if (confirm("Datei wirklich löschen?")) {   //Meldung "Wirklich löschen?"
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
    if (confirm("Datei wirklich löschen?")) {   //Meldung "Wirklich löschen?"
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

function getStates(){  
    if (arguments[1]){
        var url = "../share/request/getStates.php?country_id="+ arguments[0] +"&name="+ arguments[1] +"&state_id="+ arguments[2] ;
    } else {
        var url = "../share/request/getStates.php?country_id="+ arguments[0];
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = setStates;
        req.open("GET", url, true);
        req.send(null);
    }
}

function setStates() {
    if (req.readyState === 4) {  
        if (req.status === 200) {
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                      if (document.getElementById('state_id')){
                           document.getElementById('state_id').innerHTML = req.responseText;
                      } else {
                          alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                      }  
           } else {
               window.location.reload();
           }
        }
    }   
}
/**
 * argument[0] = institution_id
 * argument[0] = name des zu erstellenden select inputs -> wichtig für die weiterverarbeitung!
 */
function getGroups(){  
    
    if (arguments[1]){
        var url = "../share/request/getGroups.php?institution_id="+ arguments[0] +"&name="+ arguments[1] +"&group_id="+ arguments[2] ;
    } else {
        var url = "../share/request/getGroups.php?institution_id="+ arguments[0];
    }
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = setGroups;
        req.open("GET", url, true);
        req.send(null);
    }
}

function setGroups() {
    if (req.readyState === 4) {  
        if (req.status === 200) {
           if (req.responseText.length != 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                      if (document.getElementById('groups')){
                           document.getElementById('groups').innerHTML = req.responseText;
                      } else {
                          alert(req.responseText); //unschön, aber #popup ist vom modalframe aus nicht verfügbar
                      }  
           } else {
               window.location.reload();
           }
        }
    }   
}

function setSemester(){  
    var url = "../share/request/setSemester.php?semester_id="+ arguments[0];
    
    req = XMLobject();
    if(req) {        
        req.onreadystatechange = reloadPage;
        req.open("GET", url, true);
        req.send(null);
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
    //document.getElementById('icon_img').src = path + icon;
    document.getElementById('icon_img').style.background =  "url('"+path+icon+"') center center";
}

/**
 * add a badge
 **/
/*function badge() {
   if (arguments.length === 4) {
        var url = "../share/request/addBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID=notset"+"&userID="+ arguments[2]+"&lastlogin="+ arguments[3]; 
    } else if (arguments.length === 5) { 
        var url = "../share/request/addBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]+"&userID="+ arguments[3]+"&lastlogin="+ arguments[4]; 
    }
    getRequest(url);
}*/

/*function getBadge() {
    if (arguments.length === 2) {
       var url = "../share/request/getBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]; 
    } else if (arguments.length === 3) {
       var url = "../share/request/getBadge.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    getRequest(url);
}*/
function addQuiz() {
    if (arguments.length === 2) {
       var url = "../share/request/addQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]; 
    } else if (arguments.length === 3) {
       var url = "../share/request/addQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    getRequest(url);
}
function showQuiz() {
    if (arguments.length === 2) {
       var url = "../share/request/showQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1]; 
    } else if (arguments.length === 3) {
       var url = "../share/request/showQuiz.php?curriculumID="+ arguments[0] +"&terminalObjectiveID="+ arguments[1] +"&enablingObjectiveID="+ arguments[2]; 
    }
    getRequest(url);
}

function sendForm(form_id, file){
    var form = $('#'+form_id);
    var data = form.serialize();
    //alert('test: ');
    $.post('../share/request/'+file, data, function(response) {
        $('#'+form_id+'_result').html(response)            
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

/*function editBulletinBoard() {
    var url = "../share/request/bulletinBoard.php";
    getRequest(url);
}*/

function resizeModal(){
    if ($('.modal-content').height() > window.innerHeight){
        $('.modal-content').height(window.innerHeight - 50);
        $('.modal-body').height(($('.modal-content').height()) - ($('.modal-header').height()) - ($('.modal-footer').height()) - 50 -31); // calc modal-body height for scrolling, -50 for margins - 31 (header padding
    }
}


/**
 * Activates scripts in modals
 * @returns {undefined}
 */
function popupFunction(e){
    eval($("#"+e).children("script").text()); // aktiviert scripte im Element e
    resizeModal();              // resize modal 
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration
    textareas = document.getElementsByTagName("textarea");
    for (var i = 0, len = textareas.length; i < len; i++) {
        CKEDITOR.replace(textareas[i].id, {toolbarStartupExpanded : false});
        CKEDITOR.on('instanceReady',function(){
            /*resize */
            resizeModal();      // if ckeditor is used, then modal has to be resized after ckeditor is ready
        }); 
    }
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
    var url     = "../share/request/setFormData.php?file="+ arguments[0];   
    
    req         = XMLobject();
    if(req) {        
        req.onloadend = function(){
            if (req.responseText.length > 1){
                //alert(req.responseText);
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
        } else {return false;}
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