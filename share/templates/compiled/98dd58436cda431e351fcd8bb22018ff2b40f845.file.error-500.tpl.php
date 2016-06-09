<?php /* Smarty version Smarty-3.0.6, created on 2016-06-09 16:44:35
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/error-500.tpl" */ ?>
<?php /*%%SmartyHeaderCode:989779695575980d3e97038-37377164%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98dd58436cda431e351fcd8bb22018ff2b40f845' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/error-500.tpl',
      1 => 1465483474,
      2 => 'file',
    ),
    'e9d941a08708e3e9080a36e68e883c7c15367c0d' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/base.tpl',
      1 => 1465371435,
      2 => 'file',
    ),
    '8c7bf3ef5fb3e1db5785d70d14743e45c0444d09' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl',
      1 => 1465465168,
      2 => 'file',
    ),
    'a235b4fdd0e1722b2076299289a74aa96ff2f11a' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/sidebar_right.tpl',
      1 => 1458766008,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '989779695575980d3e97038-37377164',
  'function' => 
  array (
    'validate_msg' => 
    array (
      'parameter' => 
      array (
        'field' => '\'\'',
      ),
      'compiled' => '',
    ),
  ),
  'has_nocache_code' => 0,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_resolve_file_id')) include '/Applications/MAMP/htdocs/curriculum/share/libs/Smarty-3.0.6/libs/plugins/modifier.resolve_file_id.php';
?><!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="de" class="no-js"> <!--<![endif]-->
<?php if (!function_exists('smarty_template_function_validate_msg')) {
    function smarty_template_function_validate_msg($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->template_functions['validate_msg']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable(trim($value,'\''));};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php if (isset($_smarty_tpl->getVariable('v_error',null,true,false)->value[$_smarty_tpl->getVariable('field',null,true,false)->value])){?>
        <?php  $_smarty_tpl->tpl_vars['v_field'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['err'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('v_error')->value[$_smarty_tpl->getVariable('field')->value]['message']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v_field']->key => $_smarty_tpl->tpl_vars['v_field']->value){
 $_smarty_tpl->tpl_vars['err']->value = $_smarty_tpl->tpl_vars['v_field']->key;
?>
                <p><label></label><?php echo $_smarty_tpl->tpl_vars['v_field']->value;?>
</p>   
        <?php }} ?> 
    <?php }?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;}}?>

   
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
        <title>Interner Serverfehler | <?php echo strip_tags($_smarty_tpl->getVariable('app_title')->value);?>
</title>
        <meta name="description" content="">
        <meta name="author" content="Joachim Dieterich (www.curriculumonline.de)">
        
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-152x152.png" />
        <link rel="icon" type="image/png" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/favicon-16x16.png" sizes="16x16" />
        <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/mstile-144x144.png" />

        
        <!-- AdminLTE -->
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"-->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
/font-awesome-4.6.1/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/google-fonts.css" >
        <!-- Ionicons --><!-- not used yet -->
        <!--link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"-->
        <!-- jvectormap -->
        <!--link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.css"-->
        <!-- fullCalendar 2.2.5 -> see calendar.tpl-->
        <!--link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/fullcalendar/fullcalendar.min.css"-->
        <!--link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/fullcalendar/fullcalendar.print.css" media="print"-->
        <!-- daterangepicker -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/colorpicker/bootstrap-colorpicker.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- End AdminLTE -->
        
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/all-bs.css">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!--<script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/modernizr-1.6.min.js"></script>-->
        <!--<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/jquery-1.4.4.min.js"%3E%3C/script%3E'));</script>-->
        <!--<script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/jquery.tools.min.js"></script>-->
        
        <!--<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/all.css" media="all">-->
        <!--<link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/date.css" media="all">
        -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/buttons.css" media="all">
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
jquery.nyroModal/styles/nyroModal.css" media="all">
        
    </head>
    
    <?php if ($_smarty_tpl->getVariable('page_action')->value=='login'){?>
        <body class="hold-transition login-page" style="background-image: url('<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/backgrounds/CC-BY-SA-miniBLOCKHELDEN20131221_bouldern0004.jpg'); background-size: cover;" >
        
    <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            500 Error
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php?action=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">500 Error</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="error-page">
            <h2 class="headline text-red">500</h2>
            <div class="error-content"><br>
              <h3><i class="fa fa-warning text-red"></i> Interner Server Fehler.</h3>
              <p>
                Sollte der Fehler dauerhaft bestehen, melden Sie diesen bitte an einen Administrator.
                Hier gehts zurück auf die  <a href="index.php?action=dashboard">Startseite</a>.
              </p>
            </div>
          </div><!-- /.error-page -->

        </section><!-- /.content -->

    </body>
    <?php }else{ ?>
    
    <body class="hold-transition skin-blue sidebar-mini" data-spy="scroll" data-target=".modal-body"> 
        <div class="wrapper"> 
            <header class="main-header">
              <!-- Logo -->
              <a href="index.php?action=dashboard" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><img class="pull-left" style="margin-top: 5px; margin-left: 2px;" src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/logo.png"/></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img class="pull-left" style="margin-top: 5px;" src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/logo.png"/><b><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</b></span>
              </a>
                
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <?php if (isset($_smarty_tpl->getVariable('my_id',null,true,false)->value)){?>
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
                        <?php if (isset($_smarty_tpl->getVariable('mails',null,true,false)->value)){?>  
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success"><?php echo count($_smarty_tpl->getVariable('mails')->value);?>
</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">Sie haben <?php echo count($_smarty_tpl->getVariable('mails')->value);?>
 neue Nachrichten</li>
                            <li>
                              <!-- inner menu: contains the actual data -->
                              <ul class="menu">
                                 
                                  <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['name'] = 'mes';
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('mails')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total']);
?>
                                      <li><!-- start message -->
                                          <a href="index.php?action=messages&function=showInbox&id=<?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->id;?>
">
                                            <div class="pull-left">
                                              <img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo smarty_modifier_resolve_file_id($_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->sender_file_id,"xs");?>
" class="img-circle" alt="User Image">
                                            </div>
                                            <h4>
                                              <?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->sender_username;?>

                                              <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <p><?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->subject;?>
</p>
                                          </a>
                                      </li>
                                  <?php endfor; endif; ?>
                               
                                <!-- end message -->
                              </ul>
                            </li>
                            <li class="footer"><a href="index.php?action=messages&function=showInbox">Alle Nachrichten</a></li>
                          </ul>
                        </li>
                        <?php }else{ ?>
                        <li class=" messages-menu">   
                        <a href="index.php?action=messages&function=showInbox" >
                            <i class="fa fa-envelope-o"></i>
                          </a>
                        </li>
                        <?php }?> 

                        <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu open">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" >
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning"><?php echo count($_smarty_tpl->getVariable('page_message')->value);?>
</span>
                          </a>
                          <ul class="dropdown-menu">
                            <li class="header">Sie haben <?php echo count($_smarty_tpl->getVariable('page_message')->value);?>
 Hinweise</li>
                            <li>
                              <ul class="menu"><!-- inner menu: contains the actual data -->
                                  <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['name'] = 'mes';
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('page_message')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['mes']['total']);
?>
                                  <li>
                                      <a href="#">
                                        <?php if (is_array($_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']])){?>
                                            <i class="fa <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['mes']['index']]['icon'])){?><?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]['icon'];?>
<?php }else{ ?>fa-warning text-yellow<?php }?>"></i> <?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]['message'];?>

                                        <?php }else{ ?>
                                            <i class="fa fa-warning text-yellow"></i> <?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']];?>

                                        <?php }?>
                                      </a>
                                  </li>
                                  <?php endfor; endif; ?>
                              </ul>
                              <li class="footer"><a href="#"> <!--Alle zeigen--></a></li>
                          </ul>
                        </li>
                        <?php }?>

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
</span>
                          </a>
                          <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                              <img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
" class="img-circle" alt="User Image">
                              <p>
                                <?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
 - <?php echo $_smarty_tpl->getVariable('my_role_name')->value;?>

                                <small>Mitglied seit <?php echo $_smarty_tpl->getVariable('my_creation_time')->value;?>
</small>
                              </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                              <div class="col-xs-6 text-center">
                                  <?php if (checkCapabilities('menu:readPassword',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                                      <a href="#" onclick="formloader('password', 'edit');">Passwort ändern</a>
                                  <?php }?>
                              </div>
                              <div class="col-xs-6 text-center">
                                  <?php if (checkCapabilities('menu:readMessages',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                                      <a href="../share/request/uploadframe.php?userID=<?php echo $_smarty_tpl->getVariable('my_id')->value;?>
&context=userFiles&target=NULL<?php echo $_smarty_tpl->getVariable('tb_param')->value;?>
" class="nyroModal">
                                          Meine Dateien
                                      </a>
                                  <?php }?>
                              </div>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                              <div class="pull-left">
                                  <?php if (checkCapabilities('menu:readProfile',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                                      <a href="#" class="btn btn-default btn-flat" onclick="formloader('profile', 'edit');">Profil</a>
                                  <?php }?>
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
                    <?php }?>   
                </nav>         
            </header>         
            <!-- Sidebar left - Menu -->
            <?php if ($_smarty_tpl->getVariable('page_name')->value=='login'||$_smarty_tpl->getVariable('page_name')->value=='error'||$_smarty_tpl->getVariable('page_name')->value=='criteria'){?>
                <!--Kein Menu -->
            <?php }else{ ?>         
                <?php $_template = new Smarty_Internal_Template('menu.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '989779695575980d3e97038-37377164';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.6, created on 2016-06-09 16:44:36
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl" */ ?>
<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">Lernzeitraum</li>
            <?php if (isset($_smarty_tpl->getVariable('mySemester',null,true,false)->value)&&count($_smarty_tpl->getVariable('mySemester')->value)>1){?>
            <li class="treeview">
                <div class="dropdown"><i class="fa fa-calendar"></i>
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          <?php echo $_smarty_tpl->getVariable('my_semester')->value;?>

                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="dropdownMenu1" >
                            <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['res']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['name'] = 'res';
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('mySemester')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['res']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['res']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['res']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['res']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['res']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['res']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['res']['total']);
?>  
                                <li><a  onclick="setSemester(<?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id;?>
);"><?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->semester;?>
 (<?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->institution;?>
)</a></li>
                                <OPTION label="<?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->semester;?>
 (<?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->institution;?>
)" <?php if (isset($_smarty_tpl->getVariable('my_semester_id',null,true,false)->value)){?><?php if ($_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id==$_smarty_tpl->getVariable('my_semester_id')->value){?>selected<?php }?><?php }?> ></OPTION>
                            <?php endfor; endif; ?> 
                        </ul>
                </div>
            </li>
            <?php }?>
            
            
            <li class="header">Lehrpläne</li>
            <li class="treeview">
                <?php if ($_smarty_tpl->getVariable('my_enrolments')->value!=''){?>
                    <?php  $_smarty_tpl->tpl_vars['cur_menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('my_enrolments')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cur_menu']->key => $_smarty_tpl->tpl_vars['cur_menu']->value){
?>
                        <?php if ($_smarty_tpl->getVariable('cur_menu')->value->semester_id==$_smarty_tpl->getVariable('my_semester_id')->value){?>
                        <li <?php if (isset($_smarty_tpl->getVariable('page_curriculum',null,true,false)->value)){?><?php if (($_smarty_tpl->getVariable('page_curriculum')->value==$_smarty_tpl->getVariable('cur_menu')->value->id)&&($_smarty_tpl->getVariable('page_group')->value==$_smarty_tpl->getVariable('cur_menu')->value->group_id)){?> class="active"<?php }?><?php }?>>
                            <a href="index.php?action=view&curriculum_id=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->id;?>
&group=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->group_id;?>
">
                                <i class="fa fa-dashboard"></i><span><?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
</span><small class="label pull-right bg-green"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->groups;?>
</small>
                            </a>
                        </li>
                        <?php }?>
                    <?php }} ?>
                <?php }else{ ?><li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
                <?php }?>   
            
            
            <!-- Institution Menu -->
            <?php if (checkCapabilities('menu:readMyInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="header">Institution</li>
                <?php if (checkCapabilities('menu:readObjectives',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='objectives'){?>active<?php }?>">
                    <a href="index.php?action=objectives&reset=true">
                        <i class="fa fa-edit"></i> <span>Lernstand eingeben</span>
                    </a>
                </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readCourseBook',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='courseBook'){?>active<?php }?>">
                    <a href="index.php?action=coursebook&reset=true">
                        <i class="fa fa-book"></i> <span>Kursbuch</span>
                    </a>
                </li>
                <?php }?>

                <?php if (checkCapabilities('menu:readCurriculum',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='curriculum'){?>active<?php }?>">
                    <a href="index.php?action=curriculum&reset=true">
                        <i class="fa fa-th"></i> <span>Lehrpläne</span>
                    </a>
                </li>                  
                <?php }?>

                <?php if (checkCapabilities('menu:readGroup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='group'){?>active<?php }?>">
                        <a href="index.php?action=group&reset=true">
                            <i class="fa fa-group"></i><span>Lerngruppen</span>
                        </a>
                    </li>
                <?php }?>

                <?php if (checkCapabilities('menu:readUser',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='user'){?>active<?php }?>">
                        <a href="index.php?action=user&reset=true">
                            <i class="fa fa-user"></i><span>Benutzer</span>
                        </a>
                    </li>
                <?php }?>

                <?php if (checkCapabilities('menu:readRole',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='role'){?>active<?php }?>">
                        <a href="index.php?action=role&reset=true">
                            <i class="fa fa-key"></i><span>Rollenverwaltung</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readGrade',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='grade'){?>active<?php }?>">
                        <a href="index.php?action=grade">
                            <i class="fa fa-signal"></i><span>Klassenstufen</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readSubject',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='subject'){?>active<?php }?>">
                        <a href="index.php?action=subject&reset=true">
                            <i class="fa fa-language"></i><span>Fächer</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readSemester',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='semester'){?>active<?php }?>">
                        <a href="index.php?action=semester&reset=true">
                            <i class="fa fa-calendar"></i><span>Lernzeiträume</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readBackup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='backup'){?>active<?php }?>">
                        <a href="index.php?action=backup&reset=true">
                            <i class="fa fa-cloud-download"></i><span>Backup</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readCertificate',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='certificate'){?>active<?php }?>">
                        <a href="index.php?action=certificate&reset=true">
                            <i class="fa fa-files-o"></i><span>Zertifikate</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='institution'){?>active<?php }?>">
                        <a href="index.php?action=institution&reset=true">
                            <i class="fa fa-university"></i><span>Institutionen</span>
                        </a>
                    </li>
                <?php }?>
            <?php }?>
            
            <?php if (checkCapabilities('menu:readLog',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
            <li class="header">Administration</li>    
            
            <li <?php if ($_smarty_tpl->getVariable('page_action')->value=='log'){?>class="active"<?php }?>>
                <a href="index.php?action=log">
                    <i class="fa fa-list"></i><span>Berichte</span>
                </a>
            </li>
            <?php }?>
            
        </section>
        <!-- /.sidebar -->
      </aside>






<?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
                 
            <?php }?>
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div id="popup" class="modal" onload="popupFunction(this.id);"></div> <!-- Popup -->    
                
    <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            500 Error
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php?action=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">500 Error</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="error-page">
            <h2 class="headline text-red">500</h2>
            <div class="error-content"><br>
              <h3><i class="fa fa-warning text-red"></i> Interner Server Fehler.</h3>
              <p>
                Sollte der Fehler dauerhaft bestehen, melden Sie diesen bitte an einen Administrator.
                Hier gehts zurück auf die  <a href="index.php?action=dashboard">Startseite</a>.
              </p>
            </div>
          </div><!-- /.error-page -->

        </section><!-- /.content -->

            </div> 
            
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                  <b>Version</b> <?php echo $_smarty_tpl->getVariable('app_version')->value;?>

                </div>
                <?php echo $_smarty_tpl->getVariable('app_footer')->value;?>
  
            </footer>    
            
            <?php $_template = new Smarty_Internal_Template('sidebar_right.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '989779695575980d3e97038-37377164';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.6, created on 2016-06-09 16:44:36
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/sidebar_right.tpl" */ ?>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Create the tabs -->
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
    <!-- Home tab content -->
    <div class="tab-pane" id="control-sidebar-home-tab">
      <h3 class="control-sidebar-heading">Recent Activity</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="javascript::;">
            <i class="menu-icon fa fa-birthday-cake bg-red"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
              <p>Will be 23 on April 24th</p>
            </div>
          </a>
        </li>
        <li>
          <a href="javascript::;">
            <i class="menu-icon fa fa-user bg-yellow"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
              <p>New phone +1(800)555-1234</p>
            </div>
          </a>
        </li>
        <li>
          <a href="javascript::;">
            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
              <p>nora@example.com</p>
            </div>
          </a>
        </li>
        <li>
          <a href="javascript::;">
            <i class="menu-icon fa fa-file-code-o bg-green"></i>
            <div class="menu-info">
              <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
              <p>Execution time 5 seconds</p>
            </div>
          </a>
        </li>
      </ul><!-- /.control-sidebar-menu -->

      <h3 class="control-sidebar-heading">Tasks Progress</h3>
      <ul class="control-sidebar-menu">
        <li>
          <a href="javascript::;">
            <h4 class="control-sidebar-subheading">
              Custom Template Design
              <span class="label label-danger pull-right">70%</span>
            </h4>
            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
            </div>
          </a>
        </li>
        <li>
          <a href="javascript::;">
            <h4 class="control-sidebar-subheading">
              Update Resume
              <span class="label label-success pull-right">95%</span>
            </h4>
            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-success" style="width: 95%"></div>
            </div>
          </a>
        </li>
        <li>
          <a href="javascript::;">
            <h4 class="control-sidebar-subheading">
              Laravel Integration
              <span class="label label-warning pull-right">50%</span>
            </h4>
            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
            </div>
          </a>
        </li>
        <li>
          <a href="javascript::;">
            <h4 class="control-sidebar-subheading">
              Back End Framework
              <span class="label label-primary pull-right">68%</span>
            </h4>
            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
            </div>
          </a>
        </li>
      </ul><!-- /.control-sidebar-menu -->

    </div><!-- /.tab-pane -->

    <!-- Settings tab content -->
    <div class="tab-pane" id="control-sidebar-settings-tab">
      <form method="post">
        <h3 class="control-sidebar-heading">General Settings</h3>
        <div class="form-group">
          <label class="control-sidebar-subheading">
            Report panel usage
            <input type="checkbox" class="pull-right" checked>
          </label>
          <p>
            Some information about this general settings option
          </p>
        </div><!-- /.form-group -->

        <div class="form-group">
          <label class="control-sidebar-subheading">
            Allow mail redirect
            <input type="checkbox" class="pull-right" checked>
          </label>
          <p>
            Other sets of options are available
          </p>
        </div><!-- /.form-group -->

        <div class="form-group">
          <label class="control-sidebar-subheading">
            Expose author name in posts
            <input type="checkbox" class="pull-right" checked>
          </label>
          <p>
            Allow the user to show his name in blog posts
          </p>
        </div><!-- /.form-group -->

        <h3 class="control-sidebar-heading">Chat Settings</h3>

        <div class="form-group">
          <label class="control-sidebar-subheading">
            Show me as online
            <input type="checkbox" class="pull-right" checked>
          </label>
        </div><!-- /.form-group -->

        <div class="form-group">
          <label class="control-sidebar-subheading">
            Turn off notifications
            <input type="checkbox" class="pull-right">
          </label>
        </div><!-- /.form-group -->

        <div class="form-group">
          <label class="control-sidebar-subheading">
            Delete chat history
            <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
          </label>
        </div><!-- /.form-group -->
      </form>
    </div><!-- /.tab-pane -->
  </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div><?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "/Applications/MAMP/htdocs/curriculum/share/templates/sidebar_right.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
            
        </div><!-- ./wrapper -->
        
<!-- SCRIPTS-->  
    <!-- CK Editor -->
    <script src="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
ckeditor/ckeditor.js"></script>
    <!--script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script-->
    <!-- moment -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/moment/moment.min.js"></script>
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <!--script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script-->
    <!--script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script-->
    <!-- SlimScroll 1.3.0 -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <!--script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/chartjs/Chart.min.js"></script-->
    <!-- bootstrap color picker -->
    <!--script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/colorpicker/bootstrap-colorpicker.min.js"></script-->
    <!-- daterangepicker load dynamic via request -->
    <!--script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/plugins/daterangepicker/daterangepicker.js"></script-->
    
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/dist/js/pages/dashboard2.js"></script>-->
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
templates/AdminLTE-2.3.0/dist/js/demo.js"></script>
    
    <!-- jquery.nyroModal -->
    <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
jquery.nyroModal/js/jquery.nyroModal.custom.js"></script> 
    
    
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.js"></script> 
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/file.js"></script>
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/dragndrop.js"></script>     
         
        
        <!-- Logout - Timer  -->
        <?php if (isset($_smarty_tpl->getVariable('institution_timeout',null,true,false)->value)){?>
        <script type="text/javascript">
            idleMax = <?php echo $_smarty_tpl->getVariable('global_timeout')->value;?>
;        // Logout based on global timout value
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
        <?php }?>
        <script type="text/javascript">
        function closePopup(){
            removeMedia();  // Important to empty audio element cache in webkit browsers. see description on function
            $('#popup').hide();  
            document.getElementById('popup').innerHTML = '<img src="<?php echo $_smarty_tpl->getVariable('base_url')->value;?>
public/assets/images/loadingAnimation.gif"/>';    
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
        <?php if (isset($_SESSION['FORM']->form)){?>
            <script type="text/javascript" > 
                <?php if (isset($_SESSION['FORM']->id)){?>
                    $(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
', <?php echo $_SESSION['FORM']->id;?>
));
                <?php }else{ ?>
                    $(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
'));
                <?php }?>
            </script>
        <?php }?> 
        
          
    </body>
    <?php }?>
</html>