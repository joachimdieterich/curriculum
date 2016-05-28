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
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
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

        
        <!-- AdminLTE -->
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"-->
        <link rel="stylesheet" href="{$lib_url}/font-awesome-4.6.1/css/font-awesome.min.css">
        <!-- Ionicons --><!-- not used yet -->
        <!--link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"-->
        <!-- jvectormap -->
        <!--link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.css"-->
        <!-- fullCalendar 2.2.5 -> see calendar.tpl-->
        <!--link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/plugins/fullcalendar/fullcalendar.min.css"-->
        <!--link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/plugins/fullcalendar/fullcalendar.print.css" media="print"-->
        <!-- daterangepicker -->
        <link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{$media_url}templates/AdminLTE-2.3.0/plugins/colorpicker/bootstrap-colorpicker.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- End AdminLTE -->
        
        <!-- Custom styles for this template -->
        <link href="{$media_url}stylesheets/all-bs.css" rel="stylesheet">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!--<script src="{$media_url}scripts/modernizr-1.6.min.js"></script>-->
        <!--<script>!window.jQuery && document.write(unescape('%3Cscript src="{$media_url}scripts/jquery-1.4.4.min.js"%3E%3C/script%3E'));</script>-->
        <!--<script src="{$media_url}scripts/jquery.tools.min.js"></script>-->
        
        <!--<link rel="stylesheet" href="{$media_url}stylesheets/all.css" media="all">-->
        <!--<link rel="stylesheet" href="{$media_url}stylesheets/date.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/buttons.css" media="all">
        <link rel="stylesheet" href="{$media_url}stylesheets/quickform.css" media="all">
        -->
        <link rel="stylesheet" href="{$media_url}jquery.nyroModal/styles/nyroModal.css" media="all">
        
        {block name=additional_stylesheets}{/block}
    </head>
    
    {if $page_action eq 'login'}
        <body class="hold-transition login-page" style="background-image: url('{$request_url}assets/images/backgrounds/CC-BY-SA-miniBLOCKHELDEN20131221_bouldern0004.jpg'); background-size: cover;" >
        {block name=content} {/block}
    </body>
    {else}
    
    <body class="hold-transition skin-blue sidebar-mini" data-spy="scroll" data-target=".modal-body"> 
        <div class="wrapper"> 
            <header class="main-header">
              <!-- Logo -->
              <a href="index.php?action=dashboard" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img class="pull-left" style="margin-top: 5px; margin-left: 2px;" src="{$request_url}assets/images/logo.png"/></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img class="pull-left" style="margin-top: 5px;" src="{$request_url}assets/images/logo.png"/><b>{$app_title}</b></span>
              </a>
                
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    {if isset($my_id)}
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                      <span class="sr-only">Navigation wechseln</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                      <ul class="nav navbar-nav">
                        <li class="calendar-menu">   
                        <a href="index.php?action=calendar" >
                            <i class="fa fa-calendar"></i>
                          </a>
                        </li>  
                        <li class="timeline-menu">   
                        <a href="index.php?action=portfolio" >
                            <i class="fa fa-cubes"></i>
                          </a>
                        </li>  
                        {if isset($mails)}  
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">{count($mails)}</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">Sie haben {count($mails)} neue Nachrichten</li>
                            <li>
                              <!-- inner menu: contains the actual data -->
                              <ul class="menu">
                                 
                                  {section name=mes loop=$mails}
                                      <li><!-- start message -->
                                          <a href="index.php?action=messages&function=showInbox&id={$mails[mes]->id}">
                                            <div class="pull-left">
                                              <img src="{$access_file}{$mails[mes]->sender_file_id|resolve_file_id:"xs"}" class="img-circle" alt="User Image">
                                            </div>
                                            <h4>
                                              {$mails[mes]->sender_username}
                                              <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <p>{$mails[mes]->subject}</p>
                                          </a>
                                      </li>
                                  {/section}
                               
                                <!-- end message -->
                              </ul>
                            </li>
                            <li class="footer"><a href="index.php?action=messages&function=showInbox">Alle Nachrichten</a></li>
                          </ul>
                        </li>
                        {else}
                        <li class=" messages-menu">   
                        <a href="index.php?action=messages&function=showInbox" >
                            <i class="fa fa-envelope-o"></i>
                          </a>
                        </li>
                        {/if} 

                        {if isset($page_message)}
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu open">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" >
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{count($page_message)}</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">Sie haben {count($page_message)} Hinweise</li>
                            <li>
                              <ul class="menu"><!-- inner menu: contains the actual data -->
                                  {section name=mes loop=$page_message}
                                  <li>
                                      <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> {$page_message[mes]}
                                        <!--<i class="fa fa-info text-yellow"></i> {$page_message[mes]}-->
                                      </a>
                                  </li>
                                  {/section}
                              </ul>
                              <li class="footer"><a href="#"> <!--Alle zeigen--></a></li>
                          </ul>
                        </li>
                        {/if}

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{$access_file}{$my_avatar}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{$my_firstname} {$my_lastname}</span>
                          </a>
                          <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                              <img src="{$access_file}{$my_avatar}" class="img-circle" alt="User Image">
                              <p>
                                {$my_firstname} {$my_lastname} - {$my_role_name}
                                <small>Mitglied seit {$my_creation_time}</small>
                              </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                              <div class="col-xs-6 text-center">
                                  {if checkCapabilities('menu:readPassword', $my_role_id, false)}
                                      <a href="#" onclick="formloader('password', 'edit');">Passwort ändern</a>
                                  {/if}
                              </div>
                              <div class="col-xs-6 text-center">
                                  {if checkCapabilities('menu:readMessages', $my_role_id, false)}
                                      {* <a href="../share/request/uploadframe.php?userID={$my_id}&context=userFiles&target=NULL{$tb_param}" class="thickbox">*}
                                      <a href="../share/request/uploadframe.php?userID={$my_id}&context=userFiles&target=NULL{$tb_param}" class="nyroModal">
                                          Meine Dateien
                                      </a>
                                  {/if}
                              </div>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                              <div class="pull-left">
                                  {if checkCapabilities('menu:readProfile', $my_role_id, false)}
                                      <a href="index.php?action=profile&function=edit" class="btn btn-default btn-flat">Profil</a>
                                  {/if}
                              </div>
                              <div class="pull-right">
                                <a href="index.php?action=logout" class="btn btn-default btn-flat">Abmelden</a>
                              </div>
                            </li>
                          </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                      </ul>
                    </div>    
                    {/if}   
                </nav>         
            </header>         
            <!-- Sidebar left - Menu -->
            {if $page_name eq 'login' OR $page_name eq 'error' OR $page_name eq 'criteria'}
                <!--Kein Menu -->
            {else}         
                {block name=nav}{include file='menu.tpl'}{/block}
                 
            {/if}
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div id="popup" class="modal" onload="popupFunction(this.id);"></div> <!-- Popup -->    
                {block name=content} {/block}
            </div> 
            
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                  <b>Version</b> {$app_version}
                </div>
                {$app_footer} {block name=footer} {/block}
            </footer>    
            
            {block name=sidebar_right}{include file='sidebar_right.tpl'}{/block}
            
        </div><!-- ./wrapper -->
        
