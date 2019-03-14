<?php /* Smarty version Smarty-3.0.6, created on 2019-01-24 13:38:02
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5763809115c472510286261-11680486%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dab66ea71508e2f7c8926ba32dc749940e40d697' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/login.tpl',
      1 => 1548166412,
      2 => 'file',
    ),
    '99499f381748bc4247ef4617bbcf016f54544b83' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/base.tpl',
      1 => 1548333477,
      2 => 'file',
    ),
    '9479a9a2147eb7ba7e10cb87dea8ad03f0a63f73' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/menu.tpl',
      1 => 1548331876,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5763809115c472510286261-11680486',
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
<?php if (!is_callable('smarty_modifier_resolve_file_id')) include '/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/plugins/modifier.resolve_file_id.php';
?><!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="de" class="no-js"> <!--<![endif]--><?php if (!function_exists('smarty_template_function_validate_msg')) {
    function smarty_template_function_validate_msg($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->template_functions['validate_msg']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable(trim($value,'\''));};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php if (isset($_smarty_tpl->getVariable('v_error',null,true,false)->value[$_smarty_tpl->getVariable('field',null,true,false)->value])){?><?php  $_smarty_tpl->tpl_vars['v_field'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['err'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('v_error')->value[$_smarty_tpl->getVariable('field')->value]['message']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['v_field']->key => $_smarty_tpl->tpl_vars['v_field']->value){
 $_smarty_tpl->tpl_vars['err']->value = $_smarty_tpl->tpl_vars['v_field']->key;
?><p><label></label><?php echo $_smarty_tpl->tpl_vars['v_field']->value;?>
</p><?php }} ?><?php }?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;}}?>
<head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title><?php echo $_smarty_tpl->getVariable('page_title')->value;?>
 | <?php echo strip_tags($_smarty_tpl->getVariable('app_title')->value);?>
</title><meta name="description" content=""><meta name="author" content="Joachim Dieterich (www.curriculumonline.de)"><link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-57x57.png" /><link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-114x114.png" /><link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-72x72.png" /><link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-144x144.png" /><link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-120x120.png" /><link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/apple-touch-icon-152x152.png" /><link rel="icon" type="image/png" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/favicon-32x32.png" sizes="32x32" /><link rel="icon" type="image/png" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/favicon-16x16.png" sizes="16x16" /><meta name="msapplication-TileColor" content="#FFFFFF" /><meta name="msapplication-TileImage" content="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
images/favicon/mstile-144x144.png" /><!-- AdminLTE --><!-- Tell the browser to be responsive to screen width --><meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"><!-- Bootstrap 3.3.5 --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
bootstrap/css/bootstrap.min.css"><!-- Font Awesome --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
/font-awesome/css/font-awesome.min.css"><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/google-fonts.min.css" ><!-- daterangepicker --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/daterangepicker/daterangepicker.min.css"><!-- Theme style --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/less/AdminLTE.min.css"><!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. --><!--link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/less/skins/_all-skins.min.css"--><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/skins/skin-blue.min.css"><!-- Pace style --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/pace/pace.min.css"><!-- Bootstrap Color Picker --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/colorpicker/bootstrap-colorpicker.min.css"><!-- Custom styles for this template --><!-- <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/all-bs.min.css">--><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/buttons.min.css"><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
jquery.nyroModal/styles/nyroModal.min.css" media="all"></head><?php if (in_array($_smarty_tpl->getVariable('page_action')->value,array('login','lock','extern'))){?><body class="hold-transition <?php if ($_smarty_tpl->getVariable('page_action')->value=='login'||$_smarty_tpl->getVariable('page_action')->value=='extern'){?>login-page<?php }?> <?php if ($_smarty_tpl->getVariable('page_action')->value=='lock'){?>lockscreen<?php }?>" <?php if ($_smarty_tpl->getVariable('cfg_login_wallpaper')->value){?>style="background-image: url('<?php echo $_smarty_tpl->getVariable('random_bg')->value;?>
'); background-size: cover;"<?php }?> ><div id="popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div></div> <!-- Popup -->
<div class="login-box">
    
      <div class="login-box-body">
          <div class="login-logo">
          <img alt="curriculum-logo" src="assets/images/favicon/apple-touch-icon-57x57.png"/> <br>
        <b><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</b>
      </div><!-- /.login-logo -->
          <p id="reset_info" class="login-box-msg hidden">Bitte geben Sie Ihren Benutzername ein und klicken auf "Passwort zurücksetzen".<br>Über Ihren Administrator bekommen Sie dann die neue Zugangsdaten. </p>
        <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>
            <strong><?php echo FORM::info('error','',$_smarty_tpl->getVariable('page_message')->value[0]['message'],'','col-sm-12 text-red');?>
</strong>
        <?php }?>
        <form id="form_login" action="<?php echo $_smarty_tpl->getVariable('base_url')->value;?>
public/index.php?action=login" method="post">
          <div class="form-group has-feedback <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>has-error<?php }?>">
            <input type="text" class="form-control" id="username" name="username" <?php if (isset($_smarty_tpl->getVariable('username',null,true,false)->value)){?>value="<?php echo $_smarty_tpl->getVariable('username')->value;?>
"<?php }?> placeholder="Benutzername">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div id="password" class="form-group has-feedback <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>has-error<?php }?>">
            <input type="password" class="form-control" name="password" placeholder="Passwort">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6 pull-left">               
                <input id="pw_reset" class="btn btn-primary btn-block btn-flat visible" onclick="toggle(['reset', 'reset_info'], ['login', 'password', 'pw_reset']);" value="Passwort vergessen" />
            </div><!-- /.col -->
            <div class="col-xs-6 pull-right">
                <input id="login" type="submit" name="login" class="btn btn-primary btn-block btn-flat visible" value="Einloggen" />
                <input id="reset" type="submit" name="reset" class="btn btn-primary btn-block btn-flat hidden" value="Passwort vergessen" />
            </div><!-- /.col -->
          </div>
            <?php if ($_smarty_tpl->getVariable('cfg_guest_login')->value=='1'||$_smarty_tpl->getVariable('cfg_shibboleth')->value=='1'){?>      
                <p class="text-center top-buffer"><strong>- ODER -</strong></p>  
            <?php }?>
            <?php if ($_smarty_tpl->getVariable('cfg_guest_login')->value){?>
                <div class="row">
                    <div class="col-xs-6 pull-left">
                        <input id="register"  onclick="alert('Funktion noch nicht verfügbar');"  name="register" class="btn btn-primary btn-block btn-flat visible" value="Registrierung" data-toggle="tooltip" title="Noch nicht registriert?"/>
                    </div><!-- /.col -->
                    <div class="col-xs-6 pull-right">
                        <input id="guest" type="submit" name="guest" class="btn btn-primary btn-block btn-flat visible" value="Gastzugang" data-toggle="tooltip" title="Als Gast einen Einblick bekommen"/>
                    </div><!-- /.col -->
                </div>
            <?php }?>    
            <?php if ($_smarty_tpl->getVariable('cfg_shibboleth')->value){?>
            <div class="social-auth-links text-center">
              <a href="../share/plugins/auth/shibboleth/index.php" class="btn btn-block btn-social btn-openid"><img src="assets/images/icons/shibboleth-web.png"/> Über Shibboleth anmelden</a>
            </div>
            <?php }?>
            <br><small><a onclick="formloader('terms', 'imprint')">Impressum</a> / <a onclick="formloader('terms', 'privacy')">Datenschutz</a></small>
        </form>
      </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php }else{ ?><body class="hold-transition <?php echo $_smarty_tpl->getVariable('page_layout')->value;?>
 skin-blue" data-spy="scroll" data-target=".modal-body" style=" -webkit-overflow-scrolling:touch; overflow:auto;" ><div id="body-wrapper" class="<?php echo $_smarty_tpl->getVariable('page_body_wrapper')->value;?>
"><?php if ($_smarty_tpl->getVariable('page_header')->value){?><header class="main-header"><!-- Logo --><a href="index.php?action=dashboard" class="logo"><!-- mini logo for sidebar mini 50x50 pixels --><span class="logo-mini"><img class="pull-left" style="margin-top: 5px; margin-left: 2px;" src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/logo.png"  data-toggle="tooltip" data-placement="bottom" title="Startseite" /></span><!-- logo for regular state and mobile devices --><span class="logo-lg"><img class="pull-left" style="margin-top: 5px;" src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/logo.png" data-toggle="tooltip" data-placement="bottom" title="Startseite" /><b><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</b></span></a><!-- Header Navbar: style can be found in header.less --><nav class="navbar navbar-static-top" role="navigation" <?php if ((isset($_smarty_tpl->getVariable('page_bg_file_id',null,true,false)->value)&&$_smarty_tpl->getVariable('cfg_show_subjectIcon')->value!="NEVER")){?>style="background: url('<?php echo $_smarty_tpl->getVariable('access_file_id')->value;?>
<?php echo $_smarty_tpl->getVariable('page_bg_file_id')->value;?>
') center center;  background-size: cover;"<?php }?>><!-- isset($page_bg_file_id) AND  --><?php if (isset($_smarty_tpl->getVariable('my_id',null,true,false)->value)){?><!-- Sidebar toggle button--><a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><span class="sr-only">Navigation wechseln</span></a><!-- Navbar Right Menu --><div class="navbar-custom-menu"><ul class="nav navbar-nav"><?php if (checkCapabilities('dashboard:globalAdmin',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li><a href="index.php?action=statistic" style="padding: 15px 8px 15px 8px;" data-toggle="tooltip" data-placement="bottom" title="Statistik"><i class="fa fa-pie-chart"></i></a></li><?php }?><li><a href="index.php?action=help" style="padding: 15px 8px 15px 8px;" data-toggle="tooltip" data-placement="bottom" title="Hilfe"><i class="fa fa-graduation-cap"></i></a></li><?php if (checkCapabilities('menu:readTasks',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li><a href="index.php?action=task" style="padding: 15px 8px 15px 8px;" data-toggle="tooltip" data-placement="bottom" title="Aufgaben"><i class="fa fa-tasks"></i></a></li><?php }?><li class="calendar-menu"><a href="index.php?action=calendar" style="padding: 15px 8px 15px 8px;" data-toggle="tooltip" data-placement="bottom" title="Kalender"><i class="fa fa-calendar"></i></a></li><?php if (checkCapabilities('menu:readTimeline',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="timeline-menu"><a href="index.php?action=portfolio" style="padding: 15px 8px 15px 8px;" data-toggle="tooltip" data-placement="bottom" title="Timeline"><i class="fa fa-cubes"></i></a></li><?php }?><?php if (checkCapabilities('menu:readMessages',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><?php if (isset($_smarty_tpl->getVariable('mails',null,true,false)->value)){?><!-- Messages: style can be found in dropdown.less--><li class="dropdown messages-menu" data-toggle="tooltip" data-placement="bottom" title="Nachrichten"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 8px 15px 8px;" title=""><i class="fa fa-envelope-o"></i><span class="label label-success"><?php echo count($_smarty_tpl->getVariable('mails')->value);?>
</span></a><ul class="dropdown-menu"><li class="header">Sie haben <?php echo count($_smarty_tpl->getVariable('mails')->value);?>
 neue Nachrichten</li><li><!-- inner menu: contains the actual data --><ul class="menu"><?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']);
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
?><li><!-- start message --><a href="index.php?action=messages&function=showInbox&id=<?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->id;?>
"><div class="pull-left"><img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo smarty_modifier_resolve_file_id($_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->sender_file_id,"xs");?>
" class="img-circle" alt="User Image"></div><h4><?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->sender_username;?>
<small><i class="fa fa-calendar-times-o"></i> <?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->creation_time;?>
</small></h4><p><?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->subject;?>
</p></a></li><?php endfor; endif; ?><!-- end message --></ul></li><li class="footer"><a href="index.php?action=messages&function=showInbox">Alle Nachrichten</a></li></ul></li><?php }else{ ?><li class=" messages-menu"><a href="index.php?action=messages&function=showInbox" style="padding: 15px 8px 15px 8px;" data-toggle="tooltip" data-placement="bottom" title="Nachrichten"><i class="fa fa-envelope-o"></i></a></li><?php }?><?php }?><?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?><!-- Notifications: style can be found in dropdown.less --><li class="dropdown notifications-menu open"><a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 8px 15px 8px;" title="Benachrichtigungen"><i class="fa fa-bell-o"></i><span class="label label-warning"><?php echo count($_smarty_tpl->getVariable('page_message')->value);?>
</span></a><ul class="dropdown-menu"><li class="header">Sie haben <?php echo count($_smarty_tpl->getVariable('page_message')->value);?>
 Hinweise</li><li><ul class="menu"><!-- inner menu: contains the actual data --><?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']);
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
?><li><a href="#" style="white-space: normal"><?php if (is_array($_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']])){?><i class="fa <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value[$_smarty_tpl->getVariable('smarty',null,true,false)->value['section']['mes']['index']]['icon'])){?><?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]['icon'];?>
<?php }else{ ?>fa-warning text-yellow<?php }?>"></i> <?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]['message'];?>
<?php }else{ ?><i class="fa fa-warning text-yellow"></i> <?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']];?>
<?php }?></a></li><?php endfor; endif; ?></ul><li class="footer"><a href="#"> <!--Alle zeigen--></a></li></ul></li><?php }?><!-- User Account: style can be found in dropdown.less --><li class="dropdown user user-menu"  data-toggle="tooltip" data-placement="bottom" title="Benutzer verwalten"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="" style="padding: 15px 8px 15px 8px;"><img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
" class="user-image" alt="User Image"><span class="hidden-xs"><?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
</span></a><ul class="dropdown-menu"><!-- User image --><li class="user-header"><img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
" class="img-circle" alt="User Image"><p><?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
 - <?php echo $_smarty_tpl->getVariable('my_role_name')->value;?>
</p></li><!-- Menu Body --><!--li class="user-body"></li--><!-- Menu Footer--><li class="user-footer"><div class="pull-left"><?php if (checkCapabilities('user:resetPassword',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><a href="#" class="btn btn-default btn-flat pull-left" onclick="formloader('password', 'edit');" data-toggle="tooltip" title="Passwort ändern"><i class="fa fa-user-secret"></i></a><?php }?><?php if (checkCapabilities('user:update',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><a href="#" class="btn btn-default btn-flat  pull-left" onclick="formloader('profile', 'edit');" data-toggle="tooltip" title="Profil bearbeiten"><i class="fa fa-user"></i></a><?php }?><?php if (checkCapabilities('menu:readFiles',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><a href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
renderer/uploadframe.php?context=userFiles<?php echo $_smarty_tpl->getVariable('tb_param')->value;?>
" data-toggle="tooltip" title="Meine Dateien" class="btn btn-default btn-flat  nyroModal"><i class="fa fa-folder-open"></i></a><?php }?></div><div class="pull-right"><a href="index.php?action=logout" data-toggle="tooltip" title="Abmelden" class="btn btn-default btn-flat pull-right">Abmelden</a><a href="index.php?action=lock" data-toggle="tooltip" title="Fenster sperren" class="btn btn-default btn-flat pull-right"><i class="fa fa-lock"></i></a></div></li></ul></li><?php if (checkCapabilities('template:change',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="dropdown" data-toggle="tooltip" data-placement="bottom" title="Einstellungen"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title=""><i class="fa fa-gears"></i></a><ul class="dropdown-menu" role="menu"><li><a href="#" onclick="formloader('settings', 'edit');">Einstellungen</a></li><li class="divider"></li><li><a href="index.php?action=navigator">Navigator (Test)</a></li><li><a href="index.php?action=debug">Debug / Userfeedback</a></li><li><a href="index.php?action=update">Updates</a></li></ul></li><?php }?></ul></div><?php }?></nav></header><?php }?><!-- Sidebar left - Menu --><?php ob_start();?><?php echo $_smarty_tpl->getVariable('page_layout')->value;?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1!='layout-top-nav'){?> <!--Kein Menu --><?php $_template = new Smarty_Internal_Template('menu.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '5763809115c472510286261-11680486';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.6, created on 2019-01-24 13:38:02
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/menu.tpl" */ ?>
<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <div id="menu_top_placeholder"></div>
          <ul class="sidebar-menu">
            <?php if (isset($_smarty_tpl->getVariable('myChildren',null,true,false)->value)){?>
                <li class="header bg-light-blue">Meine Kinder</li>
                <?php echo Form::input_select('my_children','',$_smarty_tpl->getVariable('myChildren')->value,'firstname, lastname','id',$_smarty_tpl->getVariable('my_child_id')->value,'',"window.location.assign('index.php?action=children&reset=true&child_id='+this.value);",'Bitte auswählen...','col-xs-0','col-xs-12');?>

            <?php }?>  
            <li class="header bg-light-blue"><?php echo $_smarty_tpl->getVariable('lang')->value['SYS_CURRICULA'];?>
