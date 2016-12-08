{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Lehrplan_anlegen'} 
 
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    {if checkCapabilities('curriculum:add', $my_role_id, false)}
                        <div class="btn-group" role="group" aria-label="..." onclick="formloader('curriculum','new');">
                            <button type="button" class="btn btn-default" >{*<a href="index.php?action=curriculum&function=new">*}
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Lehrplan hinzufügen / importieren
                            </button>
                        </div>
                    {/if}
                    <span class="clearfix"></span>
                        {html_paginator id='curriculumP' title='Lehrpläne'}
                </div>
            </div>
        </div>
    </div>
</section>

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}