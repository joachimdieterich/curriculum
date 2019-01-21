{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/documentation/benutzerhandbuch/faecher-klassenstufen-und-lerngruppen'}  
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <form id='grouplist' method='post' action='index.php?action=group'>
                <div class="box-body">
                    {if checkCapabilities('groups:add', $my_role_id, false)}    
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="button" class="btn btn-default" onclick="formloader('group','new');"><a  href="#">
                               <span class="fa fa-plus-circle" aria-hidden="true"></span> Lerngruppe hinzufügen</a>
                            </button>
                        </div>	
                    {/if}
                    {html_paginator id='groupP' title='Lerngruppen'}
                </div>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        {if checkCapabilities('user:resetPassword', $my_role_id, false)}
                            <li id="nav_tab_enrolments" class="active"><a href="#tab_enrolments" data-toggle="tab">Einschreibungen</a></li>
                        {/if}
                    </ul>
                    <div class="tab-content">
                        {if checkCapabilities('groups:enrol', $my_role_id, false) OR checkCapabilities('groups:expel', $my_role_id, false)}
                            {if isset($curriculum_list)}
                                <div id="tab_enrolments" class="tab-pane active row" >
                                    <div class="form-horizontal col-xs-12">
                                        {Form::info(['id' => 'group_info', 'content' => 'Markierte Lerngruppe(n)in Lehrplan ein- und ausschreiben'])}
                                        {Form::input_select_multiple(['id' => 'curriculum', 'label' => 'Lehrplan', 'select_data' => $curriculum_list, 'select_label' => 'curriculum', 'select_value' => 'id', 'input' => null, 'error' => null, 'limiter' => ' | ' ])}
                                        <div class="btn-group" role="group" aria-label="...">
                                            {if checkCapabilities('groups:enrol', $my_role_id, false)}
                                                {Form::input_button(['id' => 'enrol', 'label' => 'einschreiben', 'icon' => 'fa fa-plus-circle'])}
                                            {/if}
                                            {if checkCapabilities('groups:expel', $my_role_id, false)}
                                                {Form::input_button(['id' => 'expel', 'label' => 'ausschreiben', 'icon' => 'fa fa-minus-circle'])}
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        {/if}   
                    </div>
                </div>
                </form>            
            </div>
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}