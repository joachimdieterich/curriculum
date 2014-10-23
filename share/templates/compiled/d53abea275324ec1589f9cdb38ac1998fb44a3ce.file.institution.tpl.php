<?php /* Smarty version Smarty-3.0.6, created on 2014-10-23 13:30:00
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/institution.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12771548315448e6b8ede567-58826055%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd53abea275324ec1589f9cdb38ac1998fb44a3ce' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/institution.tpl',
      1 => 1413814402,
      2 => 'file',
    ),
    'e9d941a08708e3e9080a36e68e883c7c15367c0d' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/base.tpl',
      1 => 1413881667,
      2 => 'file',
    ),
    '8c7bf3ef5fb3e1db5785d70d14743e45c0444d09' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl',
      1 => 1413818504,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12771548315448e6b8ede567-58826055',
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
<?php if (!is_callable('smarty_function_paginate_prev')) include '/Applications/MAMP/htdocs/curriculum/share/libs/Smarty-3.0.6/libs/plugins/function.paginate_prev.php';
if (!is_callable('smarty_function_paginate_middle')) include '/Applications/MAMP/htdocs/curriculum/share/libs/Smarty-3.0.6/libs/plugins/function.paginate_middle.php';
if (!is_callable('smarty_function_paginate_next')) include '/Applications/MAMP/htdocs/curriculum/share/libs/Smarty-3.0.6/libs/plugins/function.paginate_next.php';
?><!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="de" class="no-js"> <!--<![endif]-->
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
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/all.css?1" media="all">
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
stylesheets/date.css" media="all">

        <title><?php echo $_smarty_tpl->getVariable('page_title')->value;?>
 | <?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</title>
        <meta name="description" content="Beschreibung">
        <meta name="author" content="">
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/modernizr/modernizr-1.6.min.js"></script>
    </head>

    <body>
        <div id="page">
            <div class="<?php echo $_smarty_tpl->getVariable('page_name')->value;?>
">
                <header id="header" class="clearfix">
                   <div id="app_title"><a href="index.php?action=dashboard"><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</a></div>
                   <div class="logininfo">
                        <?php if ($_smarty_tpl->getVariable('my_username')->value==''){?> Sie sind nicht angemeldet. 
                    <?php }else{ ?> 
                        <?php if ($_smarty_tpl->getVariable('my_role_id')->value!=-1){?>(<?php echo $_smarty_tpl->getVariable('stat_users_Online')->value;?>
 User online) | Sie sind als <strong><?php echo $_smarty_tpl->getVariable('my_username')->value;?>
</strong> (<?php echo $_smarty_tpl->getVariable('my_role_name')->value;?>
) angemeldet. <a href="index.php?action=logout">Logout</a><?php }?>
                    <?php }?>
                    
                    </div>
                    
                    <div class="navbar clearfix">
                        <div class="breadcrumb_left"></div>
                        <div class="breadcrumb_right">
                        <?php if ($_smarty_tpl->getVariable('page_message')->value&&isset($_smarty_tpl->getVariable('page_message',null,true,false)->value[0])){?> 
                            <div id="notification_li">
                                <span id="notification_count"><?php echo $_smarty_tpl->getVariable('page_message_count')->value;?>
</span>
                                <a href="#" id="notificationLink">Meldungen</a>
                                <div id="notificationContainer">
                                <div id="notificationTitle">Meldungen</div>
                                <div id="notificationsBody" class="notifications">
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
?><p class="notificationContent"><img src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/basic/logo.png"/><?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']];?>
</p><?php endfor; endif; ?>
                                </div><div id="notificationFooter"></div>
                            </div>    
                        <?php }?>
                        </div>
                        </div>
                    </div>
                </header>    

                    <?php if ($_smarty_tpl->getVariable('page_name')->value=='login'||$_smarty_tpl->getVariable('page_name')->value=='error'){?> <!--Kein Menu -->
                         <div class="floatleft"></div>    
                    <?php }else{ ?>
                            <div class="floatleft"><?php $_template = new Smarty_Internal_Template('menu.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '12771548315448e6b8ede567-58826055';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.6, created on 2014-10-23 13:30:01
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl" */ ?>
<?php if ($_smarty_tpl->getVariable('my_role_id')->value!=-1){?>
    <nav role="user" class="menu border-box">
    <ul class="group">
            <li class="menuheader">Mein Profil</li>
            <?php if ($_smarty_tpl->getVariable('my_semester')->value!=null){?>
            <form method='post' action='index.php'>
                <select name="mySemester" onchange="this.form.submit()">
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
                            <OPTION label="<?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->semester;?>
" value=<?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id;?>
 <?php if (isset($_smarty_tpl->getVariable('my_semester',null,true,false)->value)){?><?php if ($_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id==$_smarty_tpl->getVariable('my_semester')->value){?>selected<?php }?><?php }?>><?php echo $_smarty_tpl->getVariable('mySemester')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->semester;?>
</OPTION>
                <?php endfor; endif; ?>
                </select>
            </form>
            <?php }?>    
            <div ><img src="<?php echo $_smarty_tpl->getVariable('avatar_url')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
"></div>
            <div ><p><strong><?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
</strong></p>
                <?php if (checkCapabilities('menu:readProfile',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <p><a href="index.php?action=profile">Profil bearbeiten</a><p>
                <?php }?>
                <?php if (checkCapabilities('menu:readPassword',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <p><a href="index.php?action=password">Password ändern</a><p> 
                <?php }?>
                <?php if (checkCapabilities('menu:readMessages',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <p><a href="index.php?action=messages">Mitteilungen</a><p>
                <?php }?>
                <?php if (checkCapabilities('file:upload',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <p><a href="assets/scripts/libs/modal-upload/uploadframe.php?userID=<?php echo $_smarty_tpl->getVariable('my_id')->value;?>
&token=<?php echo $_smarty_tpl->getVariable('my_token')->value;?>
&context=userFiles&target=NULL&format=1&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=710&modal=true" class="thickbox">Meine Dateien</a><p>      
                <?php }?>        
            
                <p>Letzter Login: <?php echo $_smarty_tpl->getVariable('my_last_login')->value;?>
</p>
            </div> 
        </ul>
    </nav>

    <?php if (checkCapabilities('menu:readMyCurricula',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
        <nav role="curriculum" class="menu border-box">
            <ul class="group">
                <li class="menuheader">Meine Lehrpläne</li>
                <?php if ($_smarty_tpl->getVariable('my_enrolments')->value!=''){?>
                    <?php  $_smarty_tpl->tpl_vars['cur_menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('my_enrolments')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cur_menu']->key => $_smarty_tpl->tpl_vars['cur_menu']->value){
?>
                        <?php if ($_smarty_tpl->getVariable('cur_menu')->value->semester_id==$_smarty_tpl->getVariable('my_semester')->value){?>
                        <li><p><a href="index.php?action=view&curriculum=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->id;?>
&group=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->group_id;?>
"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
<span> <?php echo $_smarty_tpl->getVariable('cur_menu')->value->groups;?>
</span></a></p></li>
                        <?php }?>
                    <?php }} ?>
                <?php }else{ ?><li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
                <?php }?>    
            </ul>
        </nav>
        <?php }?>    
    
    <?php if (checkCapabilities('menu:readMyPortfolio',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
        <nav role="edit" class="menu border-box">
            <ul class="group">
                <li class="menuheader">Mein Portfolio</li>
                <?php if (checkCapabilities('menu:readPortfolio',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=portfolio&reset=true">Portfolio</a></p></li> 
                <?php }?>
            </ul>
        </nav>   
    <?php }?>    
        
    <?php if (checkCapabilities('menu:readMyInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
        <nav role="edit" class="menu border-box">
            <ul class="group">
                <li class="menuheader">Meine Institution</li>
                <?php if (checkCapabilities('menu:readObjectives',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=objectives&reset=true">Lernstand</a></p></li> 
                <?php }?>
                <?php if (checkCapabilities('menu:readCurriculum',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=curriculum&reset=true">Lehrpläne</a></p></li>                    
                <?php }?>
                <?php if (checkCapabilities('menu:readGroup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=group&reset=true">Lerngruppen</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readUser',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=user&reset=true">Benutzerverwaltung</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readRole',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=role&reset=true">Rollenverwaltung</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readGrade',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=grade">Klassenstufen</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readSubject',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=subject&reset=true">Fächer</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readSemester',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=semester&reset=true">Lernzeiträume</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readBackup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                    <li><p><a href="index.php?action=backup&reset=true">Backup</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readConfirm',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>            
                    <li><p><a href="index.php?action=confirm&reset=true">Freigaben</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readCertificate',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                <li><p><a href="index.php?action=certificate&reset=true">Zertifikate einrichten</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>   
                <li><p><a href="index.php?action=institution&reset=true">Institutionen</a></p></li>
                <?php }?>
                <?php if (checkCapabilities('menu:readConfig',$_smarty_tpl->getVariable('my_role_id')->value,false)||checkCapabilities('menu:readInstitutionConfig',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                <li><p><a href="index.php?action=config">Einstellungen</a></p></li>
                <?php }?>
            </ul>
        </nav>   
    <?php }?>


    <?php if (checkCapabilities('menu:readLog',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
        <nav role="log" class="menu border-box">
            <ul class="group">
                <li class="menuheader">Administration</li>
                <li><p><a href="index.php?action=log">Berichte</a></p></li>
            </ul>
        </nav> 
    <?php }?>
<?php }?>

<?php if ($_smarty_tpl->getVariable('my_role_id')->value==-1&&isset($_smarty_tpl->getVariable('install',null,true,false)->value)){?>
    <nav role="log" class="menu border-box">
        <ul class="group">
            <li class="menuheader">Installation</li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==1){?><strong><?php }?>1 Datenbank einrichten<?php if ($_smarty_tpl->getVariable('step')->value==1){?></strong><?php }?></p></li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==2){?><strong><?php }?>2 Curriculum einrichten<?php if ($_smarty_tpl->getVariable('step')->value==2){?></strong><?php }?></p></li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==3){?><strong><?php }?>3 Institution einrichten<?php if ($_smarty_tpl->getVariable('step')->value==3){?></strong><?php }?></p></li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==4){?><strong><?php }?>4 Administrator einrichten<?php if ($_smarty_tpl->getVariable('step')->value==4){?></strong><?php }?></p></li>
            
        </ul>
    </nav> 
<?php }?>
<p>&nbsp;</p><p>&nbsp;</p><?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?></div>    
                          
                    <?php }?>

                <div id="main" class="group">
                    <div id="content" class="space-top">
                        
    
<div class="border-box">
    <div class="contentheader "><?php echo $_smarty_tpl->getVariable('page_title')->value;?>
</div>
    <div>
        
        <?php if (checkCapabilities('institution:add',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
            <?php if (!isset($_smarty_tpl->getVariable('showInstitutionForm',null,true,false)->value)){?>
            <p class="floatleft  cssimgbtn gray-border">
                <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=institution&function=newInstitution">Institution hinzufügen</a>
            </p>
            <?php }?>
        <?php }?>
        <p>&nbsp;</p><p>&nbsp;</p>
        <?php if (isset($_smarty_tpl->getVariable('showInstitutionForm',null,true,false)->value)){?>
        <form method='post' action='index.php?action=institution'>
        <p>&nbsp;</p>
        <p><h3>Institution</h3></p>
        <input type='hidden' name='id' id='id' <?php if (isset($_smarty_tpl->getVariable('id',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('id')->value;?>
'<?php }?> />   
        <p><label>Institution / Schule*: </label><input class='inputlarge' type='text' name='institution' id='institution' <?php if (isset($_smarty_tpl->getVariable('institution',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('institution')->value;?>
'<?php }?> /></p> 
        <?php smarty_template_function_validate_msg($_smarty_tpl,array('field'=>'institution'));?>

        <p><label>Beschreibung*: </label><input class='inputlarge' type='description' name='description' <?php if (isset($_smarty_tpl->getVariable('description',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('description')->value;?>
'<?php }?>/></p>
        <?php smarty_template_function_validate_msg($_smarty_tpl,array('field'=>'institution_description'));?>

        <p id="schooltype_list"><label>Schultyp: </label><select name="schooltype_id" >
            <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['res']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['name'] = 'res';
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('schooltype')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <option value=<?php echo $_smarty_tpl->getVariable('schooltype')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id;?>
 <?php if (isset($_smarty_tpl->getVariable('schooltype_id',null,true,false)->value)&&$_smarty_tpl->getVariable('schooltype')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id==$_smarty_tpl->getVariable('schooltype_id')->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->getVariable('schooltype')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->schooltype;?>
</option>
            <?php endfor; endif; ?>
            </select></p>  
        <p><label>Anderer Schultyp... </label><input class="centervertical" type="checkbox" name='btn_newSchooltype' value='Neuen Schultyp anlegen' onclick="checkbox_addForm(this.checked, 'inline', 'newSchooltype', 'schooltype_list')"/></p>
        <div id="newSchooltype" style="display:none;">
            <p><label>Schultyp: </label><input class='inputlarge' type='text' name='new_schooltype' id='schooltype_id' <?php if (isset($_smarty_tpl->getVariable('new_schooltype',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('new_schooltype')->value;?>
'<?php }?> /></p> 
            <p><label>Beschreibung: </label><input class='inputlarge' type='text' name='schooltype_description' <?php if (isset($_smarty_tpl->getVariable('schooltype_description',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('schooltype_description')->value;?>
'<?php }?>/></p>
        </div>
        <p><label>Land: </label><select name="country" id="country" onchange="loadStates(this.value);">
            <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['res']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['name'] = 'res';
$_smarty_tpl->tpl_vars['smarty']->value['section']['res']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('countries')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <option label=<?php echo $_smarty_tpl->getVariable('countries')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->de;?>
 value=<?php echo $_smarty_tpl->getVariable('countries')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id;?>
 <?php if (isset($_smarty_tpl->getVariable('country_id',null,true,false)->value)&&$_smarty_tpl->getVariable('countries')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->id==$_smarty_tpl->getVariable('country_id')->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->getVariable('countries')->value[$_smarty_tpl->getVariable('smarty')->value['section']['res']['index']]->de;?>
 </option>
            <?php endfor; endif; ?>
        </select></p>

        <p id="states">
            <?php if (isset($_smarty_tpl->getVariable('state_id',null,true,false)->value)){?>
            <label>Bundesland / Region: </label><SELECT name="state" />
            <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['name'] = 's_id';
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('state')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['s_id']['total']);
?>
                <OPTION label=<?php echo $_smarty_tpl->getVariable('state')->value[$_smarty_tpl->getVariable('smarty')->value['section']['s_id']['index']]->state;?>
 value=<?php echo $_smarty_tpl->getVariable('state')->value[$_smarty_tpl->getVariable('smarty')->value['section']['s_id']['index']]->id;?>
 <?php if ($_smarty_tpl->getVariable('state')->value[$_smarty_tpl->getVariable('smarty')->value['section']['s_id']['index']]->id==($_smarty_tpl->getVariable('state_id')->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->getVariable('state')->value[$_smarty_tpl->getVariable('smarty')->value['section']['s_id']['index']]->state;?>
</OPTION>
            <?php endfor; endif; ?> 
            </SELECT>
            <?php }else{ ?><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.js"></script>
                  <script type='text/javascript'>loadStates(document.getElementById('country').value);</script><?php }?>
        </p>
        
        <?php if (!isset($_smarty_tpl->getVariable('showeditInstitutionForm',null,true,false)->value)){?>
        <p><label></label><input type='submit' name='addInstitution' value='Institution hinzufügen' /></p>
        <?php }else{ ?>
        <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateInstitution" value='Institution aktualisieren' /></p>
	<?php }?>
        </form>	
        <?php }?>
    </div>
         <form id='institutionlist' method='post' action='index.php?action=institution&next=<?php echo $_smarty_tpl->getVariable('currentUrlId')->value;?>
'>
            <p>&nbsp;</p>
    <?php if ($_smarty_tpl->getVariable('data')->value!=null){?>
        <p class="floatright">Datensätze <?php echo $_smarty_tpl->getVariable('institutionPaginator')->value['first'];?>
-<?php echo $_smarty_tpl->getVariable('institutionPaginator')->value['last'];?>
 von <?php echo $_smarty_tpl->getVariable('institutionPaginator')->value['total'];?>
 werden angezeigt.</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                        <td></td>
                    <td>Institution</td>
                    <td>Beschreibung</td>
                    <td>Schultyp</td>
                    <td>Bundesland/Region</td>
                    <td>Land</td>
                    <td>Erstellungsdatum</td>
                    <td>Administrator</td>
                    
                    <td class="td_options">Optionen</td>
            </tr>
                
            <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['name'] = 'ins';
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('institution_list')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['ins']['total']);
?>
                <tr class="contenttablerow" id="row<?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->id;?>
" onclick="checkrow(<?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->id;?>
)">
                    <td><input class="invisible" type="checkbox" id="<?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->id;?>
" name="id[]" value=<?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->id;?>
 /></td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->institution;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->description;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->schooltype_id;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->state_id;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->country;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->creation_time;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->creator_id;?>
</td>
                    <td class="td_options">
                        <?php if (checkCapabilities('institution:delete',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                            <a class="deletebtn floatright" type="button" name="delete" onclick="del('institution',<?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->id;?>
, <?php echo $_smarty_tpl->getVariable('my_id')->value;?>
)"></a>
                        <?php }else{ ?>
                            <a class="deletebtn deactivatebtn floatright" type="button"></a>
                        <?php }?>
                        <?php if (checkCapabilities('institution:update',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>
                            <a class="editbtn floatright" href="index.php?action=institution&edit=true&id=<?php echo $_smarty_tpl->getVariable('institution_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['ins']['index']]->id;?>
"></a>
                        <?php }else{ ?>
                            <a class="editbtn deactivatebtn floatright"></a>
                        <?php }?>
                        </td>
                </tr>
            <?php endfor; endif; ?>
            
            </table>

                    <!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <input class="invisible" type="checkbox" name="id[]" value="none" checked />
            <p class="floatright"><?php echo smarty_function_paginate_prev(array('id'=>"institutionPaginator"),$_smarty_tpl);?>
 <?php echo smarty_function_paginate_middle(array('id'=>"institutionPaginator"),$_smarty_tpl);?>
 <?php echo smarty_function_paginate_next(array('id'=>"institutionPaginator"),$_smarty_tpl);?>
</p>
             <p>&nbsp;</p>
        <?php }?>
        </form>          
            
            
</div>  <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.js"></script>
        <script type='text/javascript'>
	document.getElementById('institution').focus();
	</script>            

                    </div> <!-- end #content -->

                    <div id="sidebar">                    
                         
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
                    
                     
                </footer>
              </div>                          
        </div> <!-- end #page -->
        
        <script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
        <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/jquery/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.js"></script>
        
         
        
        <?php if ($_smarty_tpl->getVariable('tiny_mce')->value){?>
            <script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/tinymce/tinymce.min.js"></script>
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
        <?php }?>
        
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/Chart.js"></script>
        
        <script type="text/javascript" > 
            $(document).ready(function() 
            {
            $("#notificationContainer").slideToggle(200) 
             setTimeout(function() { $("#notificationContainer").slideToggle(200); }, <?php echo $_smarty_tpl->getVariable('message_timeout')->value;?>
);
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
        
        <script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/modal-upload/thickbox.js"></script>  
        <!--[if lt IE 7 ]>
            <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/dd_belatedpng/dd_belatedpng.js"></script>
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
           
        
    </body>
</html>