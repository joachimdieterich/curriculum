{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    <script src="{$template_url}bootstrap/js/bootstrap.min.js"></script><!-- Bootstrap 3.3.5 -->
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<div class="login-box">
    <div class="login-logo"><br>
          <img alt="curriculum-logo" src="assets/images/favicon/apple-touch-icon-57x57.png"/> <br>
        <b>{$app_title}</b>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
          <p id="reset_info" class="login-box-msg hidden">Bitte geben Sie Ihren Benutzername ein und klicken auf "Passwort zurücksetzen".<br>Über Ihren Administrator bekommen Sie dann die neue Zugangsdaten. </p>
        {if isset($page_message)}
            <strong>{FORM::info('error', '',$page_message[0]['message'], '','col-sm-12 text-red')}</strong>
        {/if}
        <form id="form_login" action="{$base_url}public/index.php?action=login" method="post">
          <div class="form-group has-feedback {if isset($page_message)}has-error{/if}">
            <input type="text" class="form-control" id="username" name="username" {if isset($username)}value="{$username}"{/if} placeholder="Benutzername">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div id="password" class="form-group has-feedback {if isset($page_message)}has-error{/if}">
            <input type="password" class="form-control" name="password" placeholder="Passwort">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
                <div class="col-xs-6 pull-left">{*!pull-left to not submit guest login on return, when entering regular user accounts*}
                    <input id="pw_reset" class="btn btn-primary btn-block btn-flat visible" onclick="toggle(['reset', 'reset_info'], ['login', 'password', 'pw_reset']);" value="Passwort vergessen" />
                </div><!-- /.col -->
            <div class="col-xs-6 pull-right">
              <input id="login" type="submit" name="login" class="btn btn-primary btn-block btn-flat visible" value="Einloggen" />
              <input id="reset" type="submit" name="reset" class="btn btn-primary btn-block btn-flat hidden" value="Passwort vergessen" />
            </div><!-- /.col -->
          </div>
            {if $cfg_guest_login eq '1' OR  $cfg_shibboleth eq '1'}      
                <p class="text-center top-buffer"><strong>- ODER -</strong></p>  
            {/if}
            {if $cfg_guest_login}
                <div class="row">
                    <div class="col-xs-6 pull-left">{*!pull-left to not submit guest login on return, when entering regular user accounts*}
                        <input id="register" {*type="submit"*} onclick="alert('Funktion noch nicht verfügbar');"  name="register" class="btn btn-primary btn-block btn-flat visible" value="Registrierung" data-toggle="tooltip" title="Noch nicht registriert?"/>
                    </div><!-- /.col -->
                    <div class="col-xs-6 pull-right">{*!pull-left to not submit guest login on return, when entering regular user accounts*}
                        <input id="guest" type="submit" name="guest" class="btn btn-primary btn-block btn-flat visible" value="Gastzugang" data-toggle="tooltip" title="Als Gast einen Einblick bekommen"/>
                    </div><!-- /.col -->
                </div>
            {/if}    
            {if $cfg_shibboleth}
            <div class="social-auth-links text-center">
              <a href="../share/plugins/auth/shibboleth/index.php" class="btn btn-block btn-social btn-openid"><img src="assets/images/icons/shibboleth-web.png"/> Über Shibboleth anmelden</a>
            </div>
            {/if}
        </form>
      </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
