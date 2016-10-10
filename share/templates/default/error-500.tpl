{extends file="base.tpl"}

{block name=title}Interner Serverfehler{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    <section class="content-header">
        <h1>
          500 Error
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?action=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">500 Error</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-red">500</h2>
            <div class="error-content"><br>
                <h3><i class="fa fa-warning text-red"></i> Interner Server Fehler.</h3>
                <p>
                  Sollte der Fehler dauerhaft bestehen, melden Sie diesen bitte an einen Administrator.
                  Hier gehts zurück auf die  <a href="index.php?action=dashboard">Startseite</a>.
                </p>
            </div>
        </div><!-- /.error-page -->
    </section><!-- /.content -->
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}
