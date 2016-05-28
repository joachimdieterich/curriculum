{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Benutzerverwaltung'}      
  
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    {if checkCapabilities('user:addUser', $my_role_id, false)}
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-default"><a  href="index.php?action=profile&function=new">
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Benutzer hinzufügen</a>
                        </button>
                        {if checkCapabilities('menu:readuserImport', $my_role_id, false)}
                            <button type="button" class="btn btn-default"><a href="index.php?action=userImport&reset=true">
                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Benutzerliste importieren</a>
                            </button>
                        {/if}
                    </div>
                    {/if}
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form id='userlist'  method='post' action="index.php?action=user&next={$currentUrlId}">
                        {html_paginator id='userP'} 
                        <input class="invisible" type="checkbox" name="id[]" value="none" checked /><!--Hack - nothing selected-->
                            {if isset($showFunctions)}
                            {if $showFunctions}  
                                <div class="row">
                                {if checkCapabilities('user:enroleToGroup', $my_role_id, false) OR checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                                    <div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                    <h4>Lerngruppe</h4>
                                    <p>Markierte Benutzer in Lerngruppe ein bzw. ausschreiben</p>
                                    {if isset($groups_array)}
                                    <label>Lerngruppe:</label>

                                        <select class="form-control " name="groups">
                                            {section name=res loop=$groups_array}  
                                            <option label="{$groups_array[res]->group}" value={$groups_array[res]->id}> {$groups_array[res]->group} | {$groups_array[res]->semester} | {$groups_array[res]->institution}</option>
                                            {/section}
                                        </select> 

                                        <p><div class="btn-group" role="group" aria-label="...">
                                            {if checkCapabilities('user:enroleToGroup', $my_role_id, false)}
                                            <button type='submit' name='enroleGroups' value='' class="btn btn-default">
                                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> einschreiben
                                            </button>
                                            {/if}
                                            {if checkCapabilities('user:expelFromGroup', $my_role_id, false)}
                                                <button type='submit' name='expelGroups' value='' class="btn btn-default">
                                                        <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> ausschreiben
                                                </button>
                                            {/if}
                                        </div></p>

                                    </div> 
                                    {/if}
                                {else}<p><strong>Sie müssen zuerst eine Lerngruppe anlegen</strong></p>{/if}


                                {if checkCapabilities('user:updateRole', $my_role_id, false)}
                                    <div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                        <h4>Institution / Rolle</h4>
                                        <p>Beim Zuweisen einer Rolle werden die markierten Nutzer automatisch in die aktuelle/ausgewählte Institution eingeschrieben bzw. die Daten aktualisiert.</p>
                                        {if isset($myInstitutions)}
                                            <p><label>Institution / Schule: </label>
                                                <select name="institution" >
                                                    {section name=res loop=$myInstitutions}  
                                                        <option label="{$myInstitutions[res]->institution}" value={$myInstitutions[res]->id} {if $myInstitutions[res]->id eq $my_institution_id} selected="selected"{/if}>{$myInstitutions[res]->institution}</option>
                                                    {/section}
                                            </select> 
                                        {/if}    
                                        <p><label>Benutzer-Rolle:</label>
                                        <SELECT  name='roles' id='roles' />
                                        {foreach key=rolid item=rol from=$roles}
                                            <OPTION  value="{$rol->id}" >{$rol->role}</OPTION>
                                        {/foreach} 
                                        </SELECT>
                                        <p><label></label>
                                        {if checkCapabilities('user:enroleToInstitution', $my_role_id, false)}
                                            <input type='submit' name='enroleInstitution' value='Rolle zuweisen / einschreiben' />
                                        {/if}
                                        {if checkCapabilities('user:expelFromInstitution', $my_role_id, false)}
                                            <input type='submit' name='expelInstitution' value='ausschreiben' />
                                        {/if} 
                                        </p>
                                    </div>    
                                {/if}     

                                {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                                    <div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                        <h4>Passwort zurücksetzen</h4>
                                        <p>Neues Passwort für markierte Benutzer festlegen. Passwort muss mind. 6 Zeichen lang sein.</p>

                                        <p><label>Neues Passwort:</label><input  type='password' name='password' id='password'  {if isset($password)}value='{$password}'{/if}/>
                                        {validate_msg field='password'}
                                        <p><label>Passwort anzeigen: </label><input type="checkbox" class="centervertical" name='showpassword'  {if isset($inputshowpassword)}checked{/if} onclick="unmask('password', this.checked);"/></p>
                                        <p><label>Passwortänderung: </label><input type="checkbox" name='confirmed'  {if isset($inputconfirmed)}checked{/if}/></p>
                                        <p><label></label><input type='submit' name='resetPassword' value='Passwort zurücksetzen' /></p>
                                    </div>
                                {/if}

                                {if checkCapabilities('user:delete', $my_role_id, false)} 
                                    <div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-3">
                                        <h4>Benutzer</h4>
                                        <p>Markierte Benutzer löschen</p>
                                        <button type='submit' name='deleteUser' value='' class="btn btn-default">
                                                <span class="glyphicon glyphicon-delete-sign" aria-hidden="true"></span> löschen
                                        </button>
                                    </div>
                                {/if}
                            {else}<p><input type='submit' name="back" value='Funktionen einblenden'/></p>{/if}{/if}{*/if*}     
                            </div>    
                    </form>     
                </div>
            </div>
        </div>
    

        {if !isset($groups_array)}<p>Sie können noch keine Benutzer verwalten, da sie entweder nicht über die nötigen Rechte verfügen, oder keine Benutzer in ihrer Institution vorhanden sind.</p><p>&nbsp;</p>{/if}

{*Groups paginator*}
{if isset($groupsPaginator)}
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Lerngruppen des Benutzers</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
            {html_paginator id='groupsPaginator'}    
        </div>
    </div>  
{/if}   
        
        
{*Curriculum paginator*}
{if isset($curriculumList)}
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Lehrpläne des Benutzers</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
            {html_paginator id='curriculumList'}
        </div>
    </div>  
{/if}

{*Institution paginator*}
{if isset($institutionList)}
    <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Institutionen des Benutzers</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
            {html_paginator id='institutionList'}  
        </div>
    </div>  
{/if}

   </div> 
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}