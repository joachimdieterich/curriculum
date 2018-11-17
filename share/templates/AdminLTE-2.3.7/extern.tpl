{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="login-box">
    <div class="login-logo"><br>
        <img alt="curriculum-logo" src="assets/images/favicon/apple-touch-icon-57x57.png"/> <br>
        <b>{$app_title}</b>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        {if isset($acc_info)}
            {$acc_info}
        {/if}
        {if isset($btn_target)}
            <input id="close"  class="btn btn-primary btn-block btn-flat visible" value="Fenster schließen" onclick="location.href='{$btn_target}'"></input>
        {else}
            <input id="close"  class="btn btn-primary btn-block btn-flat visible" value="Fenster schließen" onclick="self.close();"></input>
        {/if}
        
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
