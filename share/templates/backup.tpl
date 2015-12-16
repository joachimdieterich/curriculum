{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="border-box" >
    <div class="contentheader ">{$page_title}</div>
        {if isset($courses)}
        <form method='post' action='index.php?action=backup'>
            <p><select id='course' name='course' onchange="window.location.assign('index.php?action=backup&course='+this.value);"> {*_blank global regeln*}
                    <option value="-1" data-skip="1">Lehrplan wählen...</option>
                {section name=res loop=$courses}
                    <option value="{$courses[res]->id}"
                    data-icon="{$subjects_path}{$courses[res]->icon}" data-html-text="{$courses[res]->group} - {$courses[res]->curriculum}&lt;i&gt;
                    {$courses[res]->description}&lt;/i&gt;">{$courses[res]->group} - {$courses[res]->curriculum}
                    </option>  
                {/section} 
                </select>
            </p>
        </form>
        {else}<p><strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong></p>{/if}
        
        {if isset($zipURL)}
            <p>Folgende Backups können heruntergeladen werden.</p>
            <p><a class="url_btn floatleft" href={$zipURL} ></a></p>
            <p>Aktuelle Sicherungsdatei herunterladen.</p></br></br>
        {/if} 
        
        {html_paginator id='backupP' values=$fb_val config=$backupP_cfg}
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}