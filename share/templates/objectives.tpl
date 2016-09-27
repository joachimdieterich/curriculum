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
        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12" >
            <div class="box box-primary" id="row_objectives_userlist">
                <div class="box-header with-border">
                    <!--div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button-->
                    <!--/div-->
                
                    {if isset($courses)}
                    <select  class='pull-left' id='course' name='course' onchange="window.location.assign('index.php?action=objectives&course='+this.value);"> {*_blank global regeln*}
                        <option value="-1" data-skip="1">Kurs / Klasse wählen...</option>
                        {section name=res loop=$courses}
                            {if $courses[res]->semester_id eq $my_semester_id}
                              <option value="{$courses[res]->id}" 
                              {if $courses[res]->id eq $selected_curriculum} selected {/if} 
                              data-icon="{$subjects_path}/{$courses[res]->icon}" data-html-text="{$courses[res]->group} - {$courses[res]->curriculum}&lt;i&gt;
                              {$courses[res]->description}&lt;/i&gt;">{$courses[res]->group} - {$courses[res]->curriculum}</option>  
                            {/if}
                        {/section} 
                    </select> 
                    {if $show_course != '' and $terminalObjectives != false or !isset($selected_user_id)}{*Zertifikat*}
                    <form method='post' action='index.php?action=objectives&course={$selected_curriculum}&userID={implode(',',$selected_user_id)}&next={$currentUrlId}'>
                    <select class='pull-left' id='certificate_template' name='certificate_template' onchange=""> 
                        <option value="-1" data-skip="1">Zertifikatvorlage wählen...</option>
                        {section name=res loop=$certificate_templates}
                            <option value="{$certificate_templates[res]->id}" 
                                {if $certificate_templates[res]->id eq $selected_certificate_template} selected {/if}>
                                {$certificate_templates[res]->certificate} - {$certificate_templates[res]->description}
                            </option>  
                        {/section} 
                    </select>    
                        <input type='hidden' name='sel_curriculum' value='{$sel_curriculum}'/>
                        <input type='hidden' name='sel_user_id' value='{implode(',',$selected_user_id)}'/>
                        <input type='hidden' name='sel_group_id' value='{$sel_group_id}'/>
                        <input class='menusubmit space-left' type='submit' name="printCertificate" value={if count($selected_user_id) > 1}'Zertifikate erstellen'{else} 'Zertifikat erstellen'{/if} /> 
                    </form>
                    {else}
                        <select class='hidden pull-left space-left' id='certificate_template' name='certificate_template' onchange=""> {*hack, damit bei checkrow die Auswahl erhalten bleibt bzw. keine Fehler entstehen*}
                            <option value="-1" data-skip="1">Zertifikatvorlage wählen...</option>
                        </select>
                    {/if}
                {else}<strong>Sie haben noch keine Lehrpläne angelegt bzw. noch keine Klassen eingeschrieben.</strong>
                {/if}

                {if isset($user->avatar)}
                                <!--img class="pull-right attachment-img" src="{$access_file}{$user->avatar}" alt="Profilfoto"-->
                            {/if}    
                </div>
                
                <div class="box-body">
                {if isset($userPaginator)}   
                    <p> Bitte  Schüler aus der Liste auswählen um den Lernstand einzugeben.</p>
                            {html_paginator id='userPaginator'} 
                {elseif $showuser eq true}Keine eingeschriebenen Benutzer{/if}
                </div>
            </div>
        </div>
        {if isset($userPaginator)} 
        <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
            {Render::courseBook($coursebook)}    
        </div>
        {/if}
    </div>
    
    {if isset($userPaginator)} 
    <div class="row ">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header ">
                    
                    <p class="pull-right">Farb-Legende:
                    <button class="btn btn-success btn-flat" style="cursor:default">selbständig erreicht</button>
                    <button class="btn btn-warning btn-flat" style="cursor:default">mit Hilfe erreicht</button>
                    <button class="btn btn-default disabled btn-flat" style="cursor:default">nicht bearbeitet</button>
                    <button class="btn btn-danger btn-flat" style="cursor:default">nicht erreicht</button>
                    </p>
                </div>
                <div class="box-body">
        
        {if $show_course != '' and $terminalObjectives != false or !isset($selected_user_id)} 
            {foreach key=terid item=ter from=$terminalObjectives}
                <div class="row" >
                    <div class="col-xs-12"> 
                        {*Thema Row*}
                        <div class="panel panel-default box-objective"> 
                            <div class="panel-heading boxheader" style="background: {$ter->color}"></div>
                            <div id="ter_{$ter->id}" class="panel-body bg-gray disabled color-palette boxwrap">
                                <div class="boxscroll">
                                    <div class="boxcontent">
                                        {$ter->terminal_objective}
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer boxfooter">
                                <a onclick="formloader('description', 'terminal_objective', '{$ter->id}');"><span class="fa fa-info pull-right box-sm-icon"></span></a>
                            </div>
                        </div> 
                        {*Ende Thema*}
                        
                        {*Anfang Ziel*}
                        {foreach key=enaid item=ena from=$enabledObjectives}
                            {if $ena->terminal_objective_id eq $ter->id}
                                <div style="display:none" id="ena_status_{$ena->id}">{0+$ena->accomplished_status_id}</div><!--Container für Variable-->
                                <div id="ena_{$ena->id}" class="panel panel-default box-objective {if $ena->accomplished_status_id eq 1} boxgreen {elseif $ena->accomplished_status_id eq 2} boxorange {elseif $ena->accomplished_status_id eq '0'} boxred {/if}"> 
                                    <div class="panel-heading boxheader" style="background: {$ter->color}">
                                        {if isset($ena->accomplished_users) and isset($ena->enroled_users) and isset($ena->accomplished_percent)}
                                            {$ena->accomplished_users} von {$ena->enroled_users} ({$ena->accomplished_percent}%)<!--Ziel--> 
                                        {/if}
                                        <a onclick='formloader("compare","group", {$ena->id},{["group_id"=>$sel_group_id]|@json_encode nofilter});'><span class="fa  fa-bar-chart-o pull-right invert box-sm-icon"></span></a>
                                    </div>
                                    <div class="panel-body boxwrap" onclick="setAccomplishedObjectives({$my_id}, '{implode(',',$selected_user_id)}', {$ena->id}, {$sel_group_id});">
                                        <div class="boxscroll">
                                                <div class="boxcontent">
                                                    {$ena->enabling_objective}
                                                </div>
                                            </div>
                                    </div>
                                    <div class="panel-footer boxfooter">
                                        <span class=" pull-left">{Render::accCheckboxes($ena->id, implode(',',$selected_user_id), $my_id, false)}</span>
                                        <a onclick="formloader('description', 'enabling_objective', '{$ena->id}');"><span class=" fa fa-info pull-right box-sm-icon"></span></a>
                                    </div>
                                </div> 
                    {/if}
                    {/foreach} 
               </tr>
            {/foreach}		
            </table>
            </div>   
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
 </div>
        {/if}
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}