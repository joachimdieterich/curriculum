<?php /* Smarty version Smarty-3.0.6, created on 2017-01-06 14:14:59
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/Bootflat-2.0.4/wallet.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2068183605863687a1a18f6-21654874%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51b8566f8f6561583295ce40b8f2f1f006f27de3' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/Bootflat-2.0.4/wallet.tpl',
      1 => 1482909807,
      2 => 'file',
    ),
    'f1c1503b92acda18655a74fc2318afdde313d027' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/Bootflat-2.0.4/base.tpl',
      1 => 1483691742,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2068183605863687a1a18f6-21654874',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_content_header')) include '/Applications/MAMP/htdocs/curriculum/share/templates/Bootflat-2.0.4/plugins/function.content_header.php';
if (!is_callable('smarty_modifier_resolve_file_id')) include '/Applications/MAMP/htdocs/curriculum/share/templates/Bootflat-2.0.4/plugins/modifier.resolve_file_id.php';
?><!DOCTYPE html>  
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="de" class="no-js" > <!--<![endif]--><head><meta charset="utf-8"><!-- Sets initial viewport load and disables zooming  --><meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no"><meta name="keywords" content="curriculum, digitaler Lehrplan, curriculumonline.de"><title><?php echo $_smarty_tpl->getVariable('page_title')->value;?>
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
images/favicon/mstile-144x144.png" /><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/site.min.css"><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/all-bs.min.css"><script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
js/site.min.js"></script><!-- Font Awesome --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
/font-awesome/css/font-awesome.min.css"><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/google-fonts.min.css" ><!-- Bootstrap Color Picker --><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/colorpicker/bootstrap-colorpicker.min.css"><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
css/buttons.min.css"><link rel="stylesheet" href="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
jquery.nyroModal/styles/nyroModal.css" media="all"></head><?php if ($_smarty_tpl->getVariable('page_action')->value=='login'||$_smarty_tpl->getVariable('page_action')->value=='lock'||$_smarty_tpl->getVariable('page_action')->value=='install'){?><body style="background-image: url('<?php echo $_smarty_tpl->getVariable('random_bg')->value;?>
'); background-size: cover;" >
<!-- Content Header (Page header) -->
<?php echo smarty_function_content_header(array('p_title'=>$_smarty_tpl->getVariable('page_title')->value,'pages'=>$_smarty_tpl->getVariable('breadcrumb')->value,'help'=>''),$_smarty_tpl);?>
       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="pull-right">
            <?php if (isset($_smarty_tpl->getVariable('wallet_reset',null,true,false)->value)){?>
                <a href="index.php?action=wallet" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            <?php }?>
            <div class="has-feedback" style="margin-right: 10px;width:150px;">
                <form id="view_search" method="post" action="index.php?action=wallet">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="fa fa-search form-control-feedback"></span>
                </form>
            </div>
        </div>
        <?php if (checkCapabilities('wallet:add',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>    
            <div class="pull-left" style="padding: 0 0 10px 15px;">
                <button type="button" class="btn btn-default " onclick="formloader('wallet','new')" ><i class="fa fa-plus"></i> Sammelmappe hinzufügen</button>
            </div>
        <?php }?>
    </div>
    <div class="row">
        <?php  $_smarty_tpl->tpl_vars['w'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['walletid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('wallet')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['w']->key => $_smarty_tpl->tpl_vars['w']->value){
 $_smarty_tpl->tpl_vars['walletid']->value = $_smarty_tpl->tpl_vars['w']->key;
?>
            <?php echo RENDER::wallet_thumb(array('wallet'=>$_smarty_tpl->tpl_vars['w']->value));?>

        <?php }} ?>
    </div>
</section>
</body><?php }else{ ?><body class="bg-aqua"><?php if (isset($_smarty_tpl->getVariable('page_bg_file_id',null,true,false)->value)){?><span style="position: fixed;left: 0;top: 0;right:0; height:600px;background-image: url('<?php echo $_smarty_tpl->getVariable('access_file_id')->value;?>
<?php echo $_smarty_tpl->getVariable('page_bg_file_id')->value;?>
'); background-position: center center; background-size: cover;  background-repeat: no-repeat;"></span><?php }else{ ?><span style="position: fixed;left: 0;top: 0;right:0; height:600px;background-image: url('<?php echo $_smarty_tpl->getVariable('random_bg')->value;?>
'); background-position: center center; background-size: cover;  background-repeat: no-repeat;"></span><?php }?><div class="transparent_gradient" style="position: fixed;left: 0;top: 0;right:0; height:600px;"></div><nav class="navbar navbar-default navbar-custom" role="navigation"><div style="padding-left:15px;padding-right:15px;"><div class="navbar-header"><button type="button" class="navbar-toggle " data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars text-white"></i><span class="sr-only">Toggle navigation</span></button><a class="navbar-brand" href="index.php?action=dashboard"><img src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/logo_white_bg.png" height="40"></h5></a></div><ul class="nav navbar-nav navbar-left"><li><a><h5 style="padding:0;margin:0;"><?php echo $_smarty_tpl->getVariable('page_title')->value;?>
</h5></a></li></ul><div class="collapse navbar-collapse"><ul class="nav navbar-nav navbar-right"><?php if (checkCapabilities('dashboard:globalAdmin',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="pull-left" style="display: inline-block;"><a href="index.php?action=statistic" style="padding: 15px 8px 15px 8px;"><i class="fa fa-pie-chart"></i></a></li><?php }?><li class="pull-left" style="display: inline-block;"><a href="index.php?action=help" style="padding: 15px 8px 15px 8px;"><i class="fa fa-graduation-cap"></i></a></li><?php if (isset($_smarty_tpl->getVariable('mySemester',null,true,false)->value)&&count($_smarty_tpl->getVariable('mySemester')->value)>1){?><?php echo Form::input_dropdown(array('class'=>'pull-left','style'=>'display: inline-block; ','icon'=>'fa-history','select_data'=>$_smarty_tpl->getVariable('mySemester')->value,'select_label'=>'semester, institution','select_value'=>'id','input'=>$_smarty_tpl->getVariable('my_semester_id')->value,'onclick'=>"processor(\'semester\',\'set\',this.getAttribute(\'data-id\'));"));?>
<?php }elseif(isset($_smarty_tpl->getVariable('my_institutions',null,true,false)->value)&&count($_smarty_tpl->getVariable('my_institutions')->value)>1){?><?php echo Form::input_dropdown(array('class'=>'pull-left','style'=>'display: inline-block; ','icon'=>'fa-history','select_data'=>$_smarty_tpl->getVariable('my_institutions')->value,'select_label'=>'institution','institution_id','select_value'=>'institution_id','input'=>$_smarty_tpl->getVariable('my_institution_id')->value,'onclick'=>"processor('config','institution_id', this.getAttribute('data-id'));"));?>
<?php }?><?php if (isset($_smarty_tpl->getVariable('mails',null,true,false)->value)){?><!-- Messages: style can be found in dropdown.less--><li class="dropdown pull-left" style="display: inline-block;"><a href="#" class="dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-envelope-o"></i><span class="label label-success"><?php echo count($_smarty_tpl->getVariable('mails')->value);?>
</span></a><ul class="dropdown-menu" role="menu"><li class="header">Sie haben <?php echo count($_smarty_tpl->getVariable('mails')->value);?>
 neue Nachrichten</li><li><?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['mes']);
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
"><div class="pull-left"><!--img src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo smarty_modifier_resolve_file_id($_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->sender_file_id,"xs");?>
" class="" alt="User Image"--></div><?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->sender_username;?>
<br><small><i class="fa fa-calendar-times-o"></i> <?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->creation_time;?>
</small><p><?php echo $_smarty_tpl->getVariable('mails')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']]->subject;?>
</p></a></li><?php endfor; endif; ?><!-- end message --></li><li class="footer"><a href="index.php?action=messages&function=showInbox">Alle Nachrichten</a></li></ul></li><?php }else{ ?><li class="messages-menu pull-left margin-r-10" style="display: inline-block;"><a href="index.php?action=messages&function=showInbox" style="padding: 15px 8px 15px 8px;"><i class="fa fa-envelope-o"></i></a></li><?php }?><?php if (isset($_smarty_tpl->getVariable('page_message',null,true,false)->value)){?><li class="dropdown pull-left" style="display: inline-block;"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell-o"></i><span class="label label-warning"><?php echo count($_smarty_tpl->getVariable('page_message')->value);?>
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
<?php }?></a></li><?php endfor; endif; ?></ul><li class="footer"><a href="#"> <!--Alle zeigen--></a></li></ul></li><?php }?><li class="calendar-menu pull-left margin-r-10" style="display: inline-block;"><a href="index.php?action=calendar" style="padding: 15px 8px 15px 8px;"><i class="fa fa-calendar"></i></a></li><li class="timeline-menu" style="display: inline-block;"><a href="index.php?action=portfolio" style="padding: 15px 8px 15px 8px;"><i class="fa fa-cubes"></i></a></li><!-- dropdown Lehrpläne --><li class="dropdown "><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Lehrpläne <b class="caret"></b></a><ul class="dropdown-menu" role="menu" style="width:350px; overflow: scroll; max-height: 200px;"><?php if ($_smarty_tpl->getVariable('my_enrolments')->value!=''){?><?php  $_smarty_tpl->tpl_vars['cur_menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('my_enrolments')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cur_menu']->key => $_smarty_tpl->tpl_vars['cur_menu']->value){
?><?php if ($_smarty_tpl->getVariable('cur_menu')->value->semester_id==$_smarty_tpl->getVariable('my_semester_id')->value){?><?php if ($_smarty_tpl->getVariable('cur_menu')->value->id==$_smarty_tpl->getVariable('cur_menu')->value->base_curriculum_id||$_smarty_tpl->getVariable('cur_menu')->value->base_curriculum_id==null){?><li <?php if (isset($_smarty_tpl->getVariable('page_curriculum',null,true,false)->value)){?><?php if (($_smarty_tpl->getVariable('page_curriculum')->value==$_smarty_tpl->getVariable('cur_menu')->value->id)&&($_smarty_tpl->getVariable('page_group')->value==$_smarty_tpl->getVariable('cur_menu')->value->group_id)){?> class="active"<?php }?><?php }?>><a class="text-ellipse" href="index.php?action=view&curriculum_id=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->id;?>
&group=<?php echo $_smarty_tpl->getVariable('cur_menu')->value->group_id;?>
" style="padding-right:20px;"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->curriculum;?>
<span class="label pull-right bg-green"><?php echo $_smarty_tpl->getVariable('cur_menu')->value->groups;?>
</span></a></li><?php }?><?php }?><?php }} ?><?php }else{ ?><li><a href=""><i class="fa fa-dashboard"></i><span>Sie sind in keinen Lehrplan <br>eingeschrieben</span></a></li><?php }?></ul></li><!-- ./dropdown Lehrpläne --><!-- dropdown Funktionen --><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Funktionen <b class="caret"></b></a><ul class="dropdown-menu" role="menu" style="width:350px;"><?php if (checkCapabilities('menu:readMyInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="header">Institution: <?php echo $_smarty_tpl->getVariable('my_institution')->value->institution;?>
</li><?php if (checkCapabilities('menu:readObjectives',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='objectives'){?>active<?php }?>"><a href="index.php?action=objectives"><i class="fa fa-edit margin-r-10"></i>Lernstand eingeben</a></li><?php }?><?php if (checkCapabilities('menu:readCourseBook',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='courseBook'){?>active<?php }?>"><a href="index.php?action=courseBook"><i class="fa fa-book margin-r-10"></i>Kursbuch</a></li><?php }?><?php if (checkCapabilities('menu:readWallet',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='wallet'){?>active<?php }?>"><a href="index.php?action=wallet"><i class="fa fa-newspaper-o margin-r-10"></i>Sammelmappe</a></li><?php }?><?php if (checkCapabilities('menu:readCurriculum',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='curriculum'){?>active<?php }?>"><a href="index.php?action=curriculum"><i class="fa fa-th margin-r-10"></i>Lehrpläne</a></li><?php }?><?php if (checkCapabilities('menu:readGroup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='group'){?>active<?php }?>"><a href="index.php?action=group"><i class="fa fa-group margin-r-10"></i>Lerngruppen</a></li><?php }?><?php if (checkCapabilities('menu:readUser',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='user'){?>active<?php }?>"><a href="index.php?action=user"><i class="fa fa-user margin-r-10"></i>Benutzer</a></li><?php }?><?php if (checkCapabilities('menu:readRole',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='role'){?>active<?php }?>"><a href="index.php?action=role"><i class="fa fa-key margin-r-10"></i>Rollenverwaltung</a></li><?php }?><?php if (checkCapabilities('menu:readGrade',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='grade'){?>active<?php }?>"><a href="index.php?action=grade"><i class="fa fa-signal margin-r-10"></i>Klassenstufen</a></li><?php }?><?php if (checkCapabilities('menu:readSubject',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='subject'){?>active<?php }?>"><a href="index.php?action=subject"><i class="fa fa-language margin-r-10"></i>Fächer</a></li><?php }?><?php if (checkCapabilities('menu:readSemester',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='semester'){?>active<?php }?>"><a href="index.php?action=semester"><i class="fa fa-history margin-r-10"></i>Lernzeiträume</a></li><?php }?><?php if (checkCapabilities('menu:readBackup',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='backup'){?>active<?php }?>"><a href="index.php?action=backup"><i class="fa fa-cloud-download margin-r-10"></i>Backup</a></li><?php }?><?php if (checkCapabilities('menu:readCertificate',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='certificate'){?>active<?php }?>"><a href="index.php?action=certificate"><i class="fa fa-files-o margin-r-10"></i>Zertifikate</a></li><?php }?><?php if (checkCapabilities('menu:readInstitution',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li class="<?php if ($_smarty_tpl->getVariable('page_action')->value=='institution'){?>active<?php }?>"><a href="index.php?action=institution"><i class="fa fa-university margin-r-10"></i>Institutionen</a></li><?php }?><?php if (checkCapabilities('menu:readLog',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><li <?php if ($_smarty_tpl->getVariable('page_action')->value=='log'){?>class="active"<?php }?>><a href="index.php?action=log"><i class="fa fa-list margin-r-10"></i><span>Berichte</span></a></li><?php }?><?php }?></ul></li><!-- ./dropdown Funktionen --><!-- dropdown Usermenü --><li class="dropdown "><a href="#" class="dropdown-toggle" data-toggle="dropdown"><img class="media-object img-rounded" style="height: 18px;" src="<?php echo $_smarty_tpl->getVariable('access_file')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
" class="user-image" alt="User Image"> <b class="caret"></b></a><ul class="dropdown-menu" role="menu" style="width:350px;"><li class="dropdown-header"><?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
<span class="badge badge-primary pull-right"><?php echo $_smarty_tpl->getVariable('my_role_name')->value;?>
</span><br><small>Mitglied seit <?php echo $_smarty_tpl->getVariable('my_creation_time')->value;?>
</small></li><li> <span class="pull-left"><?php if (checkCapabilities('user:resetPassword',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><a href="#" class="pull-left" onclick="formloader('password', 'edit');" data-toggle="tooltip" title="Passwort ändern"><i class="fa fa-user-secret"></i></a><?php }?><?php if (checkCapabilities('user:update',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><a href="#" class="pull-left" onclick="formloader('profile', 'edit');" data-toggle="tooltip" title="Profil bearbeiten"><i class="fa fa-user"></i></a><?php }?><?php if (checkCapabilities('file:upload',$_smarty_tpl->getVariable('my_role_id')->value,false)){?><a href="../share/templates/Bootflat-2.0.4/renderer/uploadframe.php?context=userFiles<?php echo $_smarty_tpl->getVariable('tb_param')->value;?>
" data-toggle="tooltip" title="Meine Dateien" class="pull-left nyroModal"><i class="fa fa-folder-open"></i></a><?php }?></span><span class="pull-right"><a href="index.php?action=logout" data-toggle="tooltip" title="Abmelden" class=" pull-right">Abmelden</a><a href="index.php?action=lock" data-toggle="tooltip" title="Fenster sperren" class="pull-right"><i class="fa fa-lock"></i></a></span></li></ul></li><!-- ./dropdown Usermenü --></ul></div></div></nav><div class="row" ><div class="col-xs-12"><div  class="content-top-padding" ><div id="popup" class="modal" onload="popupFunction(this.id);"><div class="modal-dialog"><div class="panel"><div class="panel-heading"><h3 >Loading...</h3></div><div class="panel-body"><div class="overlay text-center"><i class="fa fa-refresh fa-spin"></i></div></div></div></div></div> <!-- Popup -->
<!-- Content Header (Page header) -->
<?php echo smarty_function_content_header(array('p_title'=>$_smarty_tpl->getVariable('page_title')->value,'pages'=>$_smarty_tpl->getVariable('breadcrumb')->value,'help'=>''),$_smarty_tpl);?>
       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="pull-right">
            <?php if (isset($_smarty_tpl->getVariable('wallet_reset',null,true,false)->value)){?>
                <a href="index.php?action=wallet" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            <?php }?>
            <div class="has-feedback" style="margin-right: 10px;width:150px;">
                <form id="view_search" method="post" action="index.php?action=wallet">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="fa fa-search form-control-feedback"></span>
                </form>
            </div>
        </div>
        <?php if (checkCapabilities('wallet:add',$_smarty_tpl->getVariable('my_role_id')->value,false)){?>    
            <div class="pull-left" style="padding: 0 0 10px 15px;">
                <button type="button" class="btn btn-default " onclick="formloader('wallet','new')" ><i class="fa fa-plus"></i> Sammelmappe hinzufügen</button>
            </div>
        <?php }?>
    </div>
    <div class="row">
        <?php  $_smarty_tpl->tpl_vars['w'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['walletid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('wallet')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['w']->key => $_smarty_tpl->tpl_vars['w']->value){
 $_smarty_tpl->tpl_vars['walletid']->value = $_smarty_tpl->tpl_vars['w']->key;
?>
            <?php echo RENDER::wallet_thumb(array('wallet'=>$_smarty_tpl->tpl_vars['w']->value));?>

        <?php }} ?>
    </div>
</section>
</div></div></div><div class="row"><div class="col-md-12"><div class="footer"><div class="container"><div class="clearfix"><div class="footer-logo"><a href="#"><img src="<?php echo $_smarty_tpl->getVariable('request_url')->value;?>
assets/images/logo_white_bg.png"><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</a></div><dl class="footer-nav"><dt class="nav-title">ABOUT</dt><dd class="nav-item"><a href="http://curriculumonline.de" target="_blank"><i class="fa fa-question-circle"></i> curriculum</a></dd></dl><dl class="footer-nav"><dt class="nav-title">CONTRIBUTING</dt><dd class="nav-item"><a href="http://www.github.com/joachimdieterich/curriculum" target="_blank"><i class="fa fa-github"></i> github</a></dd></dl><dl class="footer-nav"><dt class="nav-title">CONTACT</dt><dd class="nav-item"><a href="mailto:mail@joachimdieterich.de"><i class="fa fa-at"></i> Joachim Dieterich</a></dd></dl></div><div class="footer-copyright text-center"><?php echo $_smarty_tpl->getVariable('app_footer')->value;?>
</div></div></div></div></div></body><?php }?><!-- SCRIPTS--><script src="<?php echo $_smarty_tpl->getVariable('lib_url')->value;?>
ckeditor/ckeditor.js"></script><!-- CK Editor --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/moment/moment.min.js"></script><!-- moment --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/jquery-2.2.1.min.js"></script> <!-- jQuery 2.2.1 --><script src="<?php echo $_smarty_tpl->getVariable('template_url')->value;?>
plugins/slimScroll/jquery.slimscroll.min.js"></script><!-- SlimScroll 1.3.0 --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/curriculum.min.js"></script><!-- curriculum settings (sidebar) --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
jquery.nyroModal/js/jquery.nyroModal.custom.js"></script> <!-- jquery.nyroModal --><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/PDFObject-master/pdfobject.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/file.min.js"></script><script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/dragndrop.min.js"></script><!-- Logout - Timer  --><?php if (isset($_smarty_tpl->getVariable('institution_timeout',null,true,false)->value)){?><script type="text/javascript">idleMax = <?php echo $_smarty_tpl->getVariable('global_timeout')->value;?>
;        // Logout based on global timout valueidleTime = 0;$(document).ready(function () {var idleInterval = setInterval("timerIncrement()", 60000);$(document.getElementById('popup')).attr('class', 'modal');});function timerIncrement() {idleTime = idleTime + 1;if (idleTime === idleMax) {window.location="index.php?action=logout&timout=true";}}</script><?php }?><!-- end Logout - Timer  --><!-- Nyromodal  --><script type="text/javascript">$(function() {$('.nyroModal').nyroModal({callbacks: {beforeShowBg: function(){$('body').css('overflow', 'hidden');},afterHideBg: function(){$('body').css('overflow', '');},afterShowCont: function(nm) {$('.scroll_list').height($('.modal').height()-150);}}});$('#popup_generate').nyroModal();});</script><?php if (isset($_SESSION['FORM']->form)){?><script type="text/javascript"><?php if (isset($_SESSION['FORM']->id)){?><?php if ($_SESSION['FORM']->id!=''){?>$(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
', <?php echo $_SESSION['FORM']->id;?>
));<?php }?>$(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
'));<?php }else{ ?>$(document).ready(formloader('<?php echo $_SESSION['FORM']->form;?>
', '<?php echo $_SESSION['FORM']->func;?>
'));<?php }?></script><?php }?></body></html>