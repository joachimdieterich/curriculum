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
<section class="content" >   
    <div class="row ">
        <div class="col-xs-12" >
            <div class="pull-right">
                <div class="has-feedback">
                    <form id="view_search" method="post" action="../share/processors/fp_search.php">
                    <input type="hidden" name="func" id="func" value="view_highlight"/>
                    <input type="hidden" name="id" id="id" value="{$course[0]->curriculum_id}"/>
                    <input type="text" name="search" class="form-control input-sm" placeholder="Lehrplan durchsuchen">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
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
        </div>
        <div class="col-xs-12 top-buffer" >
         {if $terminal_objectives != false}
             {assign var="sol_btn" value="false"}  
             {*Thema Row*}
             {foreach name=foreach_ter key=terid item=ter from=$terminal_objectives}   
             <div class="row" >
                 <div class="col-xs-12"> 
                     {*Thema Row*}
                     {if isset($showaddObjectives)}
                         {assign var="orderup" value=false}
                         {if isset($terminal_objectives[{$terid+1}])}
                             {if $terminal_objectives[{$terid+1}]->curriculum_id eq $ter->curriculum_id}
                                 {assign var="orderup" value=true} 
                             {/if}
                         {/if}    
                         {assign var="orderdown" value=false}
                         {if $ter->order_id neq '1'}
                             {assign var="orderdown" value=true}
                         {/if}
                         {RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $my_id, "edit" => true, "orderup" => $orderup, "orderdown" => $orderdown])}
                     {else}
                         {RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $my_id])}
                     {/if}
                     {*Ende Thema*}

                     {*Ziele*}
                     {if $enabledObjectives != false}
                         {foreach key=enaid item=ena from=$enabledObjectives}
                         {if $ena->terminal_objective_id eq $ter->id}
                             {if isset($showaddObjectives)}
                                 {assign var="orderup" value=false}
                                 {if isset($enabledObjectives[{$enaid+1}])}
                                     {if $enabledObjectives[{$enaid+1}]->terminal_objective_id eq $ena->terminal_objective_id}
                                         {assign var="orderup" value=true} 
                                     {/if}
                                 {/if}    
                                 {assign var="orderdown" value=false}
                                 {if $ena->order_id neq '1'}
                                     {assign var="orderdown" value=true}
                                 {/if}
                                 {RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $my_id, "solutions" => $solutions, "edit" => true, "orderup" => $orderup, "orderdown" => $orderdown, "border_color" => $ter->color])}
                             {else}
                                 {RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $my_id, "solutions" => $solutions, "group_id" => $page_group, "border_color" => $ter->color])}
                             {/if}
                         {/if}
                         {/foreach}
                     {/if}

                 {if isset($showaddObjectives)}  
                     <div class="box box-objective bg-white"> 
                         <span style="position:absolute; top:20px; width:100%;text-align: center;"><h5 class="text-primary">Ziel</h5></span>
                         <div class="text-primary" style="text-align: center; padding: 25px; font-size:100px;" onclick="formloader('enabling_objective','new', {$ter->id});">+</div>
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
                 <div class="text-primary" style="text-align: center; padding: 25px; font-size:100px;" onclick="formloader('terminal_objective','new', {$course[0]->curriculum_id});">+</div>
             </div>
         {/if}        
        </div>            
    </div>
</section>
        
 <!--jump to actual row-->      
{if isset($smarty.session.anchor)}
    <script type="text/javascript"> window.location.hash="{$smarty.session.anchor}"; </script>            
{/if}

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}