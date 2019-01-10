{extends file="base.tpl"}

{block name=title}Kompetenzraster: {$course[0]->curriculum}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
    {if isset($glossar_json)}
        <script src="{$media_url}scripts/glossarizer/jquery.glossarize.min.js"></script>
        <script src="{$media_url}scripts/glossarizer/tooltip/tooltip.min.js"></script>

        <script>
        $(function(){
          $('.boxcontent').glossarizer({
            sourceURL: {$glossar_json},
            callback: function(){
              new tooltip();
            }
          });
        });
        function toggleAll(){
            return (this.tog^=1) ? $('.collapse').collapse('hide') : $('.collapse').collapse('show');
        };
        </script>
    {/if}
    <script type="text/javascript">
    $(document).ready(function () {
         <!--jump to actual row-->      
        {if isset($anchor)}
            $('#body-wrapper').animate({literal}{{/literal} scrollTop: $('#{$anchor}').offset().top-60{literal}}{/literal}, 100);
        {/if}
        
        $('a[data-toggle="collapse"]').click(function () {
                $(this).find('i.fa').toggleClass('fa-compress fa-expand');
        });
    });
    function toggleAll(){
        return (this.tog^=1) ? $('.collapse').collapse('hide') : $('.collapse').collapse('show');
    };
    
    function processdata(data){
        var jsonData = JSON.parse(data);
        $('body').find('.box-objective').css("filter","alpha(opacity=40)");;
        $('body').find('.box-objective').css("opacity","0.4");
        $('body').find('.box-objective').css("-moz-opacity","0.4");
        for (var i = 0; i < jsonData.length; i++) {
            $('body').find('#'+jsonData[i]).css("filter","alpha(opacity=100)");
            $('body').find('#'+jsonData[i]).css("opacity","1");
            $('body').find('#'+jsonData[i]).css("-moz-opacity","1");
        }
    };
        
    function ajax_search(id, search){
        var content;
        $.get('../share/processors/p_highlight.php?id=' + id +'&search=' + search, processdata);   
    };
    
</script>
{/block}
{block name=additional_stylesheets}
<link rel="stylesheet" href="{$media_url}scripts/glossarizer/tooltip/tooltip.min.css">
{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{if isset($showaddObjectives)}{assign var="help" value="https://curriculumonline.gitbook.io/documentation/benutzerhandbuch/benutzerverwaltung"}
{else}{assign var="help" value="https://curriculumonline.gitbook.io/documentation/benutzerhandbuch/benutzerverwaltung"}{/if}
{content_header p_title="{$course[0]->curriculum}" pages=$breadcrumb help= $help}       

<!-- Main content -->
<section class="content " >   
    <div class="row ">
        <div class="col-xs-12" >
            <form action="#" class="no-padding col-xs-12 col-sm-12 col-md-4 col-lg-3 pull-right" onsubmit="ajax_search({$course[0]->curriculum_id},$('#v_search').val());$('#search_results').load('../share/request/render_html.php?render=search&func=view&id={$course[0]->curriculum_id}&search='+$('#v_search').val()+'&ajax=true#search_results');return false">
                <div class="input-group">
                  <input type="text" id="v_search" class="form-control" placeholder="Suche...">
                      <span class="input-group-addon btn" onclick="ajax_search({$course[0]->curriculum_id},$('#v_search').val());$('#search_results').load('../share/request/render_html.php?render=search&func=view&id={$course[0]->curriculum_id}&search='+$('#v_search').val()+'&ajax=true#search_results');return false">
                            <i class="fa fa-search"></i>  
                      </span>
                </div>
            </form>
            <div class="btn-group pull-left margin-r-5">
                <button type="button" class="btn btn-default" data-toggle="tooltip" title="Kompetenzen einklappen bzw. ausklappen"  onclick="toggleAll(); $(this).find('i.fa').toggleClass('fa-compress fa-expand');">
                    <i class="fa fa-compress"></i>
                </button>
                {Render::split_button($cur_content)}
                {if isset($content_menu)}
                   {Render::split_button($content_menu)}    
                {/if}
                
                {if checkCapabilities('curriculum:print', $my_role_id, false)}
                    <button type="button" class="btn btn-default" onclick="formloader('print','curriculum',{$course[0]->curriculum_id});">
                        <i class="fa fa-print"></i>
                    </button>
                {/if}
            </div>
            <div class="btn-group pull-left margin-r-5">
                {Render::split_button($glossar_content)}
                {if isset($showaddObjectives)}
                    <button type="button" class="btn btn-default" onclick='formloader("content", "new", null,{["label_title"=>"Begriff", "label_content"=>"Definition", "label_header"=>"Glossar hinzufügen","label_save"=>"Glossareintrag speichern", "context"=>"glossar", "reference_id"=>$course[0]->curriculum_id]|@json_encode nofilter});'>
                        <i class="fa fa-list-alt "></i>
                    </button>
                {/if} 
            </div>
            <div class="btn-group pull-left margin-r-5">
                {Render::split_button($cur_files)}
                {if isset($showaddObjectives)}
                    <a href="{$template_url}renderer/uploadframe.php?context=curriculum&ref_id={$course[0]->curriculum_id}&modal=true&format=1" class="nyroModal btn btn-default">
                        <i class="fa fa-upload text-black"></i>
                    </a>
                {/if} 
            </div>
            <div class="btn-group pull-left margin-r-5">
                {if count($needed_curriculum_list) eq 1}
                    <button type="button" class="btn btn-default" onclick="window.location.assign('index.php?action=view&curriculum_id={$course[0]->curriculum_id}&group={$page_group}&reference_view={$needed_curriculum_list[0]}');">
                        {$lang['REF_SELECTOR_TITLE']}
                    </button>
                {elseif isset($reference_curriculum_list)}
                    {Form::input_select('reference_curriuclumid', '', $reference_curriculum_list, 'curriculum', 'id', $selected_curriculum_id, null, "window.location.assign('index.php?action=view&curriculum_id={$course[0]->curriculum_id}&group={$page_group}&reference_view='+this.value);", "{$lang['REF_SELECTOR_TITLE']}", '', 'col-sm-12')}
                {/if}
            </div>
            {Render::badge_preview(["reference_id" => $course[0]->curriculum_id, "user_id" => $my_id])}
        </div>
            <div id="search_results"></div>    
        {if isset($curriculum_content_references)}
            {RENDER::quote_reference($curriculum_content_references)}    
        {/if}
        <div id="search_curriculum_{$course[0]->curriculum_id}" class="col-xs-12 top-buffer" >
         {if $terminal_objectives != false}
            {assign var="sol_btn" value="false"}  
            {*Thema Row*}
            <!-- Type Tabs -->
            <div class="nav-tabs">
                <ul class="nav nav-tabs">
                    {if count($ter_obj_given_type_ids) > 1}
                        {foreach name=type_ids_li item=type_id from=$ter_obj_type_id} 
                            {if $type_id->id|in_array:$ter_obj_given_type_ids}
                                <li class='{if isset($tab_type_id_{$type_id->id})}active{/if} '><a href="#tab_type_id_{$type_id->id}" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"tab_type_id_{$type_id->id}"]|@json_encode nofilter});'>{$type_id->type}</a></li>
                            {/if}
                        {/foreach}
                    {/if}
                </ul>
                <div class="tab-content" style="padding-top:10px;">
                    {foreach name=type_ids_ct item=type_id from=$ter_obj_type_id} 
                    {if $type_id->id|in_array:$ter_obj_given_type_ids}
                        <div class="tab-pane {if isset($tab_type_id_{$type_id->id})}active{/if}" id="tab_type_id_{$type_id->id}">
                            {foreach name=foreach_ter key=terid item=ter from=$terminal_objectives}   
                            <div class="row " >
                                <div class="col-xs-12"> 
                                    {if $ter->type_id eq $type_id->id}
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
                                            {RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $my_id, "edit" => true, "orderup" => $orderup, "orderdown" => $orderdown, "highlight" => $highlight])}
                                        {else}
                                            {RENDER::objective(["type" =>"terminal_objective", "objective" => $ter , "user_id" => $my_id, "highlight" => $highlight, "reference_view" => $reference_view])}
                                        {/if}

                                        {*Ende Thema*}

                                        {*Ziele*}
                                        {if $enabledObjectives != false}
                                            <span id="collaps_ter_{$ter->id}" class="collapse in">
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
                                                    {RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $my_id, "solutions" => $solutions, "edit" => true, "orderup" => $orderup, "orderdown" => $orderdown, "border_color" => $ter->color, "highlight" => $highlight])}
                                                {else}
                                                    {RENDER::objective(["type" =>"enabling_objective", "objective" => $ena , "user_id" => $my_id, "solutions" => $solutions, "group_id" => $page_group, "border_color" => $ter->color, "highlight" => $highlight, "reference_view" => $reference_view])}
                                                {/if}
                                            {/if}
                                            {/foreach}
                                            </span>
                                        {/if}

                                    {if isset($showaddObjectives)}  
                                        <div class="box box-objective bg-white"> 
                                            <span style="position:absolute; top:20px; width:100%;text-align: center;"><h5 class="text-primary">Ziel</h5></span>
                                            <div class="text-primary" style="text-align: center; padding: 25px; font-size:100px;" onclick="formloader('enabling_objective','new', {$ter->id});">+</div>
                                        </div>
                                    {/if}
                                {/if}
                                 </div> <!-- /.col -->
                             </div><!-- .row-->
                             <div class="hidden-lg hidden-md"><br/></div>
                             {/foreach}     
                        </div>        <!-- /.tab-pane -->
                    {/if}
                    {/foreach}
                </div>
                <!-- /.tab-content -->
            </div>
            <!--  Type Tabs  --> 
            
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

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}