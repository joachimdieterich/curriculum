{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Klassenstufen_anlegen'}  

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">  

    {if !isset($showForm) AND checkCapabilities('grade:add', $my_role_id, false)}
    <div class="btn-group" role="group" aria-label="...">
        <button type="button" class="btn btn-default"><a href="index.php?action=grade&function=new">
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Klassenstufe hinzufügen</a>
        </button>
    </div>
    {else}
        <form id='gradeForm' method='post' action='index.php?action=grade&next={$currentUrlId}'>
            <input id='id' name='id' type='hidden' {if isset($id)}value='{$id}'{/if} /></p>   
            <p><label>Klassenstufen-Name</label><input id='grade' name='grade' class='inputlarge' {if isset($grade)}value='{$grade}'{/if} /></p>
            {validate_msg field='grade'}
            <p><label>Beschreibung</label><input name='description' class='inputlarge' {if isset($description)}value='{$description}'{/if}/></p>
            {validate_msg field='description'}
            <p><label>Institution / Schule*:</label><SELECT  name='institution_id' id='institution_id' />
            {foreach key=insid item=ins from=$my_institutions}
                <OPTION  value="{$ins->institution_id}"  {if $ins->institution_id eq $institution_id}selected="selected"{/if}>{$ins->institution}</OPTION>
            {/foreach} 
            </SELECT></p>
            <script type='text/javascript'> document.getElementById('grade').focus(); </script>
            <p><label></label>
                {if !isset($editBtn)}
                    <input name="add" type='submit' value='Klassenstufe hinzufügen' 
                {else}
                    <input name="back" type='submit' value='zurück'/><input name="update" type='submit' value='Klassenstufe aktualisieren' />
                {/if}
            </p>
        </form>	
    {/if}
    
{html_paginator id='gradeP'}

                </div>
            </div>
        </div>
    </div>
</section>    
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}