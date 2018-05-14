{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Kinder'}   

<!-- Main content, id section_content used to reload content with ajax-->
<section id="section_content" class="content">
    <div id="row_content" class="row">
        <div class="col-sm-12">
            <div>
                {if isset($courses)}
                    <form method='post' action='index.php?action=children&course={$selected_curriculum_id}'>        
                        <div class="form-horizontal">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    {Form::input_select('course', '', $courses, 'curriculum', 'id', $selected_curriculum_id, null, "window.location.assign('index.php?action=children&curriculum_id='+this.value);", 'Lehrplan wählen...', '', 'col-sm-12')}
                                </div>
                            </div>
                        </div>
                    </form>
                {else}<strong>Ihr Kind ist in keine Lehrpläne eingeschrieben</strong>
                {/if}        
            </div>
        </div>
    </div>
    
    <div>
    <div id="curriculum_content" class="row ">
    {if isset($terminalObjectives)}    
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header ">
                    {if isset($user->avatar)}
                        {*if $user->avatar_id neq 0*}
                        <img src="{$access_file}{$user->avatar}" style="height:40px;"class="user-image pull-left margin-r-5" alt="User Image">
                        {*/if*}
                    {/if}
                    {Render::badge_preview(["reference_id" => $selected_curriculum_id, "user_id" => $selected_user_id])}
                    <p class="pull-right">Farb-Legende:
                    <button class="btn btn-success btn-flat" style="cursor:default">selbständig erreicht</button>
                    <button class="btn btn-warning btn-flat" style="cursor:default">mit Hilfe erreicht</button>
                    <button class="btn btn-default disabled btn-flat" style="cursor:default">nicht bearbeitet</button>
                    <button class="btn btn-danger btn-flat" style="cursor:default">nicht erreicht</button>
                    </p>
                </div>
                <div class="box-body">
        
                {if isset($terminalObjectives)} 
                    {foreach key=terid item=ter from=$terminalObjectives}
                        <div class="row" >
                            <div class="col-xs-12"> 
                                {*Thema Row*}
                                {RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $selected_user_id, "group_id" => ''])}
                                {*Ende Thema*}

                                {*Anfang Ziel*}
                                {foreach key=enaid item=ena from=$enabledObjectives}
                                    {if $ena->terminal_objective_id eq $ter->id}
                                        {RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $selected_user_id, "group_id" => '', "border_color" => $ter->color])}
                                    {/if}
                                {/foreach}
                                {*Ende Ziel*}
                            </div>
                        </div>
                    {/foreach}		
                {/if} 
                </div>
            </div>
        </div>
    {/if}
    </div>
    </div>
    
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}