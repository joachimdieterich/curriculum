<!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="de" class="no-js"> <!--<![endif]-->

{* define global validate function*}
{function validate_msg field=''}
    {if isset($v_error.$field)}
        {foreach key=err item=v_field from=$v_error.$field.message}
                <p><label></label>{$v_field}</p>   
        {/foreach} 
    {/if}
{/function}
    
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{$media_url}stylesheets/all.css?1" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/date.css" media="all">

        {block name=additional_stylesheets}{/block}

        <title>{block name=title}Willkommen!{/block} | {$app_title}</title>
        <meta name="description" content="{block name="description"}Beschreibung{/block}">
        <meta name="author" content="">
        <script src="{$media_url}scripts/libs/modernizr/modernizr-1.6.min.js"></script>
    </head>

    <body>
        <div id="page">
            <div class="{$page_name}">
                <header id="header" class="clearfix">
                   <div id="app_title"><a href="index.php?action=dashboard">{$app_title}</a></div>
                   <div class="logininfo">
                        {if $my_username eq ''} Sie sind nicht angemeldet. 
                    {else} 
                        {if $my_role_id != -1}({$stat_users_Online} User online) | Sie sind als <strong>{$my_username}</strong> ({$my_role_name}) angemeldet. <a href="index.php?action=logout">Logout</a>{/if}
                    {/if}
                    
                    </div>
                    
                    <div class="navbar clearfix">
                        <div class="breadcrumb_left"></div>
                        <div class="breadcrumb_right">
                        {if $page_message AND isset($page_message[0])} 
                            <div id="notification_li">
                                <span id="notification_count">{$page_message_count}</span>
                                <a href="#" id="notificationLink">Meldungen</a>
                                <div id="notificationContainer">
                                <div id="notificationTitle">Meldungen</div>
                                <div id="notificationsBody" class="notifications">
                                {section name=mes loop=$page_message}<p class="notificationContent"><img src="{$request_url}assets/images/basic/logo.png"/>{$page_message[mes]}</p>{/section}
                                </div><div id="notificationFooter">{*<a href="#">Alle Anzeigen</a>*}</div>
                            </div>    
                        {/if}
                        </div>
                        </div>
                    </div>
                </header>    

                    {if $page_name eq 'login' OR $page_name eq 'error'} <!--Kein Menu -->
                         <div class="floatleft"></div>    
                    {else}{block name=nav}
                            <div class="floatleft">{include file='menu.tpl'}</div>    
                          {/block}
                    {/if}

                <div id="main" class="group">
                    <div id="content" class="space-top">
                        {block name=content} {/block}
                    </div> <!-- end #content -->

                    <div id="sidebar">                    
                        {block name=sidebar} {/block}
                        <!-- Popup -->     
                        <div id="popup" class="modal" ></div> 
                        <!-- end Popup --> 
                    </div> <!-- end #sidebar -->
                    
                </div> <!-- end #main -->

                <footer id="page-footer">
                    <div class="copyright">
                            © Copyright 2014 - Joachim Dieterich.<br>
                            Aktuelle Informationen unter <a href="http://www.joachimdieterich.de">http://www.joachimdieterich.de</a>
                    </div>
                    
                    {block name=footer} {/block}
                </footer>
              </div>                          
        </div> <!-- end #page -->
        
        <script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
        <script>!window.jQuery && document.write(unescape('%3Cscript src="{$media_url}scripts/libs/jquery/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>
        <script src="{$media_url}scripts/script.js"></script>
        
        {block name=additional_scripts} 
        
        {if $tiny_mce}
            <script type="text/javascript" src="{$media_url}scripts/libs/tinymce/tinymce.min.js"></script>
            <script type="text/javascript">
            tinymce.init({  
                selector: "textarea",
                theme:     "modern",
                height : 300,
                plugins: [ "advlist autolink code colorpicker lists link image charmap print preview hr anchor pagebreak",
                            "searchreplace wordcount visualblocks visualchars fullscreen",
                            "insertdatetime media nonbreaking save textcolor table contextmenu directionality",
                            "emoticons paste"],
                toolbar1:   "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                toolbar2:   "print preview media | forecolor backcolor emoticons"    
            });
            </script>
        {/if}
        
        <script src="{$media_url}scripts/Chart.js"></script>
        
        <script type="text/javascript" > 
            $(document).ready(function() 
            {
            $("#notificationContainer").slideToggle(200) 
             setTimeout(function() { $("#notificationContainer").slideToggle(200); }, {$message_timeout});
            }); 
            
            $("#notificationLink").click(function() 
            {
            $("#notificationContainer").slideToggle(200);
            //$("#notification_count").fadeOut("slow");
            return false;
            });
            
            //Document Click
            $(document).click(function()
            {
            $("#notificationContainer").hide();
            });
            //Popup Click
            $("#notificationContainer").click(function()
            {
            return false
           });
        </script>
        
        <script type="text/javascript" src="{$media_url}scripts/libs/modal-upload/thickbox.js"></script>  
        <!--[if lt IE 7 ]>
            <script src="{$media_url}scripts/libs/dd_belatedpng/dd_belatedpng.js"></script>
            <script>DD_belatedPNG.fix('img, .trans-bg');</script>
        <![endif]--> 
        
        <!-- jquery tools --><!-- dateinput styling -->
        <script> $(":date").dateinput({
            format: 'yyyy-mm-dd 00:00:00',	// the format displayed for the user
            selectors: true,             	// whether month/year dropdowns are shown          
            offset: [10, 20],            	// tweak the position of the calendar
            speed: 'fast',               	// calendar reveal speed
            firstDay: 1,                  	// which day starts a week. 0 = sunday, 1 = monday etc..
            yearRange: [-20, 20] 
            });
        </script>   
        <!-- end jquery tools -->
        {/block}   
        
    </body>
</html>