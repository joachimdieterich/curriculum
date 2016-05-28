{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Rollen_und_Rechte'}      
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body"></div>    
    
<div class="border-box">
    {if !isset($showForm) && checkCapabilities('role:add', $my_role_id, false)}
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default"><a href="index.php?action=role&function=new">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Rolle hinzufügen</a>
            </button>
        </div>
    {else}
        <form id='roleForm' method='post' action='index.php?action=role&next={$currentUrlId}'>
        <input id='r_id'  name='r_id' type='hidden' {if isset($r_id)}value='{$r_id}'{/if} />  
        <p><label>Rollennamen</label>   <input id='r_role' name='r_role' class='inputlarge' {if isset($r_role)}value='{$r_role}'{/if} /></p>   
        {validate_msg field='r_role'}
	<p><label>Beschreibung</label>  <input id='r_description' name='r_description' class='inputlarge' {if isset($r_description)}value='{$r_description}'{/if} /></p>
        {validate_msg field='r_description'}
        {*capabilities*}
        {assign var="section" value=""}
        {section name=cap loop=$capabilities}
            {assign var="colon" value=$capabilities[cap]->capability|strpos:":"}
            {if $section neq $capabilities[cap]->capability|substr:0:$colon}
                <div class="contentheader">{$capabilities[cap]->capability|substr:0:$colon}</div> 
            {/if}
             <p><label class="inputlarge" style="margin-right:100px" >{$capabilities[cap]->name}</label>
                 <input type="checkbox" name="{$capabilities[cap]->capability}" id="{$capabilities[cap]->capability}" class="ios-toggle checkbox1" {if $capabilities[cap]->permission eq 1} value="true" checked {else} value="false"{/if} onclick="switchValue('{$capabilities[cap]->capability}');"/>
                 <label for="{$capabilities[cap]->capability}" class="checkbox-label" data-off="nicht erlaubt" data-on="erlaubt"></label> 
                
                {*<input name="{$capabilities[cap]->capability}" type="radio" class="inputsmall" value="true"{if $capabilities[cap]->permission eq 1}checked{/if}> erlaubt
                <input name="{$capabilities[cap]->capability}" type="radio" class="inputsmall" value="false"{if $capabilities[cap]->permission eq 0}checked{/if}> nicht erlaubt*}
                <p class="tiny_txt">{$capabilities[cap]->capability}</p>
                 
            </p>   
            {assign var="section" value=$capabilities[cap]->capability|substr:0:$colon}
        {/section}    
        {if !isset($editBtn)}
            <p><label></label><input name="add" type='submit' value='Rolle hinzufügen' /></p>
        {else}
            <p><label></label><input name="back" type='submit' value='zurück'/><input name="update" type='submit' value='Rolle aktualisieren' /></p>
        {/if}
	</form>	
        <script type='text/javascript'> document.getElementById('r_role').focus();</script>
    {/if}
    
    {html_paginator id='roleP'}
</div>
                </div>
            </div>
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}