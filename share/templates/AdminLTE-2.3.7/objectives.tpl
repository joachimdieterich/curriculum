{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
{if isset($userPaginator)} 
    {literal}
    <script type="text/javascript" > 
        $(document).ready(
                resizeBlocks('row_objectives_userlist', ['coursebook'])
        );
        $(document).ready(function () {
            small       = false;
            if ($('#tab_userlist').hasClass('active')){
                floating_table('body-wrapper', 'userPaginator', ['username', 'role_name', 'completed', 'online'], 'menu_top_placeholder', 'container_userPaginator', 'default_userPaginator_position');
            }
        });
    </script>
    
    {/literal}
{/if} 
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/documentation/benutzerhandbuch/lernstand'}   

<!-- Main content, id section_content used to reload content with ajax-->
<section id="section_content" class="content">
    <div id="row_content" class="row">
        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li id="nav_tab_userlist" {if isset($tab_userlist)}class="active"{/if}><a href="#tab_userlist" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"tab_userlist", "reload"=>"false"]|@json_encode nofilter});'>Kursliste</a></li>
                    {if isset($coursebook) AND checkCapabilities('menu:readCourseBook', $my_role_id, false)}
                        <li id="nav_tab_coursebook" {if isset($tab_coursebook)}class="active"{/if}><a href="#tab_coursebook" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"tab_coursebook", "reload"=>"false"]|@json_encode nofilter});'>Kursbuch</a></li>
                    {/if}
                </ul>
                <div class="tab-content">
                    <div id="tab_userlist" class="tab-pane {if isset($tab_userlist)}active{/if}">
                        {if isset($courses)}
                            <form method='post' action='index.php?action=objectives&course={$selected_curriculum_id}'>        
                                <div class="form-horizontal">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12" style="margin-left:15px;">
                                            {Form::input_select('course', '', $courses, 'group, curriculum', 'id', $selected_curriculum_id, null, "window.location.assign('index.php?action=objectives&course='+this.value);", 'Kurs / Klasse wählen...', '', 'col-sm-12')}
                                        </div>
                                        {*Zertifikat*}
                                        <div class="col-md-2 col-sm-12">
                                            <div class='btn btn-default' onclick="formloader('generate_certificate','',{$sel_curriculum});">
                                                <span class="fa fa-files-o" aria-hidden="true"></span> {if count($selected_user_id) > 1} Zertifikate/Gruppen-Übersicht {else} Zertifikat/Gruppen-Übersicht {/if}erstellen
                                            </div>
                                        </div>
                                        <input id="certificate_template" class="hidden" value="false"/>{* hack to get js working if no user is selected, todo: remve certificate_template in js not used any more *}
                                    </div>
                                </div>
                            </form>
                        {else}<strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong>
                        {/if}
                        {if isset($userPaginator)}   
                            <p> Bitte  Schüler aus der Liste auswählen um den Lernstand einzugeben.</p>
                            <div id="default_userPaginator_position" >
                                    {html_paginator id='userPaginator' title='Kurs'} 
                            </div>
                        {elseif $showuser eq true}Keine eingeschriebenen Benutzer{/if}
                    </div>
                    {if isset($coursebook)} 
                    <div id="tab_coursebook" class="tab-pane {if isset($tab_coursebook)}active{/if}">
                        {Render::courseBook($coursebook)}
                    </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    
    {*if isset($userPaginator)*}
    
    <div id="curriculum_content">
        {if isset($selected_user_id)}
            {Render::curriculum(['show_course' => $show_course, 
                             'terminalObjectives' => $terminalObjectives,
                             'enabledObjectives' => $enabledObjectives,
                             'user' => $user,
                             'sel_curriculum' => $sel_curriculum,
                             'selected_user_id' => $selected_user_id,
                             'sel_group_id' => $sel_group_id,
                             'cur_content' => $cur_content
                            ])}
        {/if}
    </div>
    
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
