{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='https://curriculumonline.gitbook.io/documentation/'}       
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-body">
                     {*$updateinfo*}
                     
                     <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Update-Kennung</th>
                    <th>Titel / Log</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Optionen</th>
                  </tr>
                  </thead>
                  <tbody>
                    {section name=upd loop=$updates}  
                    <tr>
                      <td>{$updates[upd]->filename}</td>
                      <td>{$updates[upd]->description}{if $updates[upd]->log neq ''}<br><br><strong>Log:</strong><br>{$updates[upd]->log}{/if}</td>
                      {if $updates[upd]->status eq 0}       <td><span class="label label-warning">Update offen</span><br>{$updates[upd]->timestamp}</td>
                      {elseif $updates[upd]->status eq 1}   <td><span class="label label-success">Update installiert</span><br>{$updates[upd]->timestamp_installed}</td>
                      {elseif $updates[upd]->status eq 2}   <td><span class="label label-danger">Update fehlgeschlagen</span><br>{$updates[upd]->timestamp_installed}</td>
                      {/if}
                      <td>
                        {$updates[upd]->user}
                      </td>
                      <td>
                          {if $updates[upd]->status neq 1}
                            <a data-toggle="tooltip" title="Update installieren" class="fa fa-refresh" href="index.php?action=update&filename={$updates[upd]->filename}">
                          {/if}
                      </td>
                    </tr>
                    {/section}
                  
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        </div>             
       
    </div>
    
    
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}