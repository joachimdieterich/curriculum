{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
{if isset($userPaginator)} 
    <script type="text/javascript" > 
        $(document).ready(
                resizeBlocks('row_objectives_userlist', ['coursebook'])
        );
    </script>
{/if} 
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Lernstand'}   

<!-- Main content -->
<section class="content">
    <div class="row ">
        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#f_userlist" data-toggle="tab">Kursliste</a></li>
                    {if isset($coursebook)} 
                        <li><a href="#f_coursebook" data-toggle="tab">Kursbuch</a></li>
                    {/if}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="f_userlist">
                        {if isset($courses)}
                            <form method='post' action='index.php?action=objectives&course={$selected_curriculum_id}{*&userID={implode(',',$selected_user_id)}&next={$currentUrlId}*}'>        
                                <div class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            {Form::input_select('course', '', $courses, 'group, curriculum', 'id', $selected_curriculum_id, null, "window.location.assign('index.php?action=objectives&reset=true&course='+this.value);", 'Kurs / Klasse wählen...', '', 'col-sm-12')}
                                        </div>
                                        {*if $show_course != '' and $terminalObjectives != false*}{*Zertifikat*}
                                        {if isset($certificate_templates)}{*Zertifikat*}
                                            <div class="col-md-4 col-sm-12">
                                                {Form::input_select('certificate_template', '', $certificate_templates, 'certificate, description', 'id', $selected_certificate_template, null, 'float-left', 'Zertifikatvorlage wählen...', '', 'col-sm-12')}   
                                            </div>
                                            <input type='hidden' name='sel_curriculum' value='{$sel_curriculum}'/>
                                            <input type='hidden' name='sel_group_id' value='{$sel_group_id}'/>
                                            <div class="col-md-4 col-sm-12">
                                                <button type='submit' name='printCertificate' value='' class='btn btn-default'>
                                                    <span class="fa fa-files-o" aria-hidden="true"></span> {if count($selected_user_id) > 1} Zertifikate erstellen{else} Zertifikat erstellen{/if}
                                                </button>
                                            </div>
                                        {else}<input id="certificate_template" class="hidden" value="false"/>{* hack to get js working if no user is selected *}{/if}
                                    </div>
                                </div>
                            </form>
                        {else}<strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong>
                        {/if}
                        {if isset($userPaginator)}   
                            <p> Bitte  Schüler aus der Liste auswählen um den Lernstand einzugeben.</p>
                                    {html_paginator id='userPaginator' title='Kurs'} 
                        {elseif $showuser eq true}Keine eingeschriebenen Benutzer{/if}
                    </div>
                    {if isset($coursebook)} 
                    <div class="tab-pane" id="f_coursebook">
                        {Render::courseBook($coursebook)}
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    
    {if isset($userPaginator)} 
    <div class="row ">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header ">
                    {if isset($user->avatar)}
                        {*if $user->avatar_id neq 0*}
                        {Render::split_button($cur_content)}
                        <img src="{$access_file}{$user->avatar}" style="height:40px;"class="user-image pull-left margin-r-5" alt="User Image">
                        {*/if*}
                    {/if}
                    <p class="pull-right">Farb-Legende:
                    <button class="btn btn-success btn-flat" style="cursor:default">selbständig erreicht</button>
                    <button class="btn btn-warning btn-flat" style="cursor:default">mit Hilfe erreicht</button>
                    <button class="btn btn-default disabled btn-flat" style="cursor:default">nicht bearbeitet</button>
                    <button class="btn btn-danger btn-flat" style="cursor:default">nicht erreicht</button>
                    </p>
                </div>
                <div class="box-body">
        
                {if $show_course != '' and isset($terminalObjectives)} 
                    {foreach key=terid item=ter from=$terminalObjectives}
                        <div class="row" >
                            <div class="col-xs-12"> 
                                {*Thema Row*}
                                {RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $selected_user_id,"group_id" => $sel_group_id])}
                                {*Ende Thema*}

                                {*Anfang Ziel*}
                                {foreach key=enaid item=ena from=$enabledObjectives}
                                    {if $ena->terminal_objective_id eq $ter->id}
                                        {RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $selected_user_id, "group_id" => $sel_group_id, "border_color" => $ter->color])}
                                    {/if}
                                {/foreach}
                                {*Ende Ziel*}
                            </div>
                        </div>
                    {/foreach}		
                {else}
                    {if isset($selected_user_id) and $show_course != ''}
                        <p>Es wurden noch keine Lernziele eingegeben.</p>
                        <p>Dies können sie unter Lehrpläne --> Lernziele/Kompetenzen hinzufügen machen.</p>
                    {else} 
                        {if isset($curriculum_id)}<!--Wenn noch keine Lehrpläne angelegt wurden-->
                        <p>Bitte wählen sie einen Benutzer aus.</p>
                        {/if}            
                    {/if}
                {/if} 
                </div>
            </div>
        </div>
    </div>
    {/if}
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}