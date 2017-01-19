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
                <a href="index.php?action=help" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
            {/if}
            <div class="has-feedback" style="margin-right: 10px;width:150px;">
                <form id="view_search" method="post" action="index.php?action=help">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="fa fa-search form-control-feedback"></span>
                </form>
            </div>
        </div>
        {if checkCapabilities('wallet:add', $my_role_id, false)}    
            <div class="pull-left" style="padding: 0 0 10px 15px;">
                <button type="button" class="btn btn-default " onclick="formloader('wallet','new')" ><i class="fa fa-plus"></i> Sammelmappe hinzufügen</button>
            </div>
        {/if}
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <strong>{$wallet->title}<span class="pull-right">{$wallet->timerange}</span></strong>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {$wallet->description}
                    </div>
                    {assign var="order_id" value="null"} 
                    {foreach key=wcid item=wc from=$wallet->content}
                        {if $order_id neq $wc->order_id}
                            {if $order_id neq "null"}
                            </div>
                            {/if}
                            <div id="row_{$wc->order_id}" class="row">
                            {assign var="order_id" value=$wc->order_id}
                        {/if}
                            {RENDER::wallet_content($wc)}
                    {/foreach}
                </div>
            </div>
        </div>
        
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}