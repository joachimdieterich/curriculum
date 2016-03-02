{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}   
<div class="border-box">
    <h3 class="page-header">{$page_title}<input class="curriculumdocsbtn pull-right" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Lernzeitr%C3%A4ume_verwalten');"/></h3>
    {if !isset($showForm) && checkCapabilities('semester:add', $my_role_id, false)}
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default"><a href="index.php?action=semester&function=new">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Lernzeitraum hinzufügen</a>
            </button>
        </div>
    {else}
        <form id='semesterForm' method='post' action='index.php?action=semester&next={$currentUrlId}'>
        <input id='id' name='id' type='hidden' {if isset($id)}value='{$id}'{/if}/>       
        <p><label>Lernzeitraum*:</label>        <input id='semester' name='semester' class='inputlarge' {if isset($semester)}value='{$semester}'{/if} /></p>   
        {validate_msg field='semester'}
        <p><label>Beschreibung*:</label>        <input id='description' name='description' class='inputlarge' {if isset($description)}value='{$description}'{/if}/></p>
        {validate_msg field='description'}
        <p><label>Lernzeitraum-Beginn*:</label> <input id='begin' name='begin' type='date' {if isset($begin)}value='{$begin}'{/if}/>
        {validate_msg field='begin'}
        <p><label>Lernzeitraum-Ende*:</label>   <input id='end' name='end' type='date' {if isset($end)}value='{$end}'{/if}/>
        {validate_msg field='end'}
        <p><label>Institution / Schule*:</label><SELECT  name='institution_id' id='institution_id' />
            {foreach key=insid item=ins from=$my_institutions}
                <OPTION  value="{$ins->institution_id}"  {if $ins->institution_id eq $institution_id}selected="selected"{/if}>{$ins->institution}</OPTION>
            {/foreach} 
        </SELECT></p>
        {if !isset($editBtn)}
            <p><label></label><input name="add" type='submit' value='Lernzeitraum erstellen' /></p>
        {else}
            <p><label></label><input name="back" type='submit' value='zurück'/><input name="update" type='submit' value='Lernzeitraum aktualisieren' /></p>
        {/if}
        </form>	
        <script type='text/javascript'> document.getElementById('semester').focus(); </script>
    {/if} 
    
    {html_paginator id='semesterP'}         
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}