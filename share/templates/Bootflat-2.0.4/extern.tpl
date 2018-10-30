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
                                    {if isset($acc_info)}
                                        {$acc_info}
                                    {/if}
                                    <input id="close"  class="btn btn-primary btn-block btn-flat visible" value="Fenster schließen" onclick="self.close();"></input>
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
