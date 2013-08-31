<?php /* Smarty version Smarty-3.0.6, created on 2013-08-31 17:15:19
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/teacherGrade.tpl" */ ?>
<?php /*%%SmartyHeaderCode:56823278552220887e41de6-40177678%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '123adf526aa3c33c9d31d9901a9e19129a35b291' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/teacherGrade.tpl',
      1 => 1376820509,
      2 => 'file',
    ),
    'e9d941a08708e3e9080a36e68e883c7c15367c0d' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/base.tpl',
      1 => 1376821769,
      2 => 'file',
    ),
    '8c7bf3ef5fb3e1db5785d70d14743e45c0444d09' => 
    array (
      0 => '/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl',
      1 => 1377934517,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '56823278552220887e41de6-40177678',
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
<?php if (!is_callable('smarty_function_html_options')) include '/Applications/MAMP/htdocs/curriculum/share/libs/Smarty-3.0.6/libs/plugins/function.html_options.php';
if (!is_callable('smarty_function_paginate_prev')) include '/Applications/MAMP/htdocs/curriculum/share/libs/Smarty-3.0.6/libs/plugins/function.paginate_prev.php';
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

        <title><?php echo $_smarty_tpl->getVariable('str_adminGrade')->value;?>
 | <?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</title>
        <meta name="description" content="Beschreibung">
        <meta name="author" content="">
        <?php if ($_smarty_tpl->getVariable('tiny_mce')->value){?>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/tinymce/tinymce.min.js"></script>
        <script type="text/javascript">
        tinymce.init({
             selector: "textarea",
             theme: "modern",
             plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons paste"
            ],
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            toolbar2: "print preview media | forecolor backcolor emoticons"
        });
        </script>
        <?php }?>
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/modernizr/modernizr-1.6.min.js"></script>
    </head>

    <body>
        
        <div id="page" >
        <div class="<?php echo $_smarty_tpl->getVariable('page_name')->value;?>
">
            <header id="header" class="border-bottom-radius">
                 <div class="floatright">
                    <?php if ($_smarty_tpl->getVariable('my_username')->value==''){?> Sie sind nicht angemeldet. 
                 <?php }else{ ?> <?php if ($_smarty_tpl->getVariable('my_role_id')->value!=-1){?>Sie sind als <strong><?php echo $_smarty_tpl->getVariable('my_username')->value;?>
</strong> (<?php echo $_smarty_tpl->getVariable('my_role_name')->value;?>
) angemeldet. <a href="index.php?action=logout">Logout</a><?php }?>
                 <?php }?> 
                 </div>
                <hgroup >
                    <img src="<?php echo $_smarty_tpl->getVariable('BASE_URL')->value;?>
public/assets/images/basic/logo.png" height="40"/><h1><a  href="index.php?action=dashboard"><?php echo $_smarty_tpl->getVariable('app_title')->value;?>
</a></h1>
                </hgroup>
                <?php if ($_smarty_tpl->getVariable('page_message')->value&&isset($_smarty_tpl->getVariable('page_message',null,true,false)->value[0])){?> 
                     <div class="floatright" onclick="showMessagebox();"><img src="<?php echo $_smarty_tpl->getVariable('BASE_URL')->value;?>
public/assets/images/basic/glyphicons_266_flag.png" height="10" /> <?php echo $_smarty_tpl->getVariable('page_message_count')->value;?>
 Meldungen </div>
                     <?php }?>
            </header>    
            
                <?php if ($_smarty_tpl->getVariable('page_name')->value=='login'){?> <!--Kein Menu -->
                    <?php }else{ ?>
                        
                        <div class="floatleft">
                          <?php $_template = new Smarty_Internal_Template('menu.tpl', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->properties['nocache_hash']  = '56823278552220887e41de6-40177678';
$_tpl_stack[] = $_smarty_tpl; $_smarty_tpl = $_template;?>
<?php /* Smarty version Smarty-3.0.6, created on 2013-08-31 17:15:20
         compiled from "/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl" */ ?>
