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
            {if isset($wallet_reset)}
                <a href="index.php?action=wallet" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            {/if}
            <div class="has-feedback" style="margin-right: 10px;width:150px;">
                <form id="view_search" method="post" action="index.php?action=wallet">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="fa fa-search form-control-feedback"></span>
                </form>
            </div>
        </div>
        
            <div class="pull-left" style="padding: 0 0 10px 15px;">
                {if checkCapabilities('wallet:add', $my_role_id, false)}    
                <button type="button" class="btn btn-default " onclick="formloader('wallet','new');" ><i class="fa fa-plus"></i> Sammelmappe hinzufügen</button>
                {/if}
                <button type="button" class="btn btn-default " onclick="location.href='index.php?action=wallet&view=shared';" ><i class="fa fa-share-alt"></i> Freigaben</button>
            </div>
        
    </div>
    <div class="row">
        {foreach key=walletid item=w from=$wallet}
            {RENDER::wallet_thumb(['wallet'=>$w])}
        {/foreach}
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}