<!-- SCRIPTS-->  
    <!-- CK Editor -->
    <script src="{$lib_url}/ckeditor/ckeditor.js"></script>
    <!--script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script-->
    <!-- moment -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/moment/moment.min.js"></script>
    <!-- jQuery 2.1.4 -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <!--script src="{$media_url}templates/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script-->
    <!--script src="{$media_url}templates/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script-->
    <!-- SlimScroll 1.3.0 -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <!--script src="{$media_url}templates/AdminLTE-2.3.0/plugins/chartjs/Chart.min.js"></script-->
    <!-- bootstrap color picker -->
    <!--script src="{$media_url}templates/AdminLTE-2.3.0/plugins/colorpicker/bootstrap-colorpicker.min.js"></script-->
    <!-- daterangepicker load dynamic via request -->
    <!--script src="{$media_url}templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js"></script-->
    
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="{$media_url}templates/AdminLTE-2.3.0/dist/js/pages/dashboard2.js"></script>-->
    <!-- AdminLTE for demo purposes -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/dist/js/demo.js"></script>
    
    <!-- jquery.nyroModal -->
    <script src="{$media_url}jquery.nyroModal/js/jquery.nyroModal.custom.js"></script> 
    
    
        <script src="{$media_url}scripts/script.js"></script> 
        <script src="{$media_url}scripts/file.js"></script>
        <script src="{$media_url}scripts/dragndrop.js"></script>     
        {block name=additional_scripts} 
        
        <!-- Logout - Timer  -->
        {if isset($institution_timeout)}
        <script type="text/javascript">
            idleMax = {$global_timeout};        // Logout based on global timout value
            idleTime = 0;
            $(document).ready(function () {
                var idleInterval = setInterval("timerIncrement()", 60000); 
                $(document.getElementById('popup')).attr('class', 'modal');
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
            removeMedia();  // Important to empty audio element cache in webkit browsers. see description on function
            $('#popup').hide();  
            document.getElementById('popup').innerHTML = '<img src="{$base_url}public/assets/images/loadingAnimation.gif"/>';    
        }
        </script>
         <!-- end Logout - Timer  -->
         
         <!-- Nyromodal  -->
         <script type="text/javascript">
        $(function() {
            $('.nyroModal').nyroModal();
            $('#popup_generate').nyroModal();
            //$('.colorpicker').colorpicker();
        });
        
        
        </script>
        {if isset($smarty.session.FORM->form)}
            <script type="text/javascript" > 
                {if isset($smarty.session.FORM->id)}
                    $(document).ready(formloader('{$smarty.session.FORM->form}', '{$smarty.session.FORM->func}', {$smarty.session.FORM->id}));
                {else}
                    $(document).ready(formloader('{$smarty.session.FORM->form}', '{$smarty.session.FORM->func}'));
                {/if}
            </script>
        {/if} 
        
        {/block}  
    </body>
    {/if}
</html>