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
            <div class="has-feedback" style="margin-right: 10px;">
                <form id="view_search" method="post" action="index.php?action=help">
                    <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </form>
            </div>
            
        </div>
    </div>
    <div class="row">
        
        {foreach key=helpid item=h from=$help}
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="#" onclick="formloader('preview','help','{$h->id}')">
              <div class="info-box">
                <span class="info-box-icon bg-aqua" style="background: url('../share/accessfile.php?id={$h->file_id}');background-size: cover; background-repeat: no-repeat;">{*<i class="{resolveFileTypeById({$h->file_id})}"> </i>*}</span>
                <div class="info-box-content">
                  <span class="info-box-text text-black">{$h->category}</span>
                  <span class="info-box-number text-black">{$h->title}</span>
                  <span class="info-box-more text-primary">{$h->description}</span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </a>
        </div>
        {/foreach}
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}