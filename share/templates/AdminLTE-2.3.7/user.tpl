{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
<script> 
  function userdelete() {
  if(confirm("Sollen die ausgewählten Benutzer wirklich gelöscht werden?"))
    $("#btn_deleteUser").click();
  else
    return false;
    }
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("href") // activated tab
  processor("config","page", "config",{literal}{{/literal}"tab": target.substring(1), "reload": "false"{literal}}{/literal});
 
});
</script>
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Benutzerverwaltung'}      
  
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    {if checkCapabilities('user:addUser', $my_role_id, false)}
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-default" onclick="formloader('profile', 'new');"><a  href="#">
                            <span class="fa fa-plus-circle" aria-hidden="true"></span> Benutzer hinzufügen</a>
                        </button>
                        {if checkCapabilities('menu:readuserImport', $my_role_id, false)}
                            <button type="button" class="btn btn-default" onclick="location.href='index.php?action=userImport';"><a href="index.php?action=userImport">
                                <span class="fa fa-plus-circle" aria-hidden="true"></span> Benutzerliste importieren</a>
                            </button>
                        {/if}
                        {if checkCapabilities('user:userListComplete', $my_role_id, false)}
                        <button type="button" class="btn btn-default" onclick="location.href='index.php?action=user&lost=true';"><a  href="#">
                            <span class="fa fa-group" aria-hidden="true"></span> Nicht zugeordnete Benutzer</a>
                        </button>
                        {/if}
                    </div>
                    {/if}
                </div><!-- /.box-header -->
                <div class="box-body">
                    {if isset($myInstitutions)}
                        {if count($myInstitutions) >= 1}
                            {Form::input_select('institution_filter', null, $myInstitutions, 'institution', 'id', $filter_institution_id, null, "location.href='index.php?action=user&filter_institution='+this.value+'&filter_role=false&filter_group=false'", 'Nach Institution filtern')}      
                            {if $filter_institution_id neq false}
                                {Form::input_select('role_filter', null, $roles, 'role', 'id', $filter_role_id, null, "location.href='index.php?action=user&filter_institution='+document.getElementById('institution_filter').value+'&filter_role='+document.getElementById('role_filter').value+'&filter_group='+document.getElementById('group_filter').value", 'Nach Rolle filtern')}
                                {Form::input_select('group_filter', null, $groups, 'group', 'id', $filter_group_id, null, "location.href='index.php?action=user&filter_institution='+document.getElementById('institution_filter').value+'&filter_role='+document.getElementById('role_filter').value+'&filter_group='+document.getElementById('group_filter').value", 'Nach Gruppe filtern')}
                            {/if}
                        {/if}
                    {/if}
                    {html_paginator id='userP' title='Benutzerliste'} 
                    <!--<input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack - nothing selected-->  
                </div>  
            </div>
                        
            {* Function Tabs *}
            <form id='userlist'   method='post' action="index.php?action=user&next={$currentUrlId}">
            <div class="row ">
                    <div class="col-sm-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                                    <li {if isset($f_password)}class="active"{/if}><a href="#f_password" data-toggle="tab">Passwort</a></li>
                                {/if}
                                {if checkCapabilities('user:enroleToGroup', $my_role_id, false) OR checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                                    <li {if isset($f_group)}class="active"{/if}><a href="#f_group" data-toggle="tab">Lerngruppe</a></li>
                                {/if}
                                {if checkCapabilities('user:updateRole', $my_role_id, false)}
                                    <li {if isset($f_institution)}class="active"{/if}><a href="#f_institution" data-toggle="tab">Institution / Rolle</a></li>
                                {/if}
                                {if checkCapabilities('user:delete', $my_role_id, false)}
                                    <li {if isset($f_delete)}class="active"{/if}><a href="#f_delete" data-toggle="tab"><span class="text-danger">löschen</span></a></li>
                                {/if}
                            </ul>

                            <div class="tab-content">
                                {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                                    <div id="f_password" class="tab-pane {if isset($f_password)}active{/if} row " >
                                        <div class="form-horizontal col-xs-12">
                                        {Form::info(['id' => 'pw_info', 'content' => 'Neues Passwort für markierte Benutzer festlegen. Passwort muss mind. 6 Zeichen lang sein.'])}
                                        {Form::input_text('pwchange', 'Passwort', '', null, '', 'password')}
                                        {Form::input_checkbox('showpassword', 'Passwort anzeigen', '', null, 'checkbox', 'unmask(\'pwchange\', this.checked);')}
                                        {Form::input_checkbox('confirmed', 'Passwortänderung', '', null)}
                                        {Form::input_button(['id' => 'resetPassword', 'label' => 'Passwort zurücksetzen', 'icon' => 'fa fa-lock', 'class' => 'btn btn-default pull-right'])}
                                        </div>
                                    </div>
                                {/if}
                                {if checkCapabilities('user:enroleToGroup', $my_role_id, false) OR checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                                    <div id="f_group" class="tab-pane row {if isset($f_group)}active{/if}" >
                                        <div class="form-horizontal col-xs-12">
                                            {Form::info(['id' => 'group_info', 'content' => 'Markierte Benutzer in Lerngruppe ein bzw. ausschreiben.<br> <strong>Benutzer muss an der entsprechenden Institution eingeschrieben sein, damit  die Lerngruppe angezeigt wird.</strong>'])}
                                            {if isset($myInstitutions)}
                                                {Form::input_select('institution_group', 'Institution', $myInstitutions, 'institution', 'id', $my_institution_id, null, "getValues('group', this.value, 'groups');")}
                                            {/if} 
                                        {if isset($groups_array)}
                                            {Form::input_select_multiple(['id' => 'groups', 'label' => 'Lerngruppe', 'select_data' => $groups_array, 'select_label' => 'group, semester', 'select_value' => 'id', 'input' => null, 'error' => null, 'limiter' => ', ' ])}
                                            <div class="btn-group pull-right" role="group" aria-label="...">
                                            {if checkCapabilities('user:enroleToGroup', $my_role_id, false)}
                                                {Form::input_button(['id' => 'enroleGroups', 'label' => 'einschreiben', 'icon' => 'fa fa-plus-circle', 'class' => 'btn btn-default pull-left'])}
                                            {/if}
                                            {if checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                                                {Form::input_button(['id' => 'expelGroups', 'label' => 'ausschreiben', 'icon' => 'fa fa-minus-circle', 'class' => 'btn btn-default pull-left'])}
                                            {/if}
                                            </div>
                                        {/if}
                                        </div>
                                    </div>
                                {/if}
                                {if checkCapabilities('user:updateRole', $my_role_id, false)}
                                    <div id="f_institution" class="tab-pane row {if isset($f_institution)}active{/if}" >
                                        <div class="form-horizontal col-xs-12">
                                            {Form::info(['id' => 'role_info', 'content' => 'Beim Zuweisen einer Rolle werden die markierten Nutzer automatisch in die aktuelle/ausgewählte Institution eingeschrieben bzw. die Daten aktualisiert.'])}
                                        {if isset($myInstitutions)}
                                            {Form::input_select('institution', 'Institution', $myInstitutions, 'institution', 'id', $my_institution_id, null)}
                                        {/if}    
                                        {Form::input_select('roles', 'Benutzer-Rolle', $roles, 'role', 'id', $institution_std_role, null)}

                                        <div class="btn-group pull-right" role="group" aria-label="...">
                                        {if checkCapabilities('user:enroleToInstitution', $my_role_id, false)}
                                            {Form::input_button(['id' => 'enroleInstitution', 'label' => 'Rolle zuweisen / einschreiben', 'icon' => 'fa fa-plus-circle', 'class' => 'btn btn-default'])}
                                        {/if}
                                        {if checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                                            {Form::input_button(['id' => 'expelInstitution', 'label' => 'ausschreiben', 'icon' => 'fa fa-minus-circle', 'class' => 'btn btn-default'])}
                                        {/if} 
                                        </div>
                                        </div>
                                    </div>
                                {/if}
                                {if checkCapabilities('user:delete', $my_role_id, false)}
                                    <div id="f_delete" class="tab-pane row {if isset($f_delete)}active{/if}" >
                                        <div class="form-horizontal col-xs-12">
                                            {Form::info(['id' => 'user_info', 'content' => 'Markierte Benutzer löschen.'])}
                                            {Form::input_button(['id' => 'submitdeleteUser', 'label' => 'löschen', 'icon' => 'fa fa-minus-circle', 'type' => 'button', 'onclick' => 'userdelete()', 'class' => 'btn btn-default pull-right'])}
                                            {Form::input_button(['id' => 'deleteUser', 'label' => 'löschen', 'icon' => 'fa fa-minus-circle', 'class' => 'hidden'])}
                                        </div>
                                    </div>
                                {/if}
                            </div><!-- ./tab-content -->
                        </div><!-- ./nav-tabs-custom -->
                    </div><!-- ./col-xs-12 -->  
                 
            </div>
            </form>   
        {if !isset($groups_array)}<p>Sie können noch keine Benutzer verwalten, da sie entweder nicht über die nötigen Rechte verfügen, oder keine Benutzer in ihrer Institution vorhanden sind.</p><p>&nbsp;</p>{/if}
   </div> 
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}