</li>
            <?php if ($_smarty_tpl->getVariable('my_enrolments')->value!=''){?>
                <?php if (($_smarty_tpl->getVariable('cfg_guest_usr')->value==$_smarty_tpl->getVariable('my_username')->value)||($_smarty_tpl->getVariable('my_role_name')->value=='Indexer')||count($_smarty_tpl->getVariable('my_enrolments')->value)>10){?>
                    <select id="guest_menu" name="guest_menu" class="select2 form-control" onchange="location = this.value;">
                        <option value="false">Bitte Lehrplan wählen...</option>
                        <?php  $_smarty_tpl->tpl_vars['cur_menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('my_enrolments')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cur_menu']->key => $_smarty_tpl->tpl_vars['cur_menu']->value){
?>
                            <?php if ($_smarty_tpl->getVariable('cur_menu')->value->semester_id==$_smarty_tpl->getVariable('my_semester_id')->value){?>
                                <option label="<?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
" value="index.php?action=view&curriculum_id=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->id;?>
&group=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->group_id;?>
" <?php if (isset($_smarty_tpl->getVariable('page_curriculum',null,true,false)->value)){?><?php if (($_smarty_tpl->getVariable('page_curriculum')->value==$_smarty_tpl->getVariable('cur_menu')->value->id)&&($_smarty_tpl->getVariable('page_group')->value==$_smarty_tpl->getVariable('cur_menu')->value->group_id)){?> selected="selected"<?php }?><?php }?>><?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
</option>
                            <?php }?>
                        <?php }} ?>
                    </select>

                    <?php }else{ ?>
                        <?php $_smarty_tpl->tpl_vars['menu_index'] = new Smarty_variable(0, null, null);?>
                        <?php  $_smarty_tpl->tpl_vars['cur_menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('my_enrolments')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cur_menu']->key => $_smarty_tpl->tpl_vars['cur_menu']->value){
?>
                            <?php if ($_smarty_tpl->getVariable('cur_menu')->value->semester_id==$_smarty_tpl->getVariable('my_semester_id')->value){?>
                                    <?php $_smarty_tpl->tpl_vars['menu_index'] = new Smarty_variable($_smarty_tpl->getVariable('menu_index')->value+1, null, null);?>
                                <?php ob_start();?><?php echo $_smarty_tpl->getVariable('menu_index')->value;?>
<?php $_tmp1=ob_get_clean();?><?php if ($_tmp1!=5){?> 
                                    <li <?php if (isset($_smarty_tpl->getVariable('page_curriculum',null,true,false)->value)){?><?php if (($_smarty_tpl->getVariable('page_curriculum')->value==$_smarty_tpl->getVariable('cur_menu')->value->id)&&($_smarty_tpl->getVariable('page_group')->value==$_smarty_tpl->getVariable('cur_menu')->value->group_id)){?> class="active treeview"<?php }?><?php }?>>                                
                                        <a class="text-ellipse" href="index.php?action=view&curriculum_id=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->id;?>
&group=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->group_id;?>
" >
                                            <?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
<span class="label pull-right bg-green"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->groups;?>
</span>
                                        </a>

                                        <div class="progress xxs margin-bottom-none">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo $_smarty_tpl->getVariable('cur_menu')->value->completed;?>
%" role="progressbar" aria-valuenow="<?php echo $_smarty_tpl->getVariable('cur_menu')->value->completed;?>
" aria-valuemin="0" aria-valuemax="100">
                                              <span class="sr-only"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->completed;?>
% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }else{ ?>
                                    <li class=" treeview"><a><span>Weitere Einträge</span><i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu" style="display: none;">
                                        <?php $_smarty_tpl->tpl_vars["submenu"] = new Smarty_variable(true, null, null);?> 
                                        <li <?php if (isset($_smarty_tpl->getVariable('page_curriculum',null,true,false)->value)){?><?php if (($_smarty_tpl->getVariable('page_curriculum')->value==$_smarty_tpl->getVariable('cur_menu')->value->id)&&($_smarty_tpl->getVariable('page_group')->value==$_smarty_tpl->getVariable('cur_menu')->value->group_id)){?> class="active treeview"<?php }?><?php }?>>                                
                                        <a class="text-ellipse" href="index.php?action=view&curriculum_id=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->id;?>
&group=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->group_id;?>
" >
                                            <?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
<span class="label pull-right bg-green"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->groups;?>
</span>
                                        </a>

                                        <div class="progress xxs margin-bottom-none">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo $_smarty_tpl->getVariable('cur_menu')->value->completed;?>
%" role="progressbar" aria-valuenow="<?php echo $_smarty_tpl->getVariable('cur_menu')->value->completed;?>
" aria-valuemin="0" aria-valuemax="100">
                                              <span class="sr-only"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->completed;?>
% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }?>  
                                
                            <?php }?>
                        <?php }} ?>
                    <?php }?>
                <?php if (isset($_smarty_tpl->getVariable('submenu',null,true,false)->value)){?>
                    <?php if ($_smarty_tpl->getVariable('submenu')->value==true){?>
                        </li></ul>
                    <?php }?>
                <?php }?>
            <?php }else{ ?><li><a href="">
                                <i class="fa fa-dashboard"></i><span>Sie sind in keinen Lehrplan <br>eingeschrieben</span>
                      </a></li>
            <?php }?>   
            
            <!-- Institution Menu -->
            <?php if (checkCapabilities('menu:readMyInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="header bg-light-blue">Institution</li>
                <?php if (isset($_smarty_tpl->getVariable('mySemester',null,true,false)->value)&&count($_smarty_tpl->getVariable('mySemester')->value)>1){?>
                    <?php echo Form::input_select('semester_id','',$_smarty_tpl->getVariable('mySemester')->value,'semester, institution','id',$_smarty_tpl->getVariable('my_semester_id')->value,null,"processor('semester','set',this.getAttribute('data-id'));",'---','','');?>
                  
                <?php }elseif(isset($_smarty_tpl->getVariable('my_institutions',null,true,false)->value)&&count($_smarty_tpl->getVariable('my_institutions')->value)>1){?>
                    <?php echo Form::input_select('institution_id','',$_smarty_tpl->getVariable('my_institutions')->value,'institution','institution_id',$_smarty_tpl->getVariable('my_institution_id')->value,null,"processor('config','institution_id',this.getAttribute('data-id'));",'---','','');?>
                  
                <?php }?> 
                
                <?php if (checkCapabilities('menu:readObjectives',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='objectives'){?>active<?php }?>">
                    <a href="index.php?action=objectives&reset=true">
                        <i class="fa fa-edit"></i> <span>Lernstand eingeben</span>
                    </a>
                </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readCourseBook',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='courseBook'){?>active<?php }?>">
                    <a href="index.php?action=courseBook">
                        <i class="fa fa-book"></i> <span>Kursbuch</span>
                    </a>
                </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readWallet',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='wallet'){?>active<?php }?>">
                    <a href="index.php?action=wallet">
                        <i class="fa fa-newspaper-o"></i> <span>Sammelmappe</span>
                    </a>
                </li>
                <?php }?>

                <?php if (checkCapabilities('menu:readCurriculum',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='curriculum'){?>active<?php }?>">
                    <a href="index.php?action=curriculum">
                        <i class="fa fa-th"></i> <span>Lehrpläne</span>
                    </a>
                </li>                  
                <?php }?>

                <?php if (checkCapabilities('menu:readGroup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='group'){?>active<?php }?>">
                        <a href="index.php?action=group">
                            <i class="fa fa-group"></i><span>Lerngruppen</span>
                        </a>
                    </li>
                <?php }?>

                <?php if (checkCapabilities('menu:readUser',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='user'){?>active<?php }?>">
                        <a href="index.php?action=user">
                            <i class="fa fa-user"></i><span>Benutzerverwaltung</span>
                        </a>
                    </li>
                <?php }?>

                <?php if (checkCapabilities('menu:readRole',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='role'){?>active<?php }?>">
                        <a href="index.php?action=role">
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
                        <a href="index.php?action=subject">
                            <i class="fa fa-language"></i><span>Fächer</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readSemester',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='semester'){?>active<?php }?>">
                        <a href="index.php?action=semester">
                            <i class="fa fa-history"></i><span>Lernzeiträume</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readBackup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='backup'){?>active<?php }?>">
                        <a href="index.php?action=backup">
                            <i class="fa fa-cloud-download"></i><span>Backup</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readCertificate',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='certificate'){?>active<?php }?>">
                        <a href="index.php?action=certificate">
                            <i class="fa fa-certificate"></i><span>Zertifikate</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='institution'){?>active<?php }?>">
                        <a href="index.php?action=institution">
                            <i class="fa fa-university"></i><span>Institutionen</span>
                        </a>
                    </li>
                <?php }?>
                <?php if (checkCapabilities('menu:readSchooltype',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                    <li class="treeview <?php if ($_smarty_tpl->getVariable('page_action')->value=='schooltype'){?>active<?php }?>">
                        <a href="index.php?action=schooltype">
                            <i class="fa fa-list-alt"></i><span>Schul-/Institutionstypen</span>
                        </a>
                    </li>
                <?php }?>
            <?php }?>
            
            <?php if (checkCapabilities('menu:readLog',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
            <li class="header bg-light-blue">Administration</li>    
            
            <li <?php if ($_smarty_tpl->getVariable('page_action')->value=='log'){?>class="active"<?php }?>>
                <a href="index.php?action=log">
                    <i class="fa fa-list"></i><span>Berichte</span>
                </a>
            </li>
            <?php }?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside><?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "/Applications/MAMP/htdocs/curriculum/share/templates/AdminLTE-2.3.7/menu.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?><?php }?><!-- Content Wrapper. Contains page content --><div id="content-wrapper" class="content-wrapper"><div id="popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="box"><div class="box-header"><h3 class="box-title">Loading...</h3></div><div class="box-body"></div><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div></div></div> <!-- Popup -->
<div class="login-box">
    
      <div class="login-box-body">
          <div class="login-logo">
          <img alt="curriculum-logo" src="assets/images/favicon/apple-touch-icon-57x57.png"/> <br>
        <b><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</b>
      </div><!-- /.login-logo -->
          <p id="reset_info" class="login-box-msg hidden">Bitte geben Sie Ihren Benutzername ein und klicken auf "Passwort zurücksetzen".<br>Über Ihren Administrator bekommen Sie dann die neue Zugangsdaten. </p>
        <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>
            <strong><?php echo FORM::info('error','',$_smarty_tpl->getVariable('page_message')->value[0]['message'],'','col-sm-12 text-red');?>
</strong>
        <?php }?>
        <form id="form_login" action="<?php echo $_smarty_tpl->getVariable('base_url')->value;?>
public/index.php?action=login" method="post">
          <div class="form-group has-feedback <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>has-error<?php }?>">
            <input type="text" class="form-control" id="username" name="username" <?php if (isset($_smarty_tpl->getVariable('username',null,true,false)->value)){?>value="<?php echo $_smarty_tpl->getVariable('username')->value;?>
"<?php }?> placeholder="Benutzername">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div id="password" class="form-group has-feedback <?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?>has-error<?php }?>">
            <input type="password" class="form-control" name="password" placeholder="Passwort">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-6 pull-left">               
                <input id="pw_reset" class="btn btn-primary btn-block btn-flat visible" onclick="toggle(['reset', 'reset_info'], ['login', 'password', 'pw_reset']);" value="Passwort vergessen" />
            </div><!-- /.col -->
            <div class="col-xs-6 pull-right">
                <input id="login" type="submit" name="login" class="btn btn-primary btn-block btn-flat visible" value="Einloggen" />
                <input id="reset" type="submit" name="reset" class="btn btn-primary btn-block btn-flat hidden" value="Passwort vergessen" />
            </div><!-- /.col -->
          </div>
            <?php if ($_smarty_tpl->getVariable('cfg_guest_login')->value=='1'||$_smarty_tpl->getVariable('cfg_shibboleth')->value=='1'){?>      
                <p class="text-center top-buffer"><strong>- ODER -</strong></p>  
            <?php }?>
            <?php if ($_smarty_tpl->getVariable('cfg_guest_login')->value){?>
                <div class="row">
                    <div class="col-xs-6 pull-left">
                        <input id="register"  onclick="alert('Funktion noch nicht verfügbar');"  name="register" class="btn btn-primary btn-block btn-flat visible" value="Registrierung" data-toggle="tooltip" title="Noch nicht registriert?"/>
                    </div><!-- /.col -->
                    <div class="col-xs-6 pull-right">
                        <input id="guest" type="submit" name="guest" class="btn btn-primary btn-block btn-flat visible" value="Gastzugang" data-toggle="tooltip" title="Als Gast einen Einblick bekommen"/>
                    </div><!-- /.col -->
                </div>
            <?php }?>    
            <?php if ($_smarty_tpl->getVariable('cfg_shibboleth')->value){?>
            <div class="social-auth-links text-center">
              <a href="../share/plugins/auth/shibboleth/index.php" class="btn btn-block btn-social btn-openid"><img src="assets/images/icons/shibboleth-web.png"/> Über Shibboleth anmelden</a>
            </div>
            <?php }?>
            <br><small><a onclick="formloader('terms', 'imprint')">Impressum</a> / <a onclick="formloader('terms', 'privacy')">Datenschutz</a></small>
        </form>
      </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
</div><footer class="main-footer"><div class="pull-right hidden-xs"><b>Version</b> <?php echo $_smarty_tpl->getVariable('app_version')->value;?>
</div><a class="btn-xs margin-r-10 pull-right" onclick='formloader("content", "new", null,<?php echo json_encode(array("label_title"=>"Betreff","label_content"=>"Fehler beschreiben","label_header"=>"Fehler melden","label_save"=>"Meldung abschicken","context"=>"debug","show_file_context"=>"false","reference_id"=>0));?>
);'><i class="fa fa-bullhorn text-warning"></i> Fehler melden</a><?php echo $_smarty_tpl->getVariable('app_footer')->value;?>
   <small><a onclick="formloader('terms', 'imprint')">Impressum</a> / <a onclick="formloader('terms', 'privacy')">Datenschutz</a></small></footer></div><!-- ./wrapper --><?php }?><!-- SCRIPTS--><script src="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
