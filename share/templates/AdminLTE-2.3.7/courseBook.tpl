{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
<script src="{$template_url}plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="{$template_url}plugins/datepicker/locales/bootstrap-datepicker.de.js"></script>
<script type="text/javascript"> 
$('#cb_datepicker').datepicker({
    todayBtn: "linked",
    language: "de",
    calendarWeeks: true,
    autoclose: true,
    todayHighlight: true
});
</script>
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}
<link rel="stylesheet" href="{$template_url}plugins/datepicker/datepicker3.css">

{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}   

<!-- Main content -->
<section class="content">
    <div class="row ">
        <div class="col-xs-12">
            {*<input id="cb_datepicker" data-provide="datepicker" data-date-format="dd.mm.yyyy" onchange="location.href='index.php?action=courseBook&date='+this.value;">*}
            {html_timeline id='coursebookP'} 
            
            {*Render::courseBook($coursebook)*}  
        </div>
    </div>
</section>

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}