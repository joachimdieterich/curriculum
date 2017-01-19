{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    <!-- jQuery 2.1.4 -->
    <script src="{$template_url}plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{$template_url}bootstrap/js/bootstrap.min.js"></script>
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="#"><b>{$app_title}</b></a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name">{$my_firstname} {$my_lastname}</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="{$access_file}{$my_avatar}" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials" action="index.php?action=lock" method="post">
      <div class="input-group">
        <input type="password" class="form-control " name="password" placeholder="password">

        <div class="input-group-btn">
          <button type="button" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Geben Sie Ihr Kennwort ein, um Ihre Sitzung abzurufen.
  </div>
  <div class="text-center">
    <a href="index.php?action=login">Benutzer wechseln.</a>
  </div>
  <div class="lockscreen-footer text-center">
    {$app_footer}
  </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
