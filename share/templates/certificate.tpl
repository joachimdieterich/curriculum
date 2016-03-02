{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <h3 class="page-header">{$page_title}<input class="curriculumdocsbtn pull-right" type="button" name="help" onclick="curriculumdocs('http://docs.joachimdieterich.de/index.php?title=Zertifikatvorlage_einrichten');"/></h3>
    {if !isset($showForm) && checkCapabilities('certificate:add', $my_role_id, false)}
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default"><a href="index.php?action=certificate&function=new">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Zertifikat hinzufügen</a>
            </button>
        </div>
    {else}
        {$certificate_form}
        <script type='text/javascript'>document.getElementById('certificate').focus();</script>
    {/if}    
    
    {html_paginator id='certificateP'}
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}