{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    <!-- jQuery 2.1.4 -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="{$media_url}templates/AdminLTE-2.3.0/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="login-box">
    <div class="login-logo"><br>
          <img src="assets/images/favicon/apple-touch-icon-57x57.png"></img> <br>
        <b>{$app_title}</b>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg"></p>
        <form action="index.php?action=login" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" id="username" name="username" {if isset($username)}value="{$username}"{/if} placeholder="Benutzername">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Passwort">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
            </div><!-- /.col -->
            <div class="col-xs-4">
              <input type="submit" name="login" class="btn btn-primary btn-block btn-flat" value="Anmelden" ></input>
            </div><!-- /.col -->
          </div>
        </form>
        {if isset($cfg_shibboleth)}
        <div class="social-auth-links text-center">
          <p>- ODER -</p>
          <a href="../share/plugins/auth/shibboleth/index.php" class="btn btn-block btn-social btn-openid"><img src="assets/images/icons/shibboleth-web.png"></img> Über Shibboleth anmelden</a>
        </div>
        {/if}
        <a href="#" onclick="alert('Funktion noch nicht verfügbar');">Passwort vergessen</a><br>
        <a href="#" class="text-center" onclick="alert('Funktion noch nicht verfügbar');">Registrieren</a>

      </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
