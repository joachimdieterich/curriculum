{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="pull-right">
            {if isset($help_reset)}
                <a href="index.php?action=help" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            {/if}
            <div class="has-feedback" style="margin-right: 10px;width:150px;">
                <form id="view_search" method="post" action="index.php?action=help">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </form>
            </div>
        </div>
        {if checkCapabilities('help:add', $my_role_id, false)}    
            <div class="pull-left" style="padding: 0 0 10px 15px;">
                <button type="button" class="btn btn-default " onclick="formloader('help','new')" ><i class="fa fa-plus"></i> Datei hinzufügen</button>
            </div>
        {/if}
    </div>
    <div class="row">
        {foreach key=helpid item=h from=$help}
            {RENDER::helpcard($h)}
        {/foreach}
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}