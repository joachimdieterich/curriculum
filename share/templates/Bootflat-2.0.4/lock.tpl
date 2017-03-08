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
                        <li class="unit price-primary active" style="min-width: 200px !important;">
                                <div class="price-title">
                                        <img src="assets/images/favicon/apple-touch-icon-57x57.png"></img> <br>
                                        <b>{$app_title}</b>
                                </div>
                                <div class="price-body">
                                    <img class="img-circle img-bordered-sm" style="height:50px;width:50px;" src="{$access_file}{$my_avatar}" alt="User Image">
                                    <div class="lockscreen-name"><strong>{$my_firstname} {$my_lastname}</strong></div>
                                    {if isset($page_message)}
                                        <strong>{FORM::info('error', '',$page_message[0]['message'], '','col-sm-12 text-red')}</strong>
                                    {/if}
                                    <form class="lockscreen-credentials" action="index.php?action=lock" method="post" >
                                        <center><div class="input-group" style="width:300px;" >
                                            <input type="password" class="form-control " name="password" placeholder="password">
                                            <div class="input-group-btn">
                                              <button type="button" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
                                            </div>
                                          </div>
                                        </center>
                                     </form>         
                                       <div class="help-block text-center">
                                        Geben Sie Ihr Kennwort ein, um Ihre Sitzung abzurufen.
                                      </div>
                                      <div class="text-center">
                                        <a href="index.php?action=login">Benutzer wechseln...</a>
                                      </div>
                                </div>
                                <div class="price-foot">
                                        <b>Version</b> {$app_version}
                                        <br>{$app_footer}
                                </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>     
  
  
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
