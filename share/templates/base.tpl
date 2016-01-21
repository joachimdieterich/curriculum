<!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="de" class="no-js"> <!--<![endif]-->
{* define global validate function*}
{function validate_msg field=''}
    {if isset($v_error.$field)}
        {foreach key=err item=v_field from=$v_error.$field.message}
                <p><label></label>{$v_field}</p>   
        {/foreach} 
    {/if}
{/function}
    
    <head>
        <title>{block name=title}{/block} | {strip_tags($app_title)}</title>
        <meta charset="utf-8"> 
        <meta name="description" content="{block name="description"}{/block}">
        <meta name="author" content="">
        {*<meta name="viewport" content="width=device-width, initial-scale=1.0" />  *}
        <script src="{$media_url}scripts/modernizr-1.6.min.js"></script>
        <script>!window.jQuery && document.write(unescape('%3Cscript src="{$media_url}scripts/jquery-1.4.4.min.js"%3E%3C/script%3E'));</script>
        <script src="{$media_url}scripts/jquery.tools.min.js"></script>
        
        <link rel="stylesheet" href="{$media_url}stylesheets/all.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/date.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/buttons.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/quickform.css" media="all">
        
        {block name=additional_stylesheets}{/block}
    </head>

    <body> 
        <header id="header">
           <div id="app_title" class="floatleft"><a href="index.php?action=dashboard">{$app_title}</a></div>
           <div id="header_center"></div>
           <div class="logininfo">
            {if isset($my_username)} 
                <li>{if $my_role_id != -1}<div>{$my_firstname} {$my_lastname}<img src="{$access_file}{$my_avatar}"/><br><span>{$my_role_name}</span></div>{/if}
                    </li>
                <ul>{if checkCapabilities('menu:readProfile', $my_role_id, false)}
                        <a href="index.php?action=profile&function=edit">
                            <li><p>Profil bearbeiten</p></li>
                        </a>
                    {/if}
                    {if checkCapabilities('menu:readPassword', $my_role_id, false)}
                        <a href="index.php?action=password">
                            <li><p>Passwort ändern</p></li> 
                        </a>
                    {/if}
                    {if checkCapabilities('menu:readMessages', $my_role_id, false)}
                    <a href="index.php?action=messages&function=showInbox">
                        <li><p>Nachrichten</p></li>
                    </a>
                    {/if}   
                    {if checkCapabilities('file:upload', $my_role_id, false)}
                    <a  href="../share/request/uploadframe.php?userID={$my_id}&context=userFiles&target=NULL{$tb_param}" class="thickbox">
                        <li><p>Meine Dateien</p></li>
                    </a>
                    {/if}  
                    <a href="index.php?action=logout">
                         <li><p>Abmelden</p></li>      
                    </a>
                    {*<li>Letzter Login: {$my_last_login}</li>*}
                </ul>
                {*if $my_role_id != -1}{$stat_users_online} Benutzer online | Sie sind als <strong>{$my_username}</strong> ({$my_role_name}) angemeldet. <a href="index.php?action=logout">Logout</a>{/if*}
            {/if}
            </div>

            <div class="navbar">
                <div class="breadcrumb_left">{*<p>{$page_action}</p>*}</div>{*bisher nicht genutzt*}
                <div class="breadcrumb_right">
                {if isset($page_message)} 
                    <div id="notification_li"><span id="notification_count">{$page_message_count}</span>
                        <a href="#" id="notificationLink">Meldungen</a>
                        <div id="notificationContainer">
                            <div id="notificationTitle">Meldungen</div>
                            <div id="notificationsBody" class="notifications">
                            {section name=mes loop=$page_message}<p class="notificationContent"><img src="{$request_url}assets/images/logo.png"/>{$page_message[mes]}</p>{/section}
                            </div>
                            {*<div id="notificationFooter"><a href="#">Alle Anzeigen</a></div>*}
                        </div>    
                    </div>
                {/if}
                </div>
            </div>
        </header>  
        
                <div id="popup" class="modal" onload="popupFunction();" ><p class="center space-top-bottom"><img src="{$base_url}public/assets/images/loadingAnimation.gif"/></p></div> <!-- Popup -->    

        <div id="wrapper"> <!-- damit content/main block 100% breit ist -->
            {if $page_name eq 'login' OR $page_name eq 'error' OR $page_name eq 'criteria'} <!--Kein Menu -->
                 <div class="floatleft"></div>    
            {else}
                <div id="sidebar2">{block name=sidebar}{/block} <a class="commentbtn cssbtnmargin cssbtntext tooltip_left" data-tooltip="Fehler melden" href="index.php?action=messages&function=shownewMessage&answer=true&receiver_id=102&subject=Probleme melden"></a></div> <!-- sidebar float right -->           
                {block name=nav} <div id="sidebar1" class="floatleft">{include file='menu.tpl'}</div>{/block}
            {/if}

            <div id="main" class="group ">
                <div id="content" class="space-top ">
                    {block name=content} {/block}
                </div> <!-- end #content -->     
            </div> <!-- end #main -->  
        </div>      

        <footer id="page-footer" >
            <div class="copyright">{$app_footer}<span class="floatright space-right">{if isset($stat_users_online) && checkCapabilities('menu:readPassword', $my_role_id, false)}Erfolgreich erreichte Ziele auf curriculumonline.de: {$stat_acc_all} davon heute: {$stat_acc_today} ({$stat_users_online} User online | Heute: {$stat_users_today}) {/if}</span></div>
            {block name=footer} {/block}
        </footer>                          
        
