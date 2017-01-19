{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Lerngruppen_anlegen'}  
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                {if checkCapabilities('groups:add', $my_role_id, false)}    
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="button" class="btn btn-default" onclick="formloader('group','new');"><a  href="#">
                                <span class="fa fa-plus-circle" aria-hidden="true"></span> Lerngruppe hinzufügen</a>
                        </button>
                    </div>	
                {/if}
                <form id='classlist' method='post' action='index.php?action=group&next={$currentUrlId}'>
                {html_paginator id='groupP' title='Lerngruppen'}
                    {if checkCapabilities('groups:enrol', $my_role_id, false) OR checkCapabilities('groups:expel', $my_role_id, false)}
                        {if isset($curriculum_list)}
                        <div class="form-horizontal col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                    <h4>Lerngruppe</h4>
                                    <p>Markierte Lerngruppe(n)in Lehrplan ein- und ausschreiben</p>     
                        {Form::input_select_multiple(['id' => 'curriculum', 'label' => 'Lehrplan', 'select_data' => $curriculum_list, 'select_label' => 'curriculum', 'select_value' => 'id', 'input' => null, 'error' => null, 'limiter' => ' | ' ])}
                        <div class="btn-group" role="group" aria-label="...">
                            {if checkCapabilities('groups:enrol', $my_role_id, false)}
                                <button type="submit" class="btn btn-default" onclick="document.getElementById('enrol').click();"><a href="#">
                                    <span class="fa fa-plus-circle" aria-hidden="true"></span> einschreiben</a>
                                </button>
                                <input class="invisible" type='submit' id='enrol' name='enrol' value='einschreiben' />
                            {/if}
                            {if checkCapabilities('groups:expel', $my_role_id, false)}
                                <button id="expel" name="expel" type="submit" class="btn btn-default" onclick="document.getElementById('expel').click();"><a href="#">
                                    <span class="fa fa-plus-circle" aria-hidden="true"></span> ausschreiben</a>
                                </button>
                                <input class="invisible" type='submit' id='expel' name='expel' value='ausschreiben' />
                            {/if}
                        </div>
                        {/if}
                     {/if}   
                </form> 
                </div>
            </div>
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}