{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/development/'}      
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
             <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li id="nav_tab_user" {if isset($f_user)}class="active"{/if}>
                        <a href="#tab_user" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"f_user", "reload"=>"false"]|@json_encode nofilter});'>Meine Einstellungen</a>
                    </li>
                   <li id="nav_tab_institution" {if isset($f_institution)}class="active"{/if}>
                        <a href="#tab_institution" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"f_institution", "reload"=>"false"]|@json_encode nofilter});'>Institution</a>
                    </li>  
                    <li id="nav_tab_system" {if isset($f_system)}class="active"{/if}>
                        <a href="#tab_system" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"f_system", "reload"=>"false"]|@json_encode nofilter});'>System</a>
                    </li>  
                    {if isset($cf_global_plugins)}
                        {foreach item=plug from=$cf_global_plugins name=cf_plugin}
                            <li id="nav_tab_{$plug->name}" {if isset(${"f_{$plug->name}"})}class="active"{/if}>
                                <a href="#tab_{$plug->name}" data-toggle="tab" onclick='processor("config","page", "config",{["tab"=>"f_{$plug->name}", "reload"=>"false"]|@json_encode nofilter});'>{$plug->name}</a>
                            </li> 
                        {/foreach}
                    {/if}
                </ul>
                 <div class="tab-content">
                    {* start user config*}
                    <div id="tab_user" class="tab-pane {if isset($f_user)}active{/if}">
                        <span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#cf_user">
                            <h4 class="text-black">Einstellungen
                                <button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#cf_user" aria-expanded="true" data-toggle="tooltip" title="aus-/einklappen">
                                    <i class="fa fa-expand"></i>
                                </button>
                            </h4>
                        </span><hr style="clear:both;">
                        <span id ="cf_user" class="collapse in">
                            <form id="form_user_config"  class="form-horizontal" role="form" method="post" action="">
                                {foreach item=val from=$cf_user name=cf_user}
                                    {if is_bool($val)}
                                        {Form::input_switch("{$val->context_id}_{$val->name}", $val->name, $val->value, $error)}
                                    {else}
                                        {Form::input_text("{$val->context_id}_{$val->name}", $val->name, $val->value, $error, '', $val->type)}
                                    {/if}
                                {/foreach}
                            </form> 
                        </span>
                    </div>
                    {* end user config*}
                    {* Start institution config*}
                    <div id="tab_institution" class="tab-pane {if isset($f_institution)}active{/if}">
                    </div>
                    {* end institution config*}
                    {* Start system config*}
                    <div id="tab_system" class="tab-pane {if isset($f_system)}active{/if}">
                        <span class="col-xs-12 bg-light-aqua" data-toggle="collapse" data-target="#cf_system">
                            <h4 class="text-black">Globale Einstellungen
                                <button class="btn btn-box-tool pull-right" style="padding-top:0;" type="button" data-toggle="collapse" data-target="#cf_system" aria-expanded="true" data-toggle="tooltip" title="aus-/einklappen">
                                    <i class="fa fa-expand"></i>
                                </button>
                            </h4>
                        </span><hr style="clear:both;">
                        <span id ="cf_system" class="collapse in">
                            <form id="form_system_config"  class="form-horizontal" role="form" method="post" action="">
                                {foreach item=val from=$cf_system name=cf_system}
                                    {if $val->type == 'bool'}
                                        <div>{Form::input_switch("{$val->context_id}_{$val->name}", $val->name, $val->value, $error, false, "col-sm-3","col-sm-9","ajaxSubmit(this,\"p_set.php\",\"config_system\",{$val|@json_encode})")}</div>
                                    {else}
                                        {Form::input_text("{$val->context_id}_{$val->name}", $val->name, $val->value, $error, '', $val->type, NULL, NULL, "col-sm-3","col-sm-9",null,"ajaxSubmit(this,\"p_set.php\",\"config_system\",{$val|@json_encode})")}
                                    {/if}
                                {/foreach}
                            </form> 
                        </span>
                    </div>
                    {* end system config*}
                    {* Start plugin config*} 
                    {if isset($cf_global_plugins)}
                        {foreach item=plug from=$cf_global_plugins name=cf_plugin}
                            <div  id="tab_{$plug->name}" class="tab-pane {if isset(${"f_{$plug->name}"})}active{/if}">
                                {RENDER::plugin_config("{$plug->type}_plugin_{$plug->name}")}
                            <form id="form_global_plugin_config" class="form-horizontal" role="form" method="post" action="index.php?action=config">
                                {foreach item=val from=$plug->config name=cf_plugin_val}
                                    {if is_bool($val)}
                                        {Form::input_switch("config_plugins_{$val->id}", $val->name, $val->value, $error, '', "text", NULL, NULL, "col-sm-3","col-sm-9",null,"ajaxSubmit(this,\"p_set.php\",\"config_plugins\",{$val|@json_encode})")}
                                    {else}
                                        {Form::input_text("config_plugins_{$val->id}", $val->name, $val->value, $error, '', "text", NULL, NULL, "col-sm-3","col-sm-9",null,"ajaxSubmit(this,\"p_set.php\",\"config_plugins\",{$val|@json_encode})")}
                                    {/if}
                                {/foreach}
                            </form>
                        
                    </div> 
                        {/foreach}
                    {/if}
                    {* end system config*}
             </div>
        </div>
    </div>
</section>  
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}