<!-- SCRIPTS-->  
        <script src="{$media_url}scripts/script.js"></script>     
        <script src="{$media_url}scripts/dragndrop.js"></script>     
        {block name=additional_scripts} 
        
        {if $tiny_mce && isset($my_id)}
            <script type="text/javascript" src="{$lib_url}tinymce/tinymce.min.js"></script>
            <script type="text/javascript">
                tinymce.init({  
                    selector:   "textarea",
                    theme:      "modern",
                    height :    300,
                    plugins:    [ "advlist autolink code colorpicker lists link image charmap print preview hr anchor pagebreak",
                                "searchreplace wordcount visualblocks visualchars fullscreen",
                                "insertdatetime media nonbreaking save textcolor table contextmenu directionality",
                                "emoticons paste"],
                    toolbar1:   "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons | fileframe",
                    {if checkCapabilities('file:upload', $my_role_id, false)}
                        setup: function(editor) {
                            editor.addButton('fileframe', {
                                text: 'Datei einfügen',
                                icon: false,
                                onclick: function() {
                                    tb_show('','../share/request/uploadframe.php?userID={$my_id}&last_login={$my_last_login}&context=editor&target=tmce_editor&format=2&multiple=false{$tb_param}');
                                }
                            });
                        }
                    {/if}
                });
            </script>
        {/if}
        
        <script type="text/javascript" > 
            $(document).ready(function(){
                $("#notificationContainer").slideToggle(200) 
                    setTimeout(function() { $("#notificationContainer").slideToggle(200); }, {$message_timeout});           
            }); 
            
            $("#notificationLink").click(function(){
                $("#notificationContainer").slideToggle(200);
                return false;
            });
            
            $(document).click(function(){
                $("#notificationContainer").hide();
            });
            //Popup Click
            $("#notificationContainer").click(function(){
                return false;
            });
        </script>
        
        <script type="text/javascript" src="{$media_url}scripts/thickbox.js"></script>
        
        <!-- jquery tools --><!-- dateinput styling -->
        <script> 
             $(":date").dateinput({
                format: 'yyyy-mm-dd 00:00:00',	// the format displayed for the user
                selectors: true,             	// whether month/year dropdowns are shown          
                offset: [10, 20],            	// tweak the position of the calendar
                speed: 'fast',               	// calendar reveal speed
                firstDay: 1,                  	// which day starts a week. 0 = sunday, 1 = monday etc..
                yearRange: [-20, 20] 
            });
        </script>    
        <!-- end jquery tools -->
        
        <!-- Logout - Timer  -->
        {if isset($institution_timeout)}
        <script type="text/javascript">
            idleMax = {$global_timeout};        // Logout based on global timout value
            idleTime = 0;
            $(document).ready(function () {
                var idleInterval = setInterval("timerIncrement()", 60000); 
            });
            function timerIncrement() {
                idleTime = idleTime + 1;
                if (idleTime === idleMax) { 
                    window.location="index.php?action=logout&timout=true";
                }
            }
        </script>
        {/if}
        <script type="text/javascript">
        function closePopup(){
            $('#popup').hide();  
            document.getElementById('popup').innerHTML = '<p class="center space-top-bottom"><img src="{$base_url}public/assets/images/loadingAnimation.gif"/></p>';  
        }
        </script>
         <!-- end Logout - Timer  -->
        {/block}  
    </body>
</html>