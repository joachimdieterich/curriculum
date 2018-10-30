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
</script>
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Benutzerverwaltung'} 
<!-- Main content -->
<div class="row">
    <div class="col-xs-12">
        <div class="panel">
            <div class="panel-heading">
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
                </div>
                {/if}
                {if checkCapabilities('user:userListComplete', $my_role_id, false)}
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="button" class="btn btn-default" onclick="location.href='index.php?action=user&lost=true';"><a  href="#">
                            <span class="fa fa-group" aria-hidden="true"></span> Nicht zugeordnete Benutzer</a>
                    </button>
                </div>
                {/if}
            </div><!-- /.box-header -->
            <div class="panel-body">
                <form id='userlist'   method='post' action="index.php?action=user&next={$currentUrlId}">
                    {html_paginator id='userP' title='Benutzerliste'} 
                    <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack - nothing selected-->  
                        <div class="row">
                        {if checkCapabilities('user:enroleToGroup', $my_role_id, false) OR checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                            <div class="form-horizontal col-xs-12 col-sm-12 col-md-5 col-lg-3">
                            <h4>Lerngruppe</h4>
                            <p>Markierte Benutzer in Lerngruppe ein bzw. ausschreiben</p>
                            {if isset($groups_array)}
                                {Form::input_select_multiple(['id' => 'groups', 'label' => 'Lerngruppe', 'select_data' => $groups_array, 'select_label' => 'group, semester', 'select_value' => 'id', 'input' => null, 'error' => null, 'limiter' => ', ' ])}
                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    {if checkCapabilities('user:enroleToGroup', $my_role_id, false)}
                                    <button type='submit' name='enroleGroups' value='' class="btn btn-default">
                                            <span class="fa fa-plus-circle" aria-hidden="true"></span> einschreiben
                                    </button>
                                    {/if}
                                    {if checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                                        <button type='submit' name='expelGroups' value='' class="btn btn-default">
                                                <span class="fa fa-minus-circle" aria-hidden="true"></span> ausschreiben
                                        </button>
                                    {/if}
                                </div>
                            </div> 
                            {/if}
                        {/if}

                        {if checkCapabilities('user:updateRole', $my_role_id, false)}
                            <div class="form-horizontal col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                <h4>Institution / Rolle</h4>
                                <p>Beim Zuweisen einer Rolle werden die markierten Nutzer automatisch in die aktuelle/ausgewählte Institution eingeschrieben bzw. die Daten aktualisiert.</p>
                                {if isset($myInstitutions)}
                                    {Form::input_select('institution', 'Institution', $myInstitutions, 'institution', 'id', $my_institution_id, null)}
                                {/if}    
                                {Form::input_select('roles', 'Benutzer-Rolle', $roles, 'role', 'id', $institution_std_role, null)}

                                <div class="btn-group pull-right" role="group" aria-label="...">
                                {if checkCapabilities('user:enroleToInstitution', $my_role_id, false)}
                                    <button type='submit' name='enroleInstitution' value='' class="btn btn-default">
                                        <span class="fa fa-plus-circle" aria-hidden="true"></span> Rolle zuweisen / einschreiben
                                    </button>
                                {/if}
                                {if checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                                    <button type='submit' name='expelInstitution' value='' class="btn btn-default">
                                        <span class="fa fa-minus-circle" aria-hidden="true"></span> ausschreiben
                                    </button>
                                {/if} 
                                </div>
                            </div>    
                        {/if}     

                        {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                            <div class="form-horizontal col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                <h4>Passwort zurücksetzen</h4>
                                <p>Neues Passwort für markierte Benutzer festlegen. Passwort muss mind. 6 Zeichen lang sein.</p>
                                {Form::input_text('pwchange', 'Passwort', '', null, '', 'password')}
                                {Form::input_checkbox('showpassword', 'Passwort anzeigen', '', null, 'checkbox', 'unmask(\'pwchange\', this.checked);')}
                                {Form::input_checkbox('confirm', 'Passwortänderung', '', null)}
                                <button type='submit' name='resetPassword' value='' class="btn btn-default pull-right">
                                        <span class="fa fa-lock" aria-hidden="true"></span> Passwort zurücksetzen
                                </button>
                            </div>
                        {/if}

                        {if checkCapabilities('user:delete', $my_role_id, false)} 
                            <div class="form-horizontal col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                <h4>Benutzer</h4>
                                <p>Markierte Benutzer löschen</p>
                                <button type='submit' name='deleteUser' value='' class="btn btn-default pull-right" onclick="userdelete();">
                                        <span class="fa fa-minus-circle" aria-hidden="true"></span> löschen
                                </button>
                                <button id='btn_deleteUser' type='submit' name='deleteUser' value='' class="hidden"></button>
                            </div>
                        {/if}
                    </div>    
                </form>     
            </div>
        </div>
    </div>

    {if !isset($groups_array)}<p>Sie können noch keine Benutzer verwalten, da sie entweder nicht über die nötigen Rechte verfügen, oder keine Benutzer in ihrer Institution vorhanden sind.</p><p>&nbsp;</p>{/if}
</div> 
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}