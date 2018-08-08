<!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
{strip}
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
        <link rel="stylesheet" href="{$template_url}bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{$lib_url}/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="{$template_url}css/google-fonts.min.css" >
        <!-- daterangepicker -->
        <link rel="stylesheet" href="{$template_url}plugins/daterangepicker/daterangepicker.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{$template_url}css/less/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{$template_url}css/less/skins/_all-skins.css">
        <!--link rel="stylesheet" href="{$template_url}css/skins/skin-blue-light.min.css"-->
          <!-- Pace style -->
        <link rel="stylesheet" href="{$template_url}plugins/pace/pace.min.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{$template_url}plugins/colorpicker/bootstrap-colorpicker.min.css">
        <!-- Custom styles for this template -->
        <!-- <link rel="stylesheet" href="{$template_url}css/all-bs.min.css">-->
        <link rel="stylesheet" href="{$template_url}css/buttons.min.css">
        <link rel="stylesheet" href="{$media_url}jquery.nyroModal/styles/nyroModal.min.css" media="all">
        {block name=additional_stylesheets}{/block}
    </head>
    
    {if in_array($page_action, array('login', 'lock', 'extern'))}
    <body class="hold-transition {if $page_action eq 'login' OR  $page_action eq 'extern'}login-page{/if} {if $page_action eq 'lock'}lockscreen{/if}" style="background-image: url('{$random_bg}'); background-size: cover;" >
        <div id="popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div></div> <!-- Popup -->    
        {block name=content} {/block}
    {else}
    <body class="hold-transition {$page_layout} skin-blue" data-spy="scroll" data-target=".modal-body" style=" -webkit-overflow-scrolling:touch; overflow:auto;" > 
        <div id="body-wrapper" class="{$page_body_wrapper}"> 
            {if $page_header}
            <header class="main-header">
              <!-- Logo -->
              <a href="index.php?action=dashboard" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img class="pull-left" style="margin-top: 5px; margin-left: 2px;" src="{$request_url}assets/images/logo.png"/></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img class="pull-left" style="margin-top: 5px;" src="{$request_url}assets/images/logo.png"/><b>{$app_title}</b></span>
              </a>
                
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation" {if isset($page_bg_file_id)}style="background: url('{$access_file_id}{$page_bg_file_id}') center center;  background-size: cover;"{/if}>
                    
                    {if isset($my_id)}
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                      <span class="sr-only">Navigation wechseln</span>
                    </a>
                    
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                      <ul class="nav navbar-nav">
                        {if checkCapabilities('dashboard:globalAdmin', $my_role_id, false)}  
                        <li>   
                        <a href="index.php?action=statistic" style="padding: 15px 8px 15px 8px;">
                            <i class="fa fa-pie-chart"></i>
                          </a>
                        </li>  
                        {/if}
                        <li>   
                        <a href="index.php?action=help" style="padding: 15px 8px 15px 8px;">
                            <i class="fa fa-graduation-cap"></i>
                          </a>
                        </li>
                        {if checkCapabilities('menu:readTasks', $my_role_id, false)}  
                        <li>     
                        <a href="index.php?action=task" style="padding: 15px 8px 15px 8px;">
                            <i class="fa fa-tasks"></i>
                          </a>
                        </li>
                        {/if}
                         {if isset($mySemester) AND count($mySemester) > 1}
                             {Form::input_dropdown('semester_id', '', $mySemester, 'semester, institution', 'id', $my_semester_id, null, "processor('semester','set',this.getAttribute('data-id'));")}
                         {else if isset($my_institutions) AND count($my_institutions) > 1}
                             {Form::input_dropdown('institution_id', '', $my_institutions, 'institution', 'institution_id', $my_institution_id, null, "processor('config','institution_id', this.getAttribute('data-id'));")}
                         {/if} 
                         <li class="calendar-menu">   
                        <a href="index.php?action=calendar" style="padding: 15px 8px 15px 8px;">
                            <i class="fa fa-calendar"></i>
                          </a>
                        </li>  
                        {if checkCapabilities('menu:readTimeline', $my_role_id, false)}  
                        <li class="timeline-menu">   
                        <a href="index.php?action=portfolio" style="padding: 15px 8px 15px 8px;">
                            <i class="fa fa-cubes"></i>
                          </a>
                        </li> 
                        {/if}
                        {if checkCapabilities('menu:readMessages', $my_role_id, false)}
                            {if isset($mails)}  
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 8px 15px 8px;">
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
                                                  <small><i class="fa fa-calendar-times-o"></i> {$mails[mes]->creation_time}</small>
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
                                <a href="index.php?action=messages&function=showInbox" style="padding: 15px 8px 15px 8px;"><i class="fa fa-envelope-o"></i></a>
                            </li>
                            {/if} 
                        {/if}

                        {if isset($page_message)}
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu open">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 8px 15px 8px;">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{count($page_message)}</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">Sie haben {count($page_message)} Hinweise</li>
                            <li>
                              <ul class="menu"><!-- inner menu: contains the actual data -->
                                  {section name=mes loop=$page_message}
                                  <li>
                                      <a href="#" style="white-space: normal">
                                        {if is_array($page_message[mes])}
                                            <i class="fa {if isset($page_message[mes]['icon'])}{$page_message[mes]['icon']}{else}fa-warning text-yellow{/if}"></i> {$page_message[mes]['message']}
                                        {else}
                                            <i class="fa fa-warning text-yellow"></i> {$page_message[mes]}
                                        {/if}
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
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 8px 15px 8px;">
                            <img src="{$access_file}{$my_avatar}" class="user-image" alt="User Image">
                            <span class="hidden-xs">{$my_firstname} {$my_lastname}</span>
                          </a>
                          <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                              <img src="{$access_file}{$my_avatar}" class="img-circle" alt="User Image">
                              <p>
                                {$my_firstname} {$my_lastname} - {$my_role_name}
                                {*<small>Mitglied seit {$my_creation_time}</small>*}
                              </p>
                            </li>
                            <!-- Menu Body -->
                            <!--li class="user-body"></li-->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                              <div class="pull-left">
                                  {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                                      <a href="#" class="btn btn-default btn-flat pull-left" onclick="formloader('password', 'edit');" data-toggle="tooltip" title="Passwort ändern"><i class="fa fa-user-secret"></i></a>
                                  {/if}
                                  {if checkCapabilities('user:update', $my_role_id, false)}
                                      <a href="#" class="btn btn-default btn-flat  pull-left" onclick="formloader('profile', 'edit');" data-toggle="tooltip" title="Profil bearbeiten"><i class="fa fa-user"></i></a>
                                  {/if}
                                  {if checkCapabilities('menu:readFiles', $my_role_id, false)}
                                      <a href="{$template_url}renderer/uploadframe.php?context=userFiles{$tb_param}" data-toggle="tooltip" title="Meine Dateien" class="btn btn-default btn-flat  nyroModal">
                                         <i class="fa fa-folder-open"></i>
                                      </a>
                                  {/if} 
                              </div>
                              <div class="pull-right">
                                <a href="index.php?action=logout" data-toggle="tooltip" title="Abmelden" class="btn btn-default btn-flat pull-right">Abmelden</a>
                                <a href="index.php?action=lock" data-toggle="tooltip" title="Fenster sperren" class="btn btn-default btn-flat pull-right"><i class="fa fa-lock"></i></a>
                              </div>
                            </li>
                          </ul>
                        </li>
                        {if checkCapabilities('template:change', $my_role_id, false)}
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gears"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="#" onclick="formloader('settings', 'edit');">Einstellungen</a></li>
                                  <li class="divider"></li>
                                  <li><a href="index.php?action=navigator">Navigator (Test)</a></li>
                                  <li><a href="index.php?action=debug">Debug / Userfeedback</a></li>
                                  <li><a href="index.php?action=update">Updates</a></li>
                                </ul>
                            </li>
                            {*<!-- Control Sidebar Toggle Button -->
                            <li>
                              <a href="#" onclick="formloader('settings', 'edit');"><i class="fa fa-gears"></i>
                                  {if checkCapabilities('system:update', $my_role_id, false) AND isset($system_update)}<span class="label label-danger">Update</span>{/if}
                              </a>
                            </li>*}
                        {/if}
                      </ul>
                    </div>    
                    {/if}   
                </nav>         
            </header>        
            {/if}    
            <!-- Sidebar left - Menu -->
            {if {$page_layout} neq 'layout-top-nav'} <!--Kein Menu -->        
                {block name=nav}{include file='menu.tpl'}{/block} 
            {/if}
            
            <!-- Content Wrapper. Contains page content -->
            <div id="content-wrapper" class="content-wrapper">
                <div id="popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div></div> <!-- Popup -->    
                {block name=content} {/block}
            </div> 
            
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                  <b>Version</b> {$app_version} 
                </div>
                <a class="btn-xs margin-r-10 pull-right" onclick='formloader("content", "new", null,{["label_title"=>"Betreff", "label_content"=>"Fehler beschreiben", "label_header"=>"Fehler melden","label_save"=>"Meldung abschicken", "context"=>"debug", "reference_id"=> 0]|@json_encode nofilter});'>
                    <i class="fa fa-bullhorn text-warning"></i> Fehler melden
                </a>
                {$app_footer} {block name=footer}  <small><a onclick="formloader('terms')">Impressum / Datenschutz</a></small>{/block}
            </footer>    
              
            {*block name=sidebar_right}{include file='sidebar_right.tpl'}{/block*}
        </div><!-- ./wrapper -->
    {/if}    
<!-- SCRIPTS-->  
    <script src="{$lib_url}ckeditor/ckeditor.js"></script><!-- CK Editor -->
    <script src="{$template_url}plugins/moment/moment.min.js"></script><!-- moment -->
    <script src="{$media_url}scripts/jquery-2.2.1.min.js"></script> <!-- jQuery 2.2.1 -->
    <script src="{$media_url}scripts/alterClass.min.js"></script> <!-- alter class -->
    <script src="{$template_url}bootstrap/js/bootstrap.min.js"></script><!-- Bootstrap 3.3.5 -->
    <script src="{$template_url}js/app.min.js"></script><!-- AdminLTE App -->
    <script src="{$template_url}plugins/slimScroll/jquery.slimscroll.min.js"></script><!-- SlimScroll 1.3.0 -->
    <script src="{$template_url}plugins/pace/pace.min.js"></script>
    <script src="{$template_url}plugins/mark/jquery.mark.min.js"></script>
    <script src="{$media_url}scripts/curriculum.min.js"></script><!-- curriculum settings (sidebar) -->
    <script src="{$media_url}jquery.nyroModal/js/jquery.nyroModal.custom.min.js"></script> <!-- jquery.nyroModal -->
    <script src="{$media_url}scripts/script.min.js"></script> 
    <script src="{$media_url}scripts/PDFObject-master/pdfobject.min.js"></script> 
    <script src="{$media_url}scripts/file.min.js"></script>
    <script src="{$media_url}scripts/dragndrop.min.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{$template_url}plugins/select2/select2.min.css">
    <script src="{$template_url}plugins/select2/select2.min.js"></script>
    <link rel="stylesheet" href="{$template_url}css/less/select2.min.css">
    <!-- MathJax -->
    {literal}
        <script type="text/x-mathjax-config">
            MathJax.Hub.Config({
              extensions: ["tex2jax.js"],
              jax: ["input/TeX","output/HTML-CSS"],
              tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
            });
        </script>
    {/literal}
     <script src="{$lib_url}MathJax-master/MathJax.js"></script><!-- MathJax-->
    {block name=additional_scripts} 
    <!-- Logout - Timer  -->
    {if isset($institution_timeout)}
    <script type="text/javascript">
        idleTime = 0;
        $(document).ready(function () {
            InitScripts();
            
            /*Increment the idle time counter every minute.*/
            var idleInterval = setInterval(timerIncrement, 60000); /*1 minute*/

            /*Zero the idle timer on mouse movement.*/
            $(this).mousemove(function (e) { idleTime = 0; });
            $(this).keypress(function (e) { idleTime = 0; });
            
            $(document.getElementById('popup')).attr('class', 'modal');
            $(".select2").select2();  
        });
        
    function timerIncrement() {
            idleTime++;
            if (idleTime === {$global_timeout}) { 
                window.location="index.php?action=logout&timout=true";
            }
        }       
    </script>
    {/if}
    <!-- end Logout - Timer  -->
    
    {if isset($smarty.session.FORM->form)}
        <script type="text/javascript">
            {if isset($smarty.session.FORM->id)}
                {if $smarty.session.FORM->id neq ''}
                    $(document).ready(formloader('{$smarty.session.FORM->form}', '{$smarty.session.FORM->func}', {$smarty.session.FORM->id}));
                {/if}
                $(document).ready(formloader('{$smarty.session.FORM->form}', '{$smarty.session.FORM->func}'));
            {else}
                $(document).ready(formloader('{$smarty.session.FORM->form}', '{$smarty.session.FORM->func}'));
            {/if}
        </script>
    {/if} 
   
    {/block}  
    </body>
</html>
{/strip}