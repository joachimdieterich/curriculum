{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/documentation/benutzerhandbuch/Institutionstypen'}      
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-body">
                {if !isset($showForm) && checkCapabilities('subject:add', $my_role_id, false)}
                    <div class="btn-group pull-left" role="group" aria-label="...">
                        {Form::input_button(['id' => 'schooltype', 'label' => 'Schul-/Institutionstyp hinzufügen', 'type' => 'button', 'onclick' => 'formloader(\'schooltype\',\'new\');', 'icon' => 'fa fa-plus-circle'])}
                    </div>
                {/if}
                {html_paginator id='schooltypeP' title='Fächer'}
                </div>
            </div>
        </div>
    </div>
</section>  
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}