{extends file="base.tpl"}

{block name=title}Fehler{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content} 
    <section class="content-header">
        <h1> 403 Error</h1>
        <ol class="breadcrumb">
            <li><a href="index.php?action=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> 403 Error</li>
        </ol>
    </section>    
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger"> 403</h2>
            <div class="error-content"><br>
                <h3><i class="fa fa-warning text-red"></i> Fehlende Berechtigung.</h3>
                <p><strong>{$curriculum_exception}</strong> <br>Die Seite <strong>{$page_name}</strong> kann nicht angezeigt werden.<br><br>
                    Hier gehts zurück auf die  <a href="index.php?action=dashboard">Startseite</a>.</p>
            </div><!-- /.error-content -->
        </div><!-- /.error-page -->
    </section><!-- /.content --> 
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}