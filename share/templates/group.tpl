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
                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Lerngruppe hinzufügen</a>
                        </button>
                    </div>	
                {/if}
                <form id='classlist' method='post' action='index.php?action=group&next={$currentUrlId}'>
                {html_paginator id='groupP'}
                    {if checkCapabilities('groups:enrol', $my_role_id, false) OR checkCapabilities('groups:expel', $my_role_id, false)}
                        {if isset($curriculum_list)}
                        <p><h4>Markierte Lerngruppe(n)in Lehrplan ein- und ausschreiben:</h4>
                        <select class='floatleft' name="curriculum">
                                {section name=res loop=$curriculum_list}  
                                    <option label="{$curriculum_list[res]->curriculum} | {$curriculum_list[res]->grade} | {$curriculum_list[res]->description}" value="{$curriculum_list[res]->id}">{$curriculum_list[res]->curriculum} | {$curriculum_list[res]->grade} | {$curriculum_list[res]->description}</option>
                                {/section}
                            </select> 
                        <div class="btn-group" role="group" aria-label="...">
                            {if checkCapabilities('groups:enrol', $my_role_id, false)}
                                <button type="submit" class="btn btn-default" onclick="document.getElementById('enrol').click();"><a href="#">
                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> einschreibenn</a>
                                </button>
                                <input class="invisible" type='submit' id='enrol' name='enrol' value='einschreiben' />
                            {/if}
                            {if checkCapabilities('groups:expel', $my_role_id, false)}
                                <button id="expel" name="expel" type="submit" class="btn btn-default" onclick="document.getElementById('expel').click();"><a href="#">
                                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> ausschreiben</a>
                                </button>
                                <input class="invisible" type='submit' id='expel' name='expel' value='ausschreiben' />
                            {/if}
                        </div>
                        {/if}
                     {/if}   
                </form> 
                {if isset($cu_val)}
                    {html_paginator id='curriculumP' action="index.php?action=groups&next={$currentUrlId}"}
                {/if}
                </div>
            </div>
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}