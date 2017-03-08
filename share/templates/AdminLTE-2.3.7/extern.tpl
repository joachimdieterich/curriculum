{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="login-box">
    <div class="login-logo"><br>
        <img src="assets/images/favicon/apple-touch-icon-57x57.png"></img> <br>
        <b>{$app_title}</b>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        {if isset($acc_info)}
            {$acc_info}
        {/if}
        <input id="close"  class="btn btn-primary btn-block btn-flat visible" value="Fenster schließen" onclick="self.close();"></input>
    </div><!-- /.login-box-body -->
    <div class="box-footer">
          <div class="">
              <b>Version</b> {$app_version}
              <br>{$app_footer}
          </div>
    </div>
</div><!-- /.login-box -->
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