<?php if ($_smarty_tpl->getVariable('my_role_id')->value!=-1){?>
<nav role="user" class="space-top gray-gradient border-radius box-shadow gray-border">
<ul class="group">
        <li class="border-top-radius contentheader">Mein Profil</li>
        <div ><img src="<?php echo $_smarty_tpl->getVariable('avatar_url')->value;?>
<?php echo $_smarty_tpl->getVariable('my_avatar')->value;?>
"></div>
        <div ><p><strong><?php echo $_smarty_tpl->getVariable('my_firstname')->value;?>
 <?php echo $_smarty_tpl->getVariable('my_lastname')->value;?>
</strong></p>
            <p><a href="index.php?action=profile">Profil bearbeiten</a><p>
            <p><a href="index.php?action=password">Password ändern</a><p> 
            <p><a href="index.php?action=messages">Mitteilungen</a><p> 
            <p><a href="assets/scripts/libs/modal-upload/uploadframe.php?userID=<?php echo $_smarty_tpl->getVariable('my_id')->value;?>
&context=userFiles&target=NULL&format=1&placeValuesBeforeTB_=savedValues&TB_iframe=true&width=710&modal=true" class="thickbox">Meine Dateien</a><p> 
            
        <p>Letzter Login: <?php echo $_smarty_tpl->getVariable('my_last_login')->value;?>
</p>
        </div> 
    </ul>
</nav>

<nav role="curriculum" class="space-top gray-gradient border-radius box-shadow gray-border">
    <ul class="group">
        <li class="border-top-radius contentheader">Meine Lehrpläne</li>
        <?php if ($_smarty_tpl->getVariable('my_enrolments')->value!=''){?>
            <?php  $_smarty_tpl->tpl_vars['cur'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('my_enrolments')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['cur']->key => $_smarty_tpl->tpl_vars['cur']->value){
?>
                <li><p><a href="index.php?action=view&curriculum=<?php echo $_smarty_tpl->tpl_vars['cur']->value['id'];?>
&group=<?php echo $_smarty_tpl->tpl_vars['cur']->value['group_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['cur']->value['curriculum'];?>
<span> <?php echo $_smarty_tpl->tpl_vars['cur']->value['groups'];?>
</span></a></p></li>
            <?php }} ?>
        <?php }else{ ?><li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
        <?php }?>    
    </ul>
</nav>
<?php }?>    
    
<?php if ($_smarty_tpl->getVariable('my_role_id')->value!=-1){?>
    <nav role="edit" class="space-top gray-gradient border-radius box-shadow gray-border">
        <ul class="group">
            <li class="border-top-radius contentheader">Meine Institution</li>
  <?php if ($_smarty_tpl->getVariable('my_role_id')->value==1||$_smarty_tpl->getVariable('my_role_id')->value==3||$_smarty_tpl->getVariable('my_role_id')->value==4){?>
            <li><p><a href="index.php?action=teacherObjectives&reset=true">Lernstand</a></p></li>            
            <li><p><a href="index.php?action=teacherCurriculum&reset=true">Lehrpläne</a></p></li>
            <li><p><a href="index.php?action=teacherGroups&reset=true">Lerngruppen verwalten</a></p></li>
            <li><p><a href="index.php?action=teacherProfile">Benutzer anlegen</a></p></li>
            <li><p><a href="index.php?action=teacherUserImport&reset=true">Benutzer-Liste hochladen</a></p></li>
            <li><p><a href="index.php?action=teacherUser&reset=true">Benutzerverwaltung</a></p></li>
  <?php if ($_smarty_tpl->getVariable('my_role_id')->value==1||$_smarty_tpl->getVariable('my_role_id')->value==4){?>
            <li><p><a href="index.php?action=teacherGrade">Klassenstufen</a></p></li>
            <li><p><a href="index.php?action=teacherSubject&reset=true">Fächer</a></p></li>
            <li><p><a href="index.php?action=teacherSemester&reset=true">Lernzeiträume verwalten</a></p></li>
            <li><p><a href="index.php?action=teacherBackup&reset=true">Backup erstellen</a></p></li>
            <li><p><a href="index.php?action=teacherConfirm&reset=true">Freigaben</a></p></li>
  <?php }?> <?php }?>
  
            <li><p><a href="index.php?action=config">Einstellungen</a></p></li>
          
        </ul>
    </nav>   
<?php }?>


<?php if ($_smarty_tpl->getVariable('my_role_id')->value==1){?>
    <nav role="log" class="space-top gray-gradient border-radius box-shadow gray-border">
        <ul class="group">
            <li class="border-top-radius contentheader">Administration Log</li>
            <li><p><a href="index.php?action=adminLog">Logfiles</a></p></li>
        </ul>
    </nav> 
<?php }?>
<?php if ($_smarty_tpl->getVariable('my_role_id')->value==-1&&isset($_smarty_tpl->getVariable('install',null,true,false)->value)){?>
    <nav role="log" class="space-top gray-gradient border-radius box-shadow gray-border">
        <ul class="group">
            <li class="border-top-radius contentheader">Installation</li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==1){?><strong><?php }?>1 Datenbank einrichten<?php if ($_smarty_tpl->getVariable('step')->value==1){?></strong><?php }?></p></li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==2){?><strong><?php }?>2 Curriculum einrichten<?php if ($_smarty_tpl->getVariable('step')->value==2){?></strong><?php }?></p></li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==3){?><strong><?php }?>3 Institution einrichten<?php if ($_smarty_tpl->getVariable('step')->value==3){?></strong><?php }?></p></li>
            <li><p><?php if ($_smarty_tpl->getVariable('step')->value==4){?><strong><?php }?>4 Administrator einrichten<?php if ($_smarty_tpl->getVariable('step')->value==4){?></strong><?php }?></p></li>
            
        </ul>
    </nav> 
