<!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
{strip}
<html lang="de" class="no-js" > <!--<![endif]-->
{* define global validate function*}
{*function validate_msg field=''}
    {if isset($v_error.$field)}
        {foreach key=err item=v_field from=$v_error.$field.message}
                <p><label></label>{$v_field}</p>   
        {/foreach} 
    {/if}
{/function*}
   
    <head>
        <meta charset="utf-8">
        <!-- Sets initial viewport load and disables zooming  -->
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="keywords" content="curriculum, digitaler Lehrplan, curriculumonline.de">
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
        
        <link rel="stylesheet" href="{$template_url}css/site.min.css">
        <link rel="stylesheet" href="{$template_url}css/all-bs.min.css">
        <script type="text/javascript" src="{$template_url}js/site.min.js"></script>
        
        <!-- Bootstrap 3.3.5 -->
        <!--<link rel="stylesheet" href="{$template_url}css/bootstrap.min.css">-->
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{$lib_url}/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="{$media_url}stylesheets/google-fonts.min.css" >
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{$template_url}plugins/colorpicker/bootstrap-colorpicker.min.css">
        {*<!-- Ionicons --><!-- not used yet -->
        <!--link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"-->
        <!-- daterangepicker -->
        <link rel="stylesheet" href="{$template_url}plugins/daterangepicker/daterangepicker.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{$template_url}css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{$template_url}css/skins/_all-skins.min.css">
        <!--link rel="stylesheet" href="{$template_url}css/skins/skin-blue-light.min.css"-->
        

        <link rel="stylesheet" href="{$media_url}stylesheets/buttons.min.css" media="all">*}
        <link rel="stylesheet" href="{$media_url}jquery.nyroModal/styles/nyroModal.css" media="all">
        {block name=additional_stylesheets}{/block}
    </head>
    
    {if $page_action eq 'login' OR  $page_action eq 'lock'}
        <body style="background-image: url('{$random_bg}'); background-size: cover;" >
            {block name=content} {/block}
        </body>
    {else}
        <body class="bg-aqua">
             {if isset($page_bg_file_id)}
                    <span style="position: fixed;left: 0;top: 0;right:0; height:600px;background-image: url('{$access_file_id}{$page_bg_file_id}'); background-position: center center; background-size: cover;  background-repeat: no-repeat;"></span>
                {else}
                    <span style="position: fixed;left: 0;top: 0;right:0; height:600px;background-image: url('{$random_bg}'); background-position: center center; background-size: cover;  background-repeat: no-repeat;"></span>
                {/if}
            <div class="transparent_gradient" style="position: fixed;left: 0;top: 0;right:0; height:600px;"></div>
            {* NAVBAR *}
                <nav class="navbar navbar-default navbar-custom" role="navigation">
                    <div style="padding-left:15px;padding-right:15px;">
                      <div class="navbar-header">
                          <button type="button" class="navbar-toggle " data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars text-white"></i>
                            <span class="sr-only">Toggle navigation</span>
                        </button>
                            <a class="navbar-brand" href="index.php?action=dashboard"><img src="{$request_url}assets/images/logo_white_bg.png" height="40"></h5></a>
                      </div>
                      <ul class="nav navbar-nav navbar-left">
                            <li><a><h5 style="padding:0;margin:0;">{$page_title}</h5></a></li>
                      </ul>
                      <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            {if checkCapabilities('dashboard:globalAdmin', $my_role_id, false)}  
                                <li class="pull-left" style="display: inline-block;">   
                                    <a href="index.php?action=statistic" style="padding: 15px 8px 15px 8px;">
                                     <i class="fa fa-pie-chart"></i>
                                    </a>
                                </li>  
                            {/if}
                            <li class="pull-left" style="display: inline-block;">   
                                <a href="index.php?action=help" style="padding: 15px 8px 15px 8px;">
                                    <i class="fa fa-graduation-cap"></i>
                                </a>
                            </li>
                            {if isset($mySemester) AND count($mySemester) > 1}
                                {Form::input_dropdown(['class' => 'pull-left', 'style' => 'display: inline-block; ', 'icon' => 'fa-history', 'select_data' => $mySemester, 'select_label' => 'semester, institution', 'select_value' => 'id', 'input' => $my_semester_id, 'onclick' => "processor(\'semester\',\'set\',this.getAttribute(\'data-id\'));"])}
                            {else if isset($my_institutions) AND count($my_institutions) > 1}
                                {Form::input_dropdown(['class' => 'pull-left', 'style' => 'display: inline-block; ', 'icon' => 'fa-history', 'select_data' => $my_institutions, 'select_label' => 'institution', 'institution_id', 'select_value' => 'institution_id', 'input' => $my_institution_id, 'onclick' => "processor('config','institution_id', this.getAttribute('data-id'));"])}
                            {/if} 
                            
                            {if isset($mails)}  
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown pull-left" style="display: inline-block;">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" >
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">{count($mails)}</span>
                              </a>
                              <ul class="dropdown-menu" role="menu">
                                <li class="header">Sie haben {count($mails)} neue Nachrichten</li>
                                <li>
                                    {section name=mes loop=$mails}
                                        <li><!-- start message -->
                                            <a href="index.php?action=messages&function=showInbox&id={$mails[mes]->id}">
                                              <div class="pull-left">
                                                <!--img src="{$access_file}{$mails[mes]->sender_file_id|resolve_file_id:"xs"}" class="" alt="User Image"-->
                                              </div>
                                                {$mails[mes]->sender_username}<br>
                                                <small><i class="fa fa-calendar-times-o"></i> {$mails[mes]->creation_time}</small>
                                              <p>{$mails[mes]->subject}</p>
                                            </a>
                                        </li>
                                    {/section}
                                  <!-- end message -->
                                  
                                </li>
                                <li class="footer"><a href="index.php?action=messages&function=showInbox">Alle Nachrichten</a></li>
                              </ul>
                            </li>
                            {else}
                            <li class="messages-menu pull-left margin-r-10" style="display: inline-block;">   
                                <a href="index.php?action=messages&function=showInbox" style="padding: 15px 8px 15px 8px;"><i class="fa fa-envelope-o"></i></a>
                            </li>
                            {/if} 
                            
                            {if isset($page_message)}
                            <li class="dropdown pull-left" style="display: inline-block;">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
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
                            
                            <li class="calendar-menu pull-left margin-r-10" style="display: inline-block;">   
                                <a href="index.php?action=calendar" style="padding: 15px 8px 15px 8px;">
                                    <i class="fa fa-calendar"></i>
                                </a>
                            </li> 
                            <li class="timeline-menu" style="display: inline-block;">   
                                <a href="index.php?action=portfolio" style="padding: 15px 8px 15px 8px;">
                                    <i class="fa fa-cubes"></i>
                                </a>
                            </li>
                            
                            <!-- dropdown Lehrpläne -->
                            <li class="dropdown ">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Lehrpläne <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu" style="width:350px; overflow: scroll; max-height: 200px;">
                                {if $my_enrolments != ''}
                                    {foreach item=cur_menu from=$my_enrolments name=enrolments}
                                        {if $cur_menu->semester_id eq $my_semester_id}
                                            {if  $cur_menu->id eq $cur_menu->base_curriculum_id || $cur_menu->base_curriculum_id eq null}
                                                <li {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} class="active"{/if}{/if}>                                
                                                    <a class="text-ellipse" href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}" style="padding-right:20px;">
                                                        {$cur_menu->curriculum}<span class="label pull-right bg-green">{$cur_menu->groups}</span>
                                                    </a>
                                                </li>
                                            {/if}
                                        {/if}
                                    {/foreach}
                                {else}
                                    <li>
                                        <a href=""><i class="fa fa-dashboard"></i><span>Sie sind in keinen Lehrplan <br>eingeschrieben</span></a>
                                    </li>
                                {/if} 
                            </ul>
                          </li>
                          <!-- ./dropdown Lehrpläne -->
                          
                          <!-- dropdown Funktionen -->
                            <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Funktionen <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu" style="width:350px;">
                            {if checkCapabilities('menu:readMyInstitution', $my_role_id, false)}
                                <li class="header">Institution: {$my_institution->institution}</li>
                                {if checkCapabilities('menu:readObjectives', $my_role_id, false)}
                                <li class="{if $page_action eq 'objectives'}active{/if}">
                                    <a href="index.php?action=objectives">
                                        <i class="fa fa-edit margin-r-10"></i>Lernstand eingeben
                                    </a>
                                </li>
                                {/if}
                                {if checkCapabilities('menu:readCourseBook', $my_role_id, false)}
                                <li class="{if $page_action eq 'courseBook'}active{/if}">
                                    <a href="index.php?action=courseBook">
                                        <i class="fa fa-book margin-r-10"></i>Kursbuch
                                    </a>
                                </li>
                                {/if}

                                {if checkCapabilities('menu:readCurriculum', $my_role_id, false)}
                                <li class="{if $page_action eq 'curriculum'}active{/if}">
                                    <a href="index.php?action=curriculum">
                                        <i class="fa fa-th margin-r-10"></i>Lehrpläne
                                    </a>
                                </li>                  
                                {/if}

                                {if checkCapabilities('menu:readGroup', $my_role_id, false)}
                                    <li class="{if $page_action eq 'group'}active{/if}">
                                        <a href="index.php?action=group">
                                            <i class="fa fa-group margin-r-10"></i>Lerngruppen
                                        </a>
                                    </li>
                                {/if}

                                {if checkCapabilities('menu:readUser', $my_role_id, false)}
                                    <li class="{if $page_action eq 'user'}active{/if}">
                                        <a href="index.php?action=user">
                                            <i class="fa fa-user margin-r-10"></i>Benutzer
                                        </a>
                                    </li>
                                {/if}

                                {if checkCapabilities('menu:readRole', $my_role_id, false)}
                                    <li class="{if $page_action eq 'role'}active{/if}">
                                        <a href="index.php?action=role">
                                            <i class="fa fa-key margin-r-10"></i>Rollenverwaltung
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readGrade', $my_role_id, false)}
                                    <li class="{if $page_action eq 'grade'}active{/if}">
                                        <a href="index.php?action=grade">
                                            <i class="fa fa-signal margin-r-10"></i>Klassenstufen
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readSubject', $my_role_id, false)}
                                    <li class="{if $page_action eq 'subject'}active{/if}">
                                        <a href="index.php?action=subject">
                                            <i class="fa fa-language margin-r-10"></i>Fächer
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readSemester', $my_role_id, false)}
                                    <li class="{if $page_action eq 'semester'}active{/if}">
                                        <a href="index.php?action=semester">
                                            <i class="fa fa-history margin-r-10"></i>Lernzeiträume
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readBackup', $my_role_id, false)}
                                    <li class="{if $page_action eq 'backup'}active{/if}">
                                        <a href="index.php?action=backup">
                                            <i class="fa fa-cloud-download margin-r-10"></i>Backup
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readCertificate', $my_role_id, false)}   
                                    <li class="{if $page_action eq 'certificate'}active{/if}">
                                        <a href="index.php?action=certificate">
                                            <i class="fa fa-files-o margin-r-10"></i>Zertifikate
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readInstitution', $my_role_id, false)}   
                                    <li class="{if $page_action eq 'institution'}active{/if}">
                                        <a href="index.php?action=institution">
                                            <i class="fa fa-university margin-r-10"></i>Institutionen
                                        </a>
                                    </li>
                                {/if}
                                {if checkCapabilities('menu:readLog', $my_role_id, false)}
                                <li {if $page_action eq 'log'}class="active"{/if}>
                                    <a href="index.php?action=log">
                                        <i class="fa fa-list margin-r-10"></i><span>Berichte</span>
                                    </a>
                                </li>
                                {/if}
                            {/if}
                            </ul>
                          </li>
                          <!-- ./dropdown Funktionen -->
                          <!-- dropdown Usermenü -->
                          <li class="dropdown ">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="media-object img-rounded" style="height: 18px;" src="{$access_file}{$my_avatar}" class="user-image" alt="User Image"> <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu" style="width:350px;">
                                <li class="dropdown-header">{$my_firstname} {$my_lastname}<span class="badge badge-primary pull-right">{$my_role_name}</span><br><small>Mitglied seit {$my_creation_time}</small></li>
                                <li> <span class="pull-left">
                                        {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                                            <a href="#" class="pull-left" onclick="formloader('password', 'edit');" data-toggle="tooltip" title="Passwort ändern"><i class="fa fa-user-secret"></i></a>
                                        {/if}
                                        {if checkCapabilities('user:update', $my_role_id, false)}
                                            <a href="#" class="pull-left" onclick="formloader('profile', 'edit');" data-toggle="tooltip" title="Profil bearbeiten"><i class="fa fa-user"></i></a>
                                        {/if}
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="../share/templates/Bootflat-2.0.4/renderer/uploadframe.php?context=userFiles{$tb_param}" data-toggle="tooltip" title="Meine Dateien" class="pull-left nyroModal">
                                                <i class="fa fa-folder-open"></i>
                                            </a>
                                        {/if} 
                                    </span>
                                    <span class="pull-right">
                                        <a href="index.php?action=logout" data-toggle="tooltip" title="Abmelden" class=" pull-right">Abmelden</a>
                                        <a href="index.php?action=lock" data-toggle="tooltip" title="Fenster sperren" class="pull-right"><i class="fa fa-lock"></i></a>
                                    </span>
                                </li>
                            </ul>
                          </li>
                          <!-- ./dropdown Usermenü -->
                        </ul>
                    </div>
                    </div>
                </nav>
            {* ./NAVBAR *}
            
            
            <div class="row" >
                <div class="col-xs-12">
                    <div  class="content-top-padding" >
                    <div id="popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="panel"><div class="panel-heading"><h3 >Loading...</h3></div><div class="panel-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div></div> <!-- Popup -->    
                    {block name=content} {/block}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                  <div class="footer">
                    <div class="container">
                      <div class="clearfix">
                        <div class="footer-logo"><a href="#"><img src="{$request_url}assets/images/logo_white_bg.png">{$app_title}</a></div>
                        <dl class="footer-nav">
                          <dt class="nav-title">ABOUT</dt>
                          <dd class="nav-item"><a href="http://curriculumonline.de" target="_blank"><i class="fa fa-question-circle"></i> curriculum</a></dd>
                        </dl>
                        <dl class="footer-nav">
                          <dt class="nav-title">CONTRIBUTING</dt>
                          <dd class="nav-item"><a href="http://www.github.com/joachimdieterich/curriculum" target="_blank"><i class="fa fa-github"></i> github</a></dd>
                        </dl>
                        <dl class="footer-nav">
                          <dt class="nav-title">CONTACT</dt>
                          <dd class="nav-item"><a href="mailto:mail@joachimdieterich.de"><i class="fa fa-at"></i> Joachim Dieterich</a></dd>
                        </dl>
                      </div>
                      <div class="footer-copyright text-center">{$app_footer}</div>
                    </div>
                  </div>
                </div>
            </div>
        </body>
        
    {/if}    
<!-- SCRIPTS-->  
    <script src="{$lib_url}ckeditor/ckeditor.js"></script><!-- CK Editor -->
    <script src="{$template_url}plugins/moment/moment.min.js"></script><!-- moment -->
    <script src="{$media_url}scripts/jquery-2.2.1.min.js"></script> <!-- jQuery 2.2.1 -->
    <script src="{$template_url}plugins/slimScroll/jquery.slimscroll.min.js"></script><!-- SlimScroll 1.3.0 -->
    <script src="{$media_url}scripts/curriculum.min.js"></script><!-- curriculum settings (sidebar) -->
    <script src="{$media_url}jquery.nyroModal/js/jquery.nyroModal.custom.js"></script> <!-- jquery.nyroModal -->
    <script src="{$media_url}scripts/script.min.js"></script> 
    <script src="{$media_url}scripts/PDFObject-master/pdfobject.min.js"></script> 
    <script src="{$media_url}scripts/file.min.js"></script>
    <script src="{$media_url}scripts/dragndrop.min.js"></script>     
    
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
    <!-- end Logout - Timer  -->

    <!-- Nyromodal  -->
    <script type="text/javascript">
    $(function() {
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
    
    </script>
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