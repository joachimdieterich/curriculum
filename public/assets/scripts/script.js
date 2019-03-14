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

//to get php like check function
function isset(v){
     return !!v; // converting to boolean.
}

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
/* toggle visibility of argument[0] and argument[1] (once). If argument[2] (=id of checkbox) is given, the toggle works in both directions */
function toggle(){ //deprecated
    if (typeof(arguments[2]) !== 'undefined'){ 
        if ($('#'+arguments[2]).is(':checked')){
            first_array = "visible";
            second_array = "hidden";
        } else {
            first_array = "hidden";
            second_array = "visible";
        }
    } else {
        first_array = "visible";
        second_array = "hidden";
    }
    
    for(var i = 0, j = arguments[0].length; i < j; ++i) {
        if ($(document.getElementById(arguments[0][i])).hasClass(second_array)){
            $(document.getElementById(arguments[0][i])).removeClass(second_array);
        }
            $(document.getElementById(arguments[0][i])).addClass(first_array);
        }
    for(var i = 0, j = arguments[1].length; i < j; ++i) {
        if ($(document.getElementById(arguments[1][i])).hasClass(first_array)){
            $(document.getElementById(arguments[1][i])).removeClass(first_array);
        }
        $(document.getElementById(arguments[1][i])).addClass(second_array);
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

function loadhtml(dependency, id, source, target, source_width, target_width){
    document.getElementById(target).innerHTML = '<div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div>';    
    var url = "../share/request/get_"+dependency+".php?id="+ id; 

    req = XMLobject();
        if(req) {        
            req.onreadystatechange = function(){
                sethtml(dependency, id, source, target, source_width, target_width);
            };
            req.open("GET", url, true);
            req.send(null);
        }
}
function sethtml(dependency, id, source, target, source_width, target_width) {
    if (req.readyState === 4) {  
        if (req.status === 200) {
            if (req.responseText.length !== 1){ //bei einem leeren responseText =1 ! wird das Fenster neu geladen
                $('#'+source).removeClass('col-*');
                $('#'+source).addClass(source_width);
                $('#'+target).removeClass('hidden');
                $('#'+target).removeClass('col-*');
                $('#'+target).addClass(source_width);
                
                document.getElementById(target).innerHTML = req.responseText;
            } else {
                window.location.reload();
            } 
            InitScripts(); //Load scripts
        }
    }   
}


function updatePaginator(response){
    $("[id^=row]").removeClass("bg-aqua");
    $("[id^="+response.paginator+"_]").prop("checked", false);
    if (typeof(response.selection.length) === 'undefined'){
        document.getElementById('count_selection').innerHTML = 0; 
        $(document.getElementById("span_unselect")).removeClass("visible");
        $(document.getElementById("span_unselect")).addClass("hidden");
    } else {
        document.getElementById('count_selection').innerHTML = response.selection.length; 
        for (i = 0; i < response.selection.length; i++) { 
            $("#"+response.paginator+"_"+response.selection[i]).prop("checked", true);  
            $("#row"+response.selection[i]).addClass("bg-aqua");
        }
        $(document.getElementById("span_unselect")).removeClass("hidden");
        $(document.getElementById("span_unselect")).addClass("visible");
        $("#"+response.paginator+"_"+response.val).prop("checked", true);  
    }
     
    $("#"+response.paginator+"_page").prop("checked", response.select_page);  
    $("#"+response.paginator+"_all").prop("checked", response.select_all);  
    $("#"+response.paginator+"_none").prop("checked", response.select_none);  
    
    $('#'+response.replaceId).replaceWith(response.element); 
}

/* floating_table with fixed header */
function findBottomPos(obj) {
    var curbottom = 0;
    if (obj.offsetParent) {
      var offsetHeight = obj.offsetHeight;
      curbottom = obj.offsetTop + offsetHeight
      while (obj = obj.offsetParent) {
        curbottom += obj.offsetTop
      }
    }
    return curbottom;
}

function findTopPos(obj) {
    var curtop = 0;
    if (obj.offsetParent) {
      curtop = obj.offsetTop
      while (obj = obj.offsetParent) {
        curtop += obj.offsetTop
      }
    }
    return curtop;
}

function floating_table(wrapper, paginator, field_array, target, source, default_position){
    $("#"+wrapper).scroll(function(e) {
            var scrollTop = $(e.target).scrollTop();
            defaultTop    = findTopPos($("#"+default_position)[0]);
            defaultBottom = findBottomPos($("#"+default_position)[0]);
            defaultHeight = defaultBottom-defaultTop;
            if ((scrollTop > defaultBottom-50) && (small === false)){
                for(var i = 0, j = field_array.length; i < j; ++i) {
                    $('td[name='+paginator+'_col_'+field_array[i]+']').addClass("hidden");
                }
                $("#"+source).appendTo("#"+target);
                $('<div id="dummySpacePaginator" style="height: '+defaultHeight+'px;"></div>').appendTo("#"+default_position);
                $("#"+target).css({'background-color': '#ecf0f5', 'webkit-transform':'translate3d(0,0,0)'});
                small    = true;
            } else {
                if ((scrollTop < defaultBottom-50) && (small === true)){
                    small = false;
                    $("#"+source).appendTo("#"+default_position);
                    $("#dummySpacePaginator").remove();
                    for(var i = 0, j = field_array.length; i < j; ++i) {
                        $('td[name='+paginator+'_col_'+field_array[i]+']').removeClass("hidden");
                    }
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

function setStatusColor(ena_id, status){
    bg     = 'bg-white';
    s_bit  = status.charAt(0); //Selbsteinschätzung
    t_bit  = status.charAt(1); //Fremdeinschätzung
    
    switch (true){
        case (s_bit === '0'):   red    = '-circle';
                                green  = orange = white = '-circle-o';
                break;
        case (s_bit === '1'):   green  = '-circle';
                                red    = orange = white = '-circle-o';
            break;
        case (s_bit === '2'):   orange = '-circle';
                                green  = red = white = '-circle-o';
            break;
        case (s_bit === '3'):   white  = '-circle';
                                green  = red = orange = '-circle-o';
            break;

        default:                green = red = orange = white ='-circle-o';
            break;
    }
    
    switch (true) { //Teacher Part of status (second char)
        case (t_bit === '0'):   red      = 'fa fa-check'+red;
                                green    = 'fa fa'+green;
                                orange   = 'fa fa'+orange;
                                white    = 'fa fa'+white;
                                bg     = 'bg-red';
            break;
        case (t_bit === '1'):   green    = 'fa fa-check'+green;
                                red      = 'fa fa'+red;
                                orange   = 'fa fa'+orange;
                                white    = 'fa fa'+white;
                                bg       = 'bg-green';
            break;
        case (t_bit === '2'):   orange   = 'fa fa-check'+orange;
                                red      =  'fa fa'+red;
                                green    =  'fa fa'+green;
                                white    =  'fa fa'+white;
                                bg     = 'bg-orange';
            break;
        case (t_bit === '3'):   white    = 'fa fa-check'+white;
                                red      =  'fa fa'+red;
                                green    =  'fa fa'+green;
                                orange   =  'fa fa'+orange;  
                                bg     = 'bg-white';
            break;

         default:               red      =  'fa fa'+red;
                                green    =  'fa fa'+green;
                                orange   =  'fa fa'+orange;
                                white    =  'fa fa'+white;
            break;
    }
    document.getElementById(ena_id+"_green").className  = 'margin-r-5 '+green+' text-green pointer_hand';
    document.getElementById(ena_id+"_orange").className = 'margin-r-5 '+orange+' text-orange pointer_hand';
    document.getElementById(ena_id+"_white").className  = 'margin-r-5 '+white+' text-gray pointer_hand';
    document.getElementById(ena_id+"_red").className    = 'margin-r-5 '+red+' text-red pointer_hand';
    $(document.getElementById("ena_header_"+ena_id)).alterClass('bg-*', 'bg-'+status);
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

/*
 * Form Loader
 * Opens Modal
 * called by user or $(document).ready in base.tpl (if valitation of form failed)
 * @returns {undefined}
 */
function formloader(/*form, func, id, []*/){
    var url = window.location.href;
    var tab = url.substring(url.indexOf("#") + 1);
    if (typeof(arguments[4]) !== 'undefined'){
        getRequest("../share/plugins/"+ arguments[4] +"/request/f_"+ arguments[0] +".php?func="+ arguments[1] +"&id="+ arguments[2]+"&"+jQuery.param(arguments[3])+"&tab="+tab);
    } else if (typeof(arguments[3]) !== 'undefined'){
        getRequest("../share/request/f_"+ arguments[0] +".php?func="+ arguments[1] +"&id="+ arguments[2]+"&"+jQuery.param(arguments[3])+"&tab="+tab);        
    } else {
        getRequest("../share/request/f_"+ arguments[0] +".php?func="+ arguments[1] +"&id="+ arguments[2]+"&tab="+tab);
    }
}

function processor(/*proc, func, val, [..., reload = false], pluginpath*/){ // if reload = false: prevent reload
    if (arguments[0] === 'delete'){
        if (!confirm("Datensatz wirklich l\u00f6schen?")) {
            return;
        } 
    }
    reload   = true;
    callback = false;
    id       = arguments[2];
    if (typeof(arguments[3]) !== 'undefined'){
        if(typeof(arguments[3].reload) !== 'undefined'){ //don't reload
            var boolValue = "true";                      //hack to get boolean
            reload = (boolValue == arguments[3].reload); //hack to get boolean
        }// else not needed ->reload already set.
        if(typeof(arguments[3].callback) !== 'undefined'){ 
            callback = arguments[3].callback; 
        }
    } 
    
    if (typeof(arguments[4]) !== 'undefined'){
        getRequest("../share/plugins/"+ arguments[4] +"/processors/p_"+ arguments[0] +".php?func="+ arguments[1] +"&val="+ arguments[2]+"&"+jQuery.param(arguments[3]));
    } else if (typeof(arguments[3]) !== 'undefined'){
        var url = "../share/processors/p_"+ arguments[0] +".php?func="+ arguments[1] +"&val="+ arguments[2]+"&"+jQuery.param(arguments[3]);
    } else {
        var url = "../share/processors/p_"+ arguments[0] +".php?func="+ arguments[1] +"&val="+ arguments[2];
    }
    req = XMLobject();
    if(req) {  
        req.onreadystatechange =  function() {
            if(this.readyState == this.DONE) {
                if (reload == true){
                    window.location.reload();
                } else if (isset(callback)){
                    response = JSON.parse(req.responseText);
                    switch (callback){
                        case 'innerHTML': innerHTML(response);
                            break;
                        case 'setElementById':
                                    setStatusColor(id, response.status);
                            break;
                        case 'replaceElementByID':
                                    replaceElementByID(response);
                            break;
                        default: 
                            break;                            
                    }    
                }
            }
        }
    }
    req.open("GET", url, false); //false --> important for print function
    req.send(null);
}

function innerHTML(response){ //if callback == innerHTML is set, innerHTML of response.id is set
    document.getElementById(response.id).innerHTML = response.html;
    if(typeof(response.mailbox) !== 'undefined'){ 
        if ($('li[name*='+response.mailbox+']').hasClass("active")){ //deactivate all active li tags
            $('li[name*='+response.mailbox+']').removeClass("active");
        }
        $('li[name='+response.mail_id+']').addClass("active");
    }
}


function replaceElementByID(response){
    if(typeof(response.func) !== 'undefined'){ 
        switch (response.func){
            case 'fadeOut': $('#'+response.replaceId).fadeOut("fast");
                break;
            case 'fadeIn':  $('#'+response.replaceId).replaceWith(response.element);  
                break;
            case 'updatePaginator': updatePaginator(response);
                break;
            default:        
                break;
        }
    } else{
        $('#'+response.replaceId).replaceWith(response.element);
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
    if (typeof(arguments[5]) !== 'undefined'){
        var url = "../share/plugins/"+ arguments[5] +"/get_"+ arguments[0] +".php?dependency_id="+ arguments[1] +"&name="+ arguments[2] +"&format="+ arguments[3] +"&select_id="+ arguments[4];
    } else if (typeof(arguments[4]) !== 'undefined'){
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
        if ($("#"+textareas[i].id).hasClass("no_editor")){
         //do nothing
        } else {
            CKEDITOR.dtd.$removeEmpty['i'] = false;
            if (i == 0){                                                            // only collapse first editor -> description editors sould show toolbar
                CKEDITOR.replace(textareas[i].id, { toolbarStartupExpanded : false});
            } else {
                CKEDITOR.replace(textareas[i].id, { toolbarStartupExpanded : true});
            }
            CKEDITOR.on('instanceReady',function(){
                resizeModal();      // if ckeditor is used, then modal has to be resized after ckeditor is ready
            }); 
        }
    }
    $(".select2").select2();
    
    $('button[data-toggle="collapse"]').click(function () {
        $(this).find('i.fa').toggleClass('fa-compress fa-expand');
    });

    /*close popup when clicking outside modal*/
    $(function() {
        $("body").click(function(e) {
            if ($("body").hasClass("modal-open")){ /* cont call function if it was called before */
                if (textareas.length > 0){
                   /* do nothing */ 
                } else if (e.target.id == "modal" || $(e.target).parents("#modal").size() ) { 
                    /* do nothing */
                } else if (e.target.id == "daterangepicker" || $(e.target).parents("#daterangepicker").size()) {
                    /* do nothing */
                } else { 
                    if ($("#daterangepicker").is(':visible') || $("#colorpicker").is(':visible') || $(".cke_dialog").is(':visible')) {
                        /* don't close if daterangepicker is visible!*/
                    } else {
                        closePopup('null'); /*only close popup without reloading*/
                    }
                }
            }
        });
    });    
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
    /*document.getElementById(popup).style.zIndex = 3000; // reset zIndex; comment cause select 2 is not working when calling modal two times without reload*/
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

function search(search_string, element_id, highlight){
    $('#'+element_id).mark(search_string, { "acrossElements": true });
}

function InitScripts(){
        $(function() {
            //Nyromodal
            $('.nyroModal').nyroModal({
                callbacks: {
                    beforeShowBg: function(){
                        $('body').css('overflow', 'hidden');  
                    },
                    afterHideBg: function(){
                        $('body').css('overflow', '');
                    },
                    afterShowCont: function(nm) {
                        $('.scroll_list').height($('.modal').height()-150);
                    }   
                }
            });
            $('#popup_generate').nyroModal();
        });
    }

function filterBySubject(selectedSubject){
  $("#subject_ajax>div").each(function(index, node) {
    var material = $(node);
    if ( selectedSubject === "false") {
        material.show();
    } else {
        var subjects = material.find(".subjectItem");
        var showMaterial = false;
        subjects.each(function(indexA, nodeA) {
            var subjectText = nodeA.textContent;
            if (subjectText === selectedSubject) {
                showMaterial = true;
            }
        })
        if (showMaterial) {
            material.show();
        } else {
            material.hide();
        }
    }
  });
}
/**
 * 
 * @param {array} object : this, url
 * @returns {undefined}
 */
function ajaxSubmit(obj, file, table, params){
$.ajax({ 
    url:  "../share/processors/"+file+"?func=ajaxsubmit" ,
    data: { id: $(obj).attr('id'), value: $(obj).val(), table: table, func: 'ajaxsubmit', params: params},
    type: 'post'
}).done(function(responseData) {
    $("#"+jQuerySelectorEscape($(obj).attr('id'))+"_form_group").addClass( "has-success");
    console.log('Done: ', responseData);
}).fail(function() {
    console.log('Failed');
});
}

function jQuerySelectorEscape(expression) {
      return expression.replace(/[!"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, '\\$&');
  }
 
function selectAll(id){
    if($("#"+id+"_checkbox").is(':checked') ){
        $("#"+id+" > option").prop("selected","selected");
        $("#"+id).trigger("change");
    }else{
        $("#"+id+" > option").removeAttr("selected");
         $("#"+id).trigger("change");
     }
}