ckeditor/ckeditor.js"></script><!-- CK Editor --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/moment/moment.min.js"></script><!-- moment --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/jquery-2.2.1.min.js"></script> <!-- jQuery 2.2.1 --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/alterClass.min.js"></script> <!-- alter class --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
bootstrap/js/bootstrap.min.js"></script><!-- Bootstrap 3.3.5 --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
js/app.min.js"></script><!-- AdminLTE App --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/slimScroll/jquery.slimscroll.min.js"></script><!-- SlimScroll 1.3.0 --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/pace/pace.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/mark/jquery.mark.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/curriculum.min.js"></script><!-- curriculum settings (sidebar) --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
jquery.nyroModal/js/jquery.nyroModal.custom.min.js"></script> <!-- jquery.nyroModal --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/PDFObject-master/pdfobject.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/file.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/dragndrop.min.js"></script><!-- Select2 --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/select2/select2.min.css"><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/select2/select2.min.js"></script><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/less/select2.min.css"><!-- MathJax -->
        <script type="text/x-mathjax-config">
            MathJax.Hub.Config({
              extensions: ["tex2jax.js"],
              jax: ["input/TeX","output/HTML-CSS"],
              tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
            });
        </script>
        <!-- popup drag -->
    <script>
        var elem = document.getElementById('popup');
        elem.addEventListener('mousemove', drag);
        elem.addEventListener('mouseup', dragstop);	

       //Das Objekt, das gerade bewegt wird.
        var dragobjekt = null;

        // Position, an der das Objekt angeklickt wurde.
        var dragx = 0;
        var dragy = 0;

        // Mausposition
        var posx = 0;
        var posy = 0;


    function dragstart(element) {
       //Wird aufgerufen, wenn ein Objekt bewegt werden soll.
        dragobjekt = document.getElementById('modal');
        dragx = posx - dragobjekt.offsetLeft;
        dragy = posy - dragobjekt.offsetTop;
    }


    function dragstop() {
      //Wird aufgerufen, wenn ein Objekt nicht mehr bewegt werden soll.
      dragobjekt=null;
    }


    function drag(ereignis) {
      //Wird aufgerufen, wenn die Maus bewegt wird und bewegt bei Bedarf das Objekt.

      posx = document.all ? window.event.clientX : ereignis.pageX;
      posy = document.all ? window.event.clientY : ereignis.pageY;
      if(dragobjekt != null) {
        dragobjekt.style.left = (posx - dragx) + "px";
        dragobjekt.style.top = (posy - dragy) + "px";
      }
    }

    </script>
    <!-- popup drag -->
    <script src="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
