{extends file="base.tpl"}

{block name=title}Kompetenzraster: {$course[0]->curriculum}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{if isset($showaddObjectives)}{assign var="help" value="http://docs.joachimdieterich.de/index.php?title=Lehrplan_anlegen"}
{else}{assign var="help" value="http://docs.joachimdieterich.de/index.php?title=Lehrplan"}{/if}
{content_header p_title="{$page_title}: {$course[0]->curriculum} ({$course[0]->grade}: {$course[0]->subject})" pages=$breadcrumb help= $help}       

<!-- Main content -->
<div class="documents" >   
    <div class="row">
        <div class="col-xs-12 bottom-buffer-20" >
            <!--Search -->
            <div class="pull-right">
                <div class="has-feedback">
                    <form id="view_search" method="post" action="../share/processors/fp_search.php">
                    <input type="hidden" name="func" id="func" value="view_highlight"/>
                    <input type="hidden" name="id" id="id" value="{$course[0]->curriculum_id}"/>
                    <input type="text" name="search" class="form-control input-sm" placeholder="Lehrplan durchsuchen">
                    <span class="fa fa-search form-control-feedback"></span>
                    </form>
                </div>
            </div>
            <div class="btn-group pull-left margin-r-5">
                <button type="button" class="btn btn-default" onclick="formloader('description','curriculum',{$course[0]->curriculum_id});">
                    <i class="fa fa-info"></i>
                </button>
                {Render::split_button($cur_content)}
                {if isset($showaddObjectives)}
                    <button type="button" class="btn btn-default" onclick='formloader("content", "new", null,{["context_id"=>"2", "reference_id"=>$course[0]->curriculum_id]|@json_encode nofilter});'>
                        <i class="fa fa-plus"></i>
                    </button>
                {/if}
            </div>
            {if isset($niveaus)}
                <div class="btn-group pull-left">
                {foreach name=foreach_niveau item=niveau from=$niveaus} 
                    <button type="button" class="btn btn-default"><a href="index.php?action=view&curriculum_id={$niveau->curriculum_id}{if !isset($showaddObjectives)}&group={$page_group}{/if}">{$niveau->name}</a></button>
                {/foreach}
                </div>
             {/if}
        </div>
        
        <div class="col-xs-12" >
            {if $terminal_objectives != false}
                {assign var="sol_btn" value="false"}  
                {*Thema Row*}
                {foreach name=foreach_ter key=terid item=ter from=$terminal_objectives}   
                <div class="row" >
                    <div class="col-xs-12"> 
                         {*Thema Row*}
                        <div class="box box-objective {if isset($highlight)}{if in_array("ter_`$ter->id`", $highlight)} highlight {/if}{/if}" style="background: {$ter->color}"> 
                            <div class="boxheader" >
                                {if isset($showaddObjectives)}
                                    <span class="fa fa-minus pull-right box-sm-icon text-primary" onclick="del('terminal_objectives', {$ter->id});"></span>
                                    <span class="fa fa-edit pull-right box-sm-icon text-primary" onclick="formloader('terminal_objective','edit', {$ter->id});"></span>
                                    {if $ter->order_id neq '1'}
                                        <span class="fa fa-arrow-up pull-left box-sm-icon text-primary" onclick='processor("orderObjective", "terminal_objective", "{$ter->id}", {["order" =>"down"]|@json_encode nofilter});'></span>
                                    {/if}
                                    {if not $smarty.foreach.foreach_ter.last}
                                        <span class="fa fa-arrow-down pull-left box-sm-icon text-primary" onclick='processor("orderObjective", "terminal_objective", "{$ter->id}", {["order" =>"up"]|@json_encode nofilter});'></span>
                                    {/if}
                                {/if}
                                
                            </div>
                            <div id="ter_{$ter->id}" class="panel-body boxwrap" >
                                <div class="boxscroll" style="background: {$ter->color}">
                                    <div class="boxcontent text-white">
                                        {$ter->terminal_objective}
                                    </div>
                                </div>
                            </div>
                            <div class="boxfooter" style="background: {$ter->color}">
                                {if $ter->description neq ''}
                                    <span class="fa fa-info pull-right box-sm-icon text-primary" style="padding-top:2px; margin-right:3px;" data-toggle="tooltip" title="Beschreibung" onclick="formloader('description', 'terminal_objective', '{$ter->id}');"></span>
                                {/if}
                                {if isset($showaddObjectives)}
                                    {if checkCapabilities('file:upload', $my_role_id, false)}
                                        <a href="../share/templates/Bootflat-2.0.4/renderer/uploadframe.php?context=terminal_objective&ref_id={$ter->id}{$tb_param}" class="nyroModal"><span class="fa fa-plus pull-right box-sm-icon"></span></a>                        
                                    {/if} 
                                {/if}
                                {if checkCapabilities('file:loadMaterial', $my_role_id, false) AND $ter->files neq '0'}
                                    <span class="fa fa-briefcase box-sm-icon text-primary" style="cursor:pointer;" data-toggle="tooltip" title="{$ter->files} Materialien verfügbar" onclick="formloader('material','terminal_objective',{$ter->id})"></span> {*<span class="badge label-primary" style="margin-top: -3px;font-size: 8px;line-height: .8"  data-toggle="tooltip" title="Material">{$ter->files}</span>*}
                                {else}
                                    <span class="fa fa-briefcase box-sm-icon deactivate"></span>
                                {/if}
                            </div>
                          </div> 
                    {*Ende Thema*}

                    {*Ziele*}
                    {if $enabledObjectives != false}
                        {foreach key=enaid item=ena from=$enabledObjectives}
                        {if $ena->terminal_objective_id eq $ter->id}
                            <div style="display:none" id="ena_status_{$ena->id}">{0+$ena->accomplished_status_id}</div><!--Container für Variable-->
                            <div id="ena_{$ena->id}" class="box box-objective bg-white {if isset($highlight)}{if in_array("ena_`$ena->id`", $highlight)} highlight {/if}{/if}"> 
                                <div id="ena_header_{$ena->id}"class="boxheader {$box_bg[$ena->accomplished_status_id]}" >
                                    {if checkCapabilities('groups:showAccomplished', $my_role_id, false)}
                                        {if isset($ena->accomplished_users) and isset($ena->enroled_users) and isset($ena->accomplished_percent)}
                                            <span class=" pull-left hidden-sm hidden-xs" data-toggle="tooltip" title="Stand der Lerngruppe">{$ena->accomplished_users} von {$ena->enroled_users} ({$ena->accomplished_percent}%)</span><!--Ziel-->  
                                        {/if}
                                    {/if}
                                    {if !isset($showaddObjectives) AND checkCapabilities('user:getHelp', $my_role_id, false)}
                                        <span class="fa fa-support pull-right box-sm-icon text-primary"  data-toggle="tooltip" title="Gruppenmitglied kontaktieren" onclick='formloader("support","random", {$ena->id},{["group_id"=>$page_group]|@json_encode nofilter});'></span>
                                    {/if} 
                                    {if !isset($showaddObjectives) AND checkCapabilities('file:solutionUpload', $my_role_id, false)}
                                        {foreach item=s from=$solutions}
                                            {if $my_id eq $s->creator_id AND $s->enabling_objective_id eq $ena->id AND $sol_btn neq $ena->id} 
                                                {assign var="sol_btn" value=$ena->id}
                                                {break}
                                            {/if}
                                        {/foreach}
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="../share/templates/Bootflat-2.0.4/renderer/uploadframe.php?context=solution&ref_id={$ena->id}{$tb_param}" class="nyroModal">
                                            <span class="fa {if $sol_btn eq $ena->id OR $sol_btn eq false}fa-check-circle-o{else}fa-upload{/if} pull-right box-sm-icon text-primary" data-toggle="tooltip" {if $sol_btn eq $ena->id OR $sol_btn eq false}title="Lösung eingereicht"{else}title="Lösung einreichen"{/if}></span></a>
                                        {/if}  
                                    {/if}
                                    {if isset($showaddObjectives)}
                                         {if isset($enabledObjectives[{$enaid+1}])} 
                                             {if $ena->terminal_objective_id eq $enabledObjectives[{$enaid+1}]->terminal_objective_id}
                                                 <span class="fa fa-arrow-right pull-right box-sm-icon text-primary" onclick='processor("orderObjective", "enabling_objective", "{$ena->id}", {["order" =>"up"]|@json_encode nofilter});'></span>
                                             {/if}
                                         {/if}
                                         <span class="fa fa-minus pull-right box-sm-icon text-primary" onclick="del('enablingObjectives', {$ena->id});"></span>
                                         <span class="fa fa-edit pull-right box-sm-icon text-primary" onclick="formloader('enablingObjective','edit', {$ena->id});"></span>
                                         {if $ena->order_id neq '1'}
                                             <span class="fa fa-arrow-left pull-left box-sm-icon text-primary" onclick='processor("orderObjective", "enabling_objective", "{$ena->id}", {["order" =>"down"]|@json_encode nofilter});'></span>
                                         {/if}
                                     {/if}
                                </div>
                                <div {*id="ena_{$ena->id}"*} class="panel-body boxwrap">
                                    <div class="boxscroll">
                                        <div class="boxcontent">
                                            {$ena->enabling_objective}
                                        </div>
                                    </div>
                                </div>
                                <div class="boxfooter">
                                    {if $ter->description neq '' AND $ena->description neq ''}
                                        <span class="fa fa-info pull-right box-sm-icon text-primary" style="padding-top:2px; margin-right:3px;" data-toggle="tooltip" title="Beschreibung" onclick="formloader('description', 'enabling_objective','{$ena->id}');"></span>
                                    {/if}
                                    {if isset($showaddObjectives)}
                                        <span class="fa fa-check-square-o pull-right box-sm-icon text-primary" onclick="formloader('addQuiz','enabling_objective','{$ena->id}');"></span>
                                        {if checkCapabilities('file:upload', $my_role_id, false)}
                                            <a href="../share/templates/Bootflat-2.0.4/renderer/uploadframe.php?context=enabling_objective&ref_id={$ena->id}{$tb_param}" class="nyroModal">
                                             <span class="fa fa-plus pull-right box-sm-icon" ></span></a>
                                        {/if}
                                    {else}
                                        {if checkCapabilities('quiz:showQuiz', $my_role_id, false) AND $ena->quiz neq '0'}
                                            <span class="fa fa-check-square-o pull-right box-sm-icon text-primary" onclick="formloader('quiz','enabling_objective','{$ena->id}');"></span>
                                        {/if}
                                    {/if}  
                                    <span class="pull-left" style="margin-right:10px;">
                                    {if checkCapabilities('file:loadMaterial', $my_role_id, false) AND $ena->files neq '0'}
                                        <i class="fa fa-briefcase box-sm-icon text-primary " style="cursor:pointer;" data-toggle="tooltip" title="{$ena->files} Materialien verfügbar" onclick="formloader('material','enabling_objective', {$ena->id});"></i> {*<span class="badge label-primary" style="margin-top: -3px;font-size: 8px;line-height: .8"  data-toggle="tooltip" title="Material">{$ena->files}</span>*}
                                    {else}
                                        <span class="fa fa-briefcase box-sm-icon deactivate"></span>
                                    {/if} 
                                    </span>
                                    {if checkCapabilities('course:selfAssessment', $my_role_id, false) AND !isset($showaddObjectives)}
                                        <span class="pull-left">{Render::accCheckboxes( {['id' => $ena->id, 'student' => $my_id, 'teacher' => $my_id, 'link' => false]|@json_encode nofilter})}</span>
                                    {/if}
                                </div>
                            </div>    
                        {/if}
                        {/foreach}
                    {/if}

                    {if isset($showaddObjectives)}  
                        <div class="box box-objective bg-white"> 
                            <span style="position:absolute; top:20px; width:100%;text-align: center;"><h5 class="text-primary">Ziel</h5></span>
                            <div class="panel-body text-primary" style="text-align: center; font-size:100px;" onclick="formloader('enablingObjective','new', {$ter->id});">+</div>
                        </div>
                    {/if}
                    </div> <!-- /.col -->
                </div><!-- .row-->
                <div class="hidden-lg hidden-md"><br/></div>
                {/foreach}
            {/if}
            {* Neues Thema *}        
            {if isset($showaddObjectives)}  
                <div class="box box-objective bg-white"> 
                    <span style="position:absolute; top:20px; width:100%;text-align: center;"><h5 class="text-primary">Thema</h5></span>
                    <div class="panel-body text-primary" style="text-align: center; font-size:100px;" onclick="formloader('terminal_objective','new', {$course[0]->curriculum_id});">+</div>
                </div>
            {/if}        
        </div>
    </div>
</div>
        
 <!--jump to actual row-->      
{if isset($smarty.session.anchor)}
    <script type="text/javascript"> window.location.hash="{$smarty.session.anchor}"; </script>            
{/if}

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}