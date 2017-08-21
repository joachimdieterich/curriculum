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
    
    
});
$(window).on("popstate", function() {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    $("a[href='" + anchor + "']").tab("show");
});



</script>
{if isset($smarty.session.PAGE->show_reference_id)}
    <script type="text/javascript">
        loadhtml('task', {$smarty.session.PAGE->show_reference_id}, 'task_left_col', 'task_right_col', 'col-xs-6', 'col-xs-6');
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
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Kursbuch (Aufgaben)</a></li>
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
                                {Render::taskList('userFiles', $my_id, '')}
                                </div>
                            </div><!-- /.tab-pane -->
                            
                            <!-- Coursebook -->
                            <div class="tab-pane row" id="tab_2">
                                <div class="form-horizontal col-xs-12">
                                    {Form::input_select('course', 'Kurs', $courses, 'group, curriculum', 'id', null, null)}
                                    <br>
                                    {foreach item=cb from=$coursbook}                                    
                                        {Render::taskList('coursebook', $cb->id, $cb->curriculum)}
                                    {/foreach}
                                </div>
                            </div><!-- /.tab-pane -->
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
                                {foreach item=gr from=$groups_array}                                    
                                    {Render::taskList('group', $gr->id, $gr->group)}
                                {/foreach}
                                
                                </div>
                            </div><!-- /.tab-pane -->
                            
                            <div class="tab-pane" id="tab_4">
                                {foreach item=ins from=$myInstitutions}                                    
                                    {Render::taskList('institution', $ins->id, $ins->institution)}
                                {/foreach}
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