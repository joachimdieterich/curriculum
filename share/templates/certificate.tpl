{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box">
    <div class="contentheader">{$page_title}<input class="curriculumdocsbtn floatright" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Zertifikatvorlage_einrichten');"/></div>
    {if !isset($showForm) && checkCapabilities('certificate:add', $my_role_id, false)}
        <p class="floatleft  cssimgbtn gray-border">
            <a class="addbtn cssbtnmargin cssbtntext" href="index.php?action=certificate&function=new">Zertifikat hinzufügen</a>
        </p>
    {else}
        {$certificate_form}
        <script type='text/javascript'>document.getElementById('certificate').focus();</script>
    {/if}    
    
    {html_paginator id='certificateP' values=$ct_val config=$certificateP_cfg}
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}