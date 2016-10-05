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
        {foreach key=helpid item=h from=$help}
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="#" onclick="formloader('preview','help','{$h->id}')">
              <div class="info-box">
                <span class="info-box-icon bg-aqua" style="background: url('../share/accessfile.php?id={$h->file_id}') center right;background-size: cover; background-repeat: no-repeat;"><i class="ion ion-ios-gear-outline"> </i></span>
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