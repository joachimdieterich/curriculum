{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{*block name=nav}{$smarty.block.parent}{/block*}

{block name=additional_scripts}{$smarty.block.parent}
 <script type="text/javascript">
function toggleAll(){
        return (this.tog^=1) ? $('.collapse').collapse('hide') : $('.collapse').collapse('show');
    };
    
</script>
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=navigator'}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-6 col-centered">
            {FORM::input_select('search', '', $search_navigator, 'title', 'onclick', '', '', 'window.location.assign(this.value);', 'Schnellzugriff', '', '')}
        </div>
        {if checkCapabilities('navigator:add', $my_role_id, false)}    
            <div class="pull-left" style="padding: 0 0 10px 15px;">
                <button type="button" class="btn btn-default " onclick="formloader('help','new');" ><i class="fa fa-plus"></i> Datei hinzufügen</button>
            </div>
        {/if}
    </div>
    <div class="row"> 
        <div class="{$navigator[0]->nv_top_width_class} ">
            {foreach key=nav_id item=nav from=$navigator}
                {if $nav->nb_position eq 'top'}
                    {RENDER::navigator_item($nav)}
                {/if}
            {/foreach}
        </div>
    </div>
    <div class="row" > 
        <div class="{$navigator[0]->nv_content_width_class}" >     
            {foreach key=nav_id item=nav from=$navigator}
                {if $nav->nb_position eq 'content'}
                    {RENDER::navigator_item($nav)}
                {/if}
            {/foreach}
        </div>
    </div>
    <div class="row" > 
        <div class="{$navigator[0]->nv_content_width_class}" >
            {foreach key=nav_id item=nav from=$navigator}
                {if $nav->nb_position eq 'footer'}
                    {RENDER::navigator_item($nav)}
                {/if}
            {/foreach}
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