<?php }?>

<p>&nbsp;</p><p>&nbsp;</p><?php $_smarty_tpl->updateParentVariables(0);?>
<?php /*  End of included template "/Applications/MAMP/htdocs/curriculum/share/templates/menu.tpl" */ ?>
<?php $_smarty_tpl = array_pop($_tpl_stack);?><?php unset($_template);?>
                        </div>    
                        
                <?php }?>
    
            <div id="main" class="group">
                <div id="content" class="space-top space-bottom">
                    
    
<div class=" border-radius gray-border">	
    <div class="border-top-radius contentheader "><?php echo $_smarty_tpl->getVariable('str_adminGrade')->value;?>
</div>
    <div class="space-top-padding gray-gradient border-bottom-radius box-shadow ">
        
        <?php if (!isset($_smarty_tpl->getVariable('showGradeForm',null,true,false)->value)){?>
        <p class="floatleft gray-gradient cssimgbtn border-radius gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=teacherGrade&function=newGrade">Klassenstufe hinzufügen</a>
        </p>
        
        <?php }?>
        <p>&nbsp;</p><p>&nbsp;</p>
        <?php if (isset($_smarty_tpl->getVariable('showGradeForm',null,true,false)->value)){?>
        <form id='addGrade' method='post' action='index.php?action=teacherGrade&next=<?php echo $_smarty_tpl->getVariable('currentUrlId')->value;?>
'>
        <input type='hidden' name='id' id='id' <?php if (isset($_smarty_tpl->getVariable('id',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('id')->value;?>
'<?php }?> /></p>   
        <p><label><?php echo $_smarty_tpl->getVariable('str_adminGrade_addGradeName')->value;?>
</label><input class='inputformlong' type='text' name='grade' id='grade' <?php if (isset($_smarty_tpl->getVariable('grade',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('grade')->value;?>
'<?php }?> /></p>   
        <?php smarty_template_function_validate_msg($_smarty_tpl,array('field'=>'grade'));?>

	<p><label><?php echo $_smarty_tpl->getVariable('str_description')->value;?>
</label><input class='inputformlong' type='description' name='description' <?php if (isset($_smarty_tpl->getVariable('description',null,true,false)->value)){?>value='<?php echo $_smarty_tpl->getVariable('description')->value;?>
'<?php }?>/></p>
        <?php smarty_template_function_validate_msg($_smarty_tpl,array('field'=>'description'));?>

        <?php if (count($_smarty_tpl->getVariable('my_institutions')->value['id'])>1){?>
            <p><label>Institution / Schule*: </label><?php echo smarty_function_html_options(array('id'=>'institution','name'=>'institution','values'=>$_smarty_tpl->getVariable('my_institutions')->value['id'],'output'=>$_smarty_tpl->getVariable('my_institutions')->value['institution']),$_smarty_tpl);?>
</p>
        <?php }elseif(count($_smarty_tpl->getVariable('my_institutions')->value['id'])==0){?>
            <p><strong>Sie müssen zuerst eine Institution anlegen</strong></p>
        <?php }else{ ?>
            <input type='hidden' name='institution' id='institution' value='<?php echo $_smarty_tpl->getVariable('my_institutions')->value['id'][0];?>
' /></p>       
        <?php }?>
        
        <script type='text/javascript'>
	document.getElementById('grade').focus();
	</script>
        
        <?php if (!isset($_smarty_tpl->getVariable('showeditGradeForm',null,true,false)->value)){?>
        <p><label></label><input type='submit' name="addGrade" value='<?php echo $_smarty_tpl->getVariable('str_adminGrade_addbtn')->value;?>
' /></p>
        <?php }else{ ?>
        <p><label></label><input type='submit' name="back" value='zurück'/><input type='submit' name="updateGrade" value='Klassenstufe aktualisieren' /></p>
        <?php }?>
	</form>	
        <?php }?>
         
        <form id='classlist' method='post' action='index.php?action=teacherGrade&next=<?php echo $_smarty_tpl->getVariable('currentUrlId')->value;?>
'>
            <p>&nbsp;</p>
    <?php if ($_smarty_tpl->getVariable('data')->value!=null){?>
        <p class="floatright"><?php echo $_smarty_tpl->getVariable('str_adminGrade_pagItem')->value;?>
 <?php echo $_smarty_tpl->getVariable('gradePaginator')->value['first'];?>
-<?php echo $_smarty_tpl->getVariable('gradePaginator')->value['last'];?>
 <?php echo $_smarty_tpl->getVariable('str_adminGrade_pagTo')->value;?>
 <?php echo $_smarty_tpl->getVariable('gradePaginator')->value['total'];?>
</p>   
            <table id="contenttable">
                    <tr id="contenttablehead">
                    <td></td>    
                    <td><?php echo $_smarty_tpl->getVariable('str_adminGrade_Grade')->value;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('str_description')->value;?>
</td>
                    <td class="td_options">Optionen</td>
            </tr>
            <?php unset($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['name'] = 'grade';
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['loop'] = is_array($_loop=$_smarty_tpl->getVariable('grade_list')->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['grade']['total']);
?>
                <tr class="contenttablerow" id="row<?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->id;?>
" onclick="checkrow(<?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->id;?>
)">
                    <td><input class="invisible" type="checkbox" id="<?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->id;?>
" name="id[]" value=<?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->id;?>
 /></td>
                    <td><?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->grade;?>
</td>
                    <td><?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->description;?>
</td>
                    <td class="td_options">
                        <a class="deletebtn floatright" type="button" name="delete" onclick="deleteGrade(<?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->id;?>
)"></a>
                        <a class="editbtn floatright" href="index.php?action=teacherGrade&edit=true&id=<?php echo $_smarty_tpl->getVariable('grade_list')->value[$_smarty_tpl->getVariable('smarty')->value['section']['grade']['index']]->id;?>
"></a>
                        </td>
                </tr>
            <?php endfor; endif; ?>
            </table>  
            <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack für problem, dass kein Array gepostet wird, wenn nichts angewählt wird-->
            <p class="floatright"><?php echo smarty_function_paginate_prev(array('id'=>"gradePaginator"),$_smarty_tpl);?>
 <?php echo smarty_function_paginate_middle(array('id'=>"gradePaginator"),$_smarty_tpl);?>
 <?php echo smarty_function_paginate_next(array('id'=>"gradePaginator"),$_smarty_tpl);?>
</p>
            <p>&nbsp;</p>
        <?php }?>
        </form>              
        
</div>

                   
                </div> <!-- end #content -->
                
                <div id="sidebar">                    
                    
                    
                    
               
                    <!-- Popup -->     
                    <div id="popup" ></div> 
                    <!-- end Popup --> 
                 
                </div> <!-- end #sidebar -->
            </div> <!-- end #main -->
            
            <!-- Message-Box -->
            <?php if ($_smarty_tpl->getVariable('page_message')->value&&isset($_smarty_tpl->getVariable('page_message',null,true,false)->value[0])){?> 
            <div id="messagebox"><!--<p id="messageboxheader">Messagebox</p>-->
                <div class="messageboxClose" onclick="hideMessagebox();"></div>
                <div id="messageboxcontent"><div>
                        
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
?><p><?php echo $_smarty_tpl->getVariable('page_message')->value[$_smarty_tpl->getVariable('smarty')->value['section']['mes']['index']];?>
</p><?php endfor; endif; ?></div>
                </div>
            </div>
            <?php }?>
                <footer id="footer" class="space-top border-top-radius gray-border gray-gradient">
                    <div> 
                        <?php echo $_smarty_tpl->getVariable('app_footer')->value;?>
 
                    </div>
                </footer>
        </div>    
               
                    
        </div> <!-- end #page -->
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js">
        </script> 
        
        <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/jquery/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/script.js"></script>
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/selectScript.js"></script>
        
        
            <script type='text/javascript'>
                $(document).ready(function($) { 
                setTimeout(function() { $("#messagebox").slideToggle(); }, <?php echo $_smarty_tpl->getVariable('message_timeout')->value;?>
); 
                });
            </script>
            
        
        
        <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/simplemodal/jquery.simplemodal-1.4.2.js"></script>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/modal-upload/thickbox.js"></script>  
        <script type="text/javascript" src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/datetimepicker_css.js"></script>  
        <!--[if lt IE 7 ]>
            <script src="<?php echo $_smarty_tpl->getVariable('media_url')->value;?>
scripts/libs/dd_belatedpng/dd_belatedpng.js"></script>
            <script>DD_belatedPNG.fix('img, .trans-bg');</script>
        <![endif]--> 
        
        

    </body>
</html>
