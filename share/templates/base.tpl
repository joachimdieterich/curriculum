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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{block name=title}{/block} | {strip_tags($app_title)}</title>
        <meta name="description" content="{block name="description"}{/block}">
        <meta name="author" content="Joachim Dieterich (www.curriculumonline.de)">
        
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{$media_url}images/favicon/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$media_url}images/favicon/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$media_url}images/favicon/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$media_url}images/favicon/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{$media_url}images/favicon/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{$media_url}images/favicon/apple-touch-icon-152x152.png" />
        <link rel="icon" type="image/png" href="{$media_url}images/favicon/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="{$media_url}images/favicon/favicon-16x16.png" sizes="16x16" />
        <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="{$media_url}images/favicon/mstile-144x144.png" />

        <!-- Bootstrap core CSS -->
        <link href="{$media_url}bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom styles for this template -->
        <link href="{$media_url}stylesheets/all-bs.css" rel="stylesheet">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <script src="{$media_url}scripts/modernizr-1.6.min.js"></script>
        <!--<script>!window.jQuery && document.write(unescape('%3Cscript src="{$media_url}scripts/jquery-1.4.4.min.js"%3E%3C/script%3E'));</script>-->
        <script src="{$media_url}scripts/jquery.tools.min.js"></script>
        
        <!--<link rel="stylesheet" href="{$media_url}stylesheets/all.css" media="all">-->
        <link rel="stylesheet" href="{$media_url}stylesheets/date.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/buttons.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/quickform.css" media="all">
        
        {block name=additional_stylesheets}{/block}
    </head>

    <body> 
        <nav class="navbar navbar-inverse navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
                
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">              
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
                <a class="navbar-brand"href="index.php?action=dashboard"><span><img src="{$request_url}assets/images/logo.png"/></span>{$app_title}</a>
            </div>
            <div  class="navbar-collapse collapse">
              <ul class="nav navbar-nav navbar-right">
                <!--<li><a href="#">Dashboard</a></li>-->
                <!--<li><a href="#">Settings</a></li>-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {if $my_role_id != -1}
                            {if isset($my_username)} 
                                {$my_firstname} {$my_lastname}<span >{*$my_role_name*}</span>
                                <span>
                                    <img class="img-rounded" style="max-height: 32px;" src="{$access_file}{$my_avatar}" alt="Profile Picture">
                                </span>
                            {/if}
                        {/if}
                        <span class="caret"></span></a>
                        {if isset($my_username)}
                            <ul class="dropdown-menu">
                                {if checkCapabilities('menu:readProfile', $my_role_id, false)}
                                <li><a href="index.php?action=profile&function=edit">
                                        Profil bearbeiten
                                    </a>
                                </li>
                                {/if}
                                {if checkCapabilities('menu:readPassword', $my_role_id, false)}
                                <li><a href="index.php?action=password">
                                        Passwort ändern
                                    </a>
                                </li>
                                {/if}
                                {if checkCapabilities('menu:readMessages', $my_role_id, false)}
                                <li><a href="index.php?action=messages&function=showInbox">
                                        Nachrichten
                                    </a>
                                </li>
                                {/if}
                                {if checkCapabilities('menu:readMessages', $my_role_id, false)}
                                <li><a href="../share/request/uploadframe.php?userID={$my_id}&context=userFiles&target=NULL{$tb_param}" class="thickbox">
                                        Meine Dateien
                                    </a>
                                </li>
                                {/if}
                            <li role="separator" class="divider"></li>
                                <li><a href="index.php?action=logout">
                                        Abmelden
                                    </a>
                                </li>
                            </ul>
                            {/if}
                  </li>
              </ul>
              <!--<form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
              </form>-->
            </div>
          </div>
        </nav>
                
        <div class="container-fluid">
            <div class="row">
                {if $page_name eq 'login' OR $page_name eq 'error' OR $page_name eq 'criteria'} <!--Kein Menu -->
                    {block name=content} {/block}
                    <footer class="footer">
                        <div class="container">
                          <p class="text-muted"><div class="copyright">{$app_footer}<span class="floatright space-right">{if isset($stat_users_online) && checkCapabilities('menu:readPassword', $my_role_id, false)}Erfolgreich erreichte Ziele auf curriculumonline.de: {$stat_acc_all} davon heute: {$stat_acc_today} ({$stat_users_online} User online | Heute: {$stat_users_today}) {/if}</span></div>
                        {block name=footer} {/block}</p>
                        </div>
                    </footer>
                {else}
                    {*'<div id="sidebar2">{block name=sidebar}{/block}{if $my_role_id neq 0} <a class="commentbtn cssbtnmargin cssbtntext tooltip_left" data-tooltip="Fehler melden" href="index.php?action=messages&function=shownewMessage&answer=true&receiver_id=102&subject=Probleme melden"></a>{/if}</div> <!-- sidebar float right -->           *}
                    <div class="col-sm-1 col-md-2 sidebar">{block name=nav}{include file='menu.tpl'}{/block}</div>
                    <div class="col-sm-offset-1 col-md-10 col-md-offset-2 main ">
                    {block name=content} {/block}
                    </div>
                    <footer class="col-xs-1 col-sm-11 col-sm-offset-1 col-md-10 col-md-offset-2 footer">
                        <div class="container">
                          <p class="text-muted"><div class="copyright">{$app_footer}<span class="floatright space-right">{if isset($stat_users_online) && checkCapabilities('menu:readPassword', $my_role_id, false)}Erfolgreich erreichte Ziele auf curriculumonline.de: {$stat_acc_all} davon heute: {$stat_acc_today} ({$stat_users_online} User online | Heute: {$stat_users_today}) {/if}</span></div>
                        {block name=footer} {/block}</p>
                        </div>
                    </footer>
                {/if}
            </div> <!-- end Row-->
        </div>

                
            
            
        <!--<header id="header">
           <div id="app_title" class="floatleft"></div>
           <div id="header_center"></div>
           <div class="logininfo">
            
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
        </header>  -->
        
                <div id="popup" class="modal" onload="popupFunction();" ><p class="center space-top-bottom"><img src="{$base_url}public/assets/images/loadingAnimation.gif"/></p></div> <!-- Popup -->    

        {*(<div id="wrapper"> <!-- damit content/main block 100% breit ist -->
            {if $page_name eq 'login' OR $page_name eq 'error' OR $page_name eq 'criteria'} <!--Kein Menu -->
                 <div class="floatleft"></div>    
            {else}
                
                <div id="sidebar2">{block name=sidebar}{/block}{if $my_role_id neq 0} <a class="commentbtn cssbtnmargin cssbtntext tooltip_left" data-tooltip="Fehler melden" href="index.php?action=messages&function=shownewMessage&answer=true&receiver_id=102&subject=Probleme melden"></a>{/if}</div> <!-- sidebar float right -->           
                {block name=nav} <div id="sidebar1" class="floatleft">{include file='menu.tpl'}</div>{/block}
            {/if}

            <div id="main" class="group ">
                <div id="content" class="space-top ">
                    {block name=content} {/block}
                </div> <!-- end #content -->     
            </div> <!-- end #main -->  
        </div> *}     
                            
        
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
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{$media_url}scripts/jquery-1.12.1.min.js"></script>
    <script src="{$media_url}bootstrap-3.3.6-dist/js/bootstrap.min.js"></script>
    </body>
</html>