MathJax-master/MathJax.js"></script><!-- MathJax--><!-- Logout - Timer  --><?php if (isset($_smarty_tpl->getVariable('institution_timeout',null,true,false)->value)){?><script type="text/javascript">idleTime = 0;$(document).ready(function () {InitScripts();/*Increment the idle time counter every minute.*/var idleInterval = setInterval(timerIncrement, 60000); /*1 minute*//*Zero the idle timer on mouse movement.*/$(this).mousemove(function (e) { idleTime = 0; });$(this).keypress(function (e) { idleTime = 0; });$(document.getElementById('popup')).attr('class', 'modal');$(".select2").select2();});function timerIncrement() {idleTime++;if (idleTime === <?php echo $_smarty_tpl->getVariable('global_timeout')->value;?>
) {window.location="index.php?action=logout&timout=true";}}</script><?php }?><!-- end Logout - Timer  --><?php if (isset($_SESSION['FORM']->form)){?><script type="text/javascript"><?php if (isset($_SESSION['FORM']->id)){?><?php if ($_SESSION['FORM']->id!=''){?>$(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
', <?php echo $_SESSION['FORM']->id;?>
));<?php }?>$(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
'));<?php }else{ ?>$(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
'));<?php }?></script><?php }?>
    <script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
bootstrap/js/bootstrap.min.js"></script><!-- Bootstrap 3.3.5 -->
</body></html>