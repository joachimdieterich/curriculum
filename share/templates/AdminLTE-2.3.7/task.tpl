{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
<script type="text/javascript" > 
$(document).ready(function() {
    if (location.hash) {
        $("a[href='" + location.hash + "']").tab("show");
    }
    $(document.body).on("click", "a[data-toggle]", function(event) {
        location.hash = this.getAttribute("href");
    });
    $(function() {
    $(".tasklink").on("click", function() {
        $(".tasklink").removeClass("bg-gray");  // remove active class from all
        $(this).addClass("bg-gray");         // add active class to clicked element
    });
});
    
});
$(window).on("popstate", function() {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    $("a[href='" + anchor + "']").tab("show");
});



</script>
{if isset($smarty.session.PAGE->show_reference_id)}
    <script type="text/javascript">
        loadhtml('task', {$smarty.session.PAGE->show_reference_id}, 'task_left_col', 'task_right_col', 'col-xs-12 col-lg-6', 'col-xs-12 col-lg-6');
    </script>
{/if} 

{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div id="task_left_col" class="col-xs-12">
                    <div class="pull-right margin">
                        {if isset($task_reset)}
                            <a href="index.php?action=task" style="margin-left: 10px;" ><span class="fa fa-refresh"></span> Suche zurücksetzen</a>
                        {/if}
                        <div class="has-feedback" style="margin-right: 10px;width:150px;">
                            <form id="view_search" method="post" action="index.php?action=task">
                                <input type="text" name="search" class="form-control input-sm" placeholder="Suchen">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </form>
                        </div>
                    </div>
                    
                    <div class="nav-tabs-custom"> 
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Persönlich</a></li>
                            {*<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Kursbuch (Aufgaben)</a></li>*}
                            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Gruppen</a></li>
                            <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Institution</a></li>
                            {*<button type="button" class="btn btn-default pull-right" style="margin-right:10px;"><i class="fa fa-plus"></i> Aufgabe hinzufügen</button>*}
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane row active" id="tab_1">
                                <div class="form-horizontal col-xs-12">
                                {if checkCapabilities('task:add', $my_role_id, false)}
                                    {Form::input_button(['id' => 'addUserTask', 'label' => 'Aufgabe / Notiz hinzufügen', 'icon' => 'fa fa-plus-circle', 'class' => 'btn btn-default pull-right', 'onclick' => "formloader('task', 'userFiles', $my_id);"])}
                                {/if}
                                <br><br>
                                <div style="overflow: scroll;  width: 100%; max-height: 400px;">{Render::taskList('userFiles', $my_id, '')}</div>
                                
                                </div>
                            </div><!-- /.tab-pane -->
                            
                            <!-- Coursebook -->
                            {*if isset($course_tasks)}
                            <div class="tab-pane row" id="tab_2">
                                <div class="form-horizontal col-xs-12">
                                    <div class="pull-right">
                                        {Form::input_select('course_filter', null, $courses, 'group, curriculum', 'course_id', $filter_course_id, null, "location.href='index.php?action=task&filter_course='+this.value+'#tab_2'", 'Nach Kurs filtern', 'col-sm-0', 'col-sm-12')}
                                    </div>
                                    
                                    <div style="overflow: scroll;  width: 100%; max-height: 400px;">
                                    {foreach item=cb from=$course_tasks}                                    
                                        {Render::taskList('coursebook', $cb->id, $cb->curriculum)}
                                    {/foreach}
                                    </div>
                                </div>
                            </div><!-- /.tab-pane -->
                            {/if*}
                            <!-- Groups -->
                            <div class="tab-pane row" id="tab_3">
                                <div class="form-horizontal col-xs-12">
                                {if isset($myInstitutions)}
                                    {Form::input_select('institution_group', 'Institution', $myInstitutions, 'institution', 'id', $my_institution_id, null, "getValues('group', this.value, 'groups');")}
                                {/if} 
                                {if isset($groups_array)}
                                    {Form::input_select('groups', 'Lerngruppe', $groups_array, 'group, semester', 'id', null, null)}
                                {/if}
                                {if checkCapabilities('task:add', $my_role_id, false)}
                                    {Form::input_button(['id' => 'addGroupTask', 'label' => 'Aufgabe hinzufügen', 'icon' => 'fa fa-plus-circle', 'class' => 'btn btn-default pull-right', 'onclick' => "formloader('task', 'group', document.getElementById('groups').value);"])}
                                {/if}
                                <br>
                                <div style="overflow: scroll;  width: 100%; max-height: 400px;">
                                {foreach item=gr from=$groups_array}                                    
                                    {Render::taskList('group', $gr->id, $gr->group)}
                                {/foreach}
                                </div>
                                </div>
                            </div><!-- /.tab-pane -->
                            
                            <div class="tab-pane" id="tab_4">
                                <div class="pull-right">
                                    {if isset($myInstitutions)}
                                        {Form::input_select('institution_filter', null, $myInstitutions, 'institution', 'id', $filter_institution_id, null, "location.href='index.php?action=task&filter_institution='+this.value+'#tab_4'", 'Nach Institution filtern', 'col-sm-0', 'col-sm-12')}
                                    {/if}
                                </div>
                                <div style="overflow: scroll;  width: 100%; max-height: 400px;">
                                {foreach item=ins from=$institution_tasks}                                    
                                    {Render::taskList('institution', $ins->id, $ins->institution)}
                                {/foreach}
                                </div>
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div><!-- /.nav-tab-custom -->    
        </div>    
        <div id="task_right_col" class="hidden">
            <div class="box box-default">
                <div class="box-header">
                
                </div>
                <div class="box-body">
                
                </div>
                <div class="box-footer">
                
                </div>
            </div>
        </div>                     
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}