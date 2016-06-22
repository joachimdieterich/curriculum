{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
  
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Benutzerliste_importieren'}  

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Nutzerkonten per CSV-Datei hochladen</h3>
                  {*<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>*}
                </div><!-- /.box-header -->
                <form name="file" enctype="multipart/form-data" action="index.php?action=userImport" method="post">                 
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-7">
                                <p>Die CSV-Datei muss folgendes Format haben:<br/>
                                - Die ersten Zeile muss die Schlüsselwerte enthalten (z.B.:username, password, firstname, lastname, email, role_id, confirmed, postalcode, city, state, country)<br/>
                                - Die Schüsselwerte <strong>username, password, firstname, lastname </strong>und <strong>email</strong> müssen gesetzt werden. <br/>
                                - Wird keine Benutzer-Rolle festgelegt (role_id) wird die Standard-Rolle der Institution verwendet.<br/>
                                - Die maximale Dateigröße liegt bei {$filesize}MB und kann im Adminstrationsbereich festgelegt werden.<br/>
                                - Die Datei muss im utf-8 Format gespeichert werden, sonst werden Umlaute und Sonderzeichen nicht korrekt importiert</p>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-5">
                                <h4>CSV-Vorlagen</h4>
                                <a class="btn btn-app" href="{$support_path}Vorlage-min.csv"><i class="fa fa-save"></i> Minimal - nur benötigte Felder</a>
                                <a class="btn btn-app" href="{$support_path}Vorlage-max.csv"><i class="fa fa-save"></i> Maximal - nur benötigte Felder</a>
                            </div>
                        </div>

                        <div class="row col-xs-12 col-sm-12 col-md-5 col-lg-5">
                            <p>{Form::input_select('institution_id', 'Institution', $my_institutions, 'institution', 'institution_id', $my_institution_id, null)}</p>
                            <p>{Form::input_select('role_id', 'Rolle', $roles, 'role', 'id', $role_id, null)}</p>
                            <p>{Form::input_select('group_id', 'Lerngruppe', $groups, 'group', 'id', null, null)}</p>
                            <p>{Form::input_text('delimiter', 'Trennzeichen', $delimiter, null)}</p>
                            <p>
                              <label for="exampleInputFile">CSV-Datei hochladen</label>
                              <input name="datei" type="file" value="">
                            </p> 
                        </div>
                    </div>
                                
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Importieren</button>
                    </div> 
                </form>
            </div>  
        </div>  
              
        {if isset($nusr_val)}
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Neue Nutzer</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    {html_paginator id='newUsersPaginator'}     
                </div>
            </div>  
        </div> 
        {/if}        
    </div>
</section>

{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}