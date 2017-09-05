{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <div class="row">
        <div class="container documents">
            <div class="col-xs-12 col-sm-offset-3 col-md-offset-4 col-md-6">
                <div class="pricing ">
                    <ul >
                        <li class="unit price-primary active" style="min-width: 400px !important;">
                                <div class="price-title">
                                        <img src="assets/images/favicon/apple-touch-icon-57x57.png"></img> <br>
                                        <b>{$app_title}</b>
                                </div>
                                <div class="price-body">
                                        <p id="reset_info" class="hidden">Bitte geben Sie Ihren Benutzername ein und klicken auf "Passwort zurücksetzen".<br>Über Ihren Administrator bekommen Sie dann die neue Zugangsdaten. </p>
                                        {if isset($page_message)}
                                            <strong>{FORM::info('error', '',$page_message[0]['message'], '','col-sm-12 text-red')}</strong>
                                        {/if}
                                        <form id="form_login" action="index.php?action=login" method="post">
                                            <p><strong>Einloggen</strong></p>
                                          <div class="form-group has-feedback {if isset($page_message)}has-error{/if}">
                                            <input type="text" class="form-control" id="username" name="username" {if isset($username)}value="{$username}"{/if} placeholder="Benutzername">
                                          </div>
                                          <div id="password" class="form-group has-feedback {if isset($page_message)}has-error{/if}">
                                            <input type="password" class="form-control" name="password" placeholder="Passwort">
                                          </div>
                                          <div class="row">
                                             <div class="col-xs-6 pull-left">
                                              <input id="pw_reset" class="btn btn-primary btn-block btn-flat " value="Passwort vergessen" onclick="toggle(['reset', 'reset_info'], ['login', 'password', 'pw_reset']);"></input>
                                            </div><!-- /.col -->
                                            <div class="col-xs-6 pull-right">
                                              <input id="login" type="submit" name="login" class="btn btn-primary btn-block btn-flat visible" value="Einloggen" ></input>
                                              <input id="reset" type="submit" name="reset" class="btn btn-primary btn-block btn-flat hidden" value="Passwort vergessen" ></input>
                                            </div><!-- /.col -->
                                          </div>
                                        <div class="top-buffer">
                                            <p><strong>- ODER -</strong></p>    
                                        {*if $cfg_shibboleth}
                                        
                                          <div class="row">
                                              <div class="col-xs-7 pull-right">
                                              <a href="../share/plugins/auth/shibboleth/index.php" class="btn btn-block btn-flat btn-warning"><img src="assets/images/icons/shibboleth-web.png" style="height:24px;"></img> Mit Shibboleth anmelden</a>
                                              </div>
                                          </div>
                                        {/if*}
                                        </div>
                                        <div class="row top-buffer">
                                            <div class="col-xs-7 pull-left">Noch nicht registriert?</div>
                                            <div class="col-xs-5 pull-right">{*!pull-left to not submit guest login on return, when entering regular user accounts*}
                                                <input id="register" {*type="submit"*} onclick="alert('Funktion noch nicht verfügbar');" name="register" class="btn btn-primary btn-block btn-flat visible" value="jetzt Registrieren" data-toggle="tooltip" title="Noch nicht registriert?"></input>
                                            </div><!-- /.col -->
                                        </div>
                                        <div class="row top-buffer">
                                            <div class="col-xs-7 pull-left">Als Gast einen Einblick bekommen</div>
                                            <div class="col-xs-5 pull-right">{*!pull-left to not submit guest login on return, when entering regular user accounts*}
                                                <input id="guest" type="submit" name="guest" class="btn btn-primary btn-block btn-flat visible" value="Gastzugang nutzen" data-toggle="tooltip" title="Als Gast einen Einblick bekommen"></input>
                                            </div><!-- /.col -->
                                        </div>
                                        </form>        
                                </div>
                                <div class="price-foot">
                                    <div class="">
                                        <b>Version</b> {$app_version}
                                        <br>{$app_footer}
                                    </div>
                                </div>
                        </li>
                    </ul>
                
                </div>
            </div>
        </div>
    </div>    
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
