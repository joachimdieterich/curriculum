{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help=''}   

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
                        <!-- The time line -->
                            <ul class="timeline">
                                {assign var="p_date"  value=' '} 
                                {foreach key=artid item=art from=$artefact}
                                {if $p_date neq $art->creation_time|date_format:"%d.%m.%Y"} <!-- only print time label if last artefact timestamp neq this timestamp --> 
                                <!-- timeline time label -->
                                <li class="time-label">
                                  <span class="bg-red">
                                    {$art->creation_time|date_format:"%d.%m.%Y"}
                                  </span>
                                </li>
                                {assign var="p_date"  value={$art->creation_time|date_format:"%d.%m.%Y"}}
                                {/if}
                                <!-- /.timeline-label -->
                                
                                
                                {if $art->artefact_type eq 'enablingObjective'}
                                <!-- timeline item enabling objectives-->
                                <li>
                                  <i class="fa fa-check bg-green"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">{$art->curriculum}</a> {$art->accomplished_teacher}</h3>
                                    <div class="timeline-body">
                                        Du hast folgendes Lernziel erfolgreich abgeschlossen.
                                        <h4>{$art->title}</h4> 
                                       {$art->description}
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                
                                {if $art->artefact_type eq 'solution'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-cloud-upload bg-blue"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">{$art->curriculum}</a></h3>
                                    <div class="timeline-body">
                                        Du hast folgende Daten zum Lernziel {$art->title} eingereicht.
                                        <div class="box-footer">
                                            <ul class="mailbox-attachments clearfix">
                                                {Render::thumb(array($art->id))}    
                                            </ul>
                                        </div>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                
                                {if $art->artefact_type eq 'avatar'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-user bg-red"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Neues Profilbild</a></h3>
                                    <div class="timeline-body">
                                        Du hast folgendes Profilbild hochgeladen.
                                        <div class="box-footer">
                                            <ul class="mailbox-attachments clearfix">
                                                {Render::thumb(array($art->id))}    
                                            </ul>
                                        </div>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                
                                {if $art->artefact_type eq 'userFiles'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-upload bg-red-active"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Dateiupload</a></h3>
                                    <div class="timeline-body">
                                        Du hast die folgende Datei hochgeladen.
                                        <div class="box-footer">
                                            <ul class="mailbox-attachments clearfix">
                                                {Render::thumb(array($art->id))}    
                                            </ul>
                                        </div>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                
                                {if $art->artefact_type eq 'curriculum'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-th bg-yellow"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Material hochgeladen</a></h3>
                                    <div class="timeline-body">
                                        Du hast die folgende Datei für den Lehrplan "{$art->curriculum}", Lernziel: {$art->title} hochgeladen.
                                        <div class="box-footer">
                                            <ul class="mailbox-attachments clearfix">
                                                {Render::thumb(array($art->id))}    
                                            </ul>
                                        </div>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                
                                {if $art->artefact_type eq 'backup'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa  fa-life-ring bg-fuchsia"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Backup erstellt </a></h3>
                                    <div class="timeline-body">
                                        Du hast ein Backup des Lehrplans "{$art->curriculum}" erstellt.
                                        <div class="box-footer">
                                            <ul class="mailbox-attachments clearfix">
                                                {Render::thumb(array($art->id))}    
                                            </ul>
                                        </div>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                {if $art->artefact_type eq 'institution'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-institution bg-black"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Institutions-Logo hochgeladen </a></h3>
                                    <div class="timeline-body">
                                        Du hast Logo für eine Institution hochgeladen.
                                        <div class="box-footer">
                                            <ul class="mailbox-attachments clearfix">
                                                {Render::thumb(array($art->id))}    
                                            </ul>
                                        </div>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                {if $art->artefact_type eq 'mail_inbox'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-envelope-o bg-light-blue"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Nachricht erhalten </a></h3>
                                    <div class="timeline-body">
                                        Du folgende Nachricht von <b>{$art->accomplished_teacher}</b> erhalten.<br>
                                        {$art->description}
                                        <ul class="mailbox-attachments clearfix">
                                        {Render::thumb(Render::link($art->description, 'message'))}
                                        </ul>
                                        
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                {if $art->artefact_type eq 'mail_outbox'}
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-envelope-o bg-light-blue"></i>
                                  <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {$art->creation_time}</span>
                                    <h3 class="timeline-header"><a href="#">Nachricht geschrieben </a></h3>
                                    <div class="timeline-body">
                                        Du folgende Nachricht an {$art->accomplished_teacher} geschrieben.<br>
                                        {$art->description}
                                        <ul class="mailbox-attachments clearfix">
                                        {Render::thumb(Render::link($art->description, 'message'))}
                                        </ul>
                                    </div>
                                    {*<div class="timeline-footer">
                                      <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a>
                                    </div>*}
                                  </div>
                                </li>
                                {/if}
                                <!-- END timeline item -->    
                                
                                {/foreach}
                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                           </ul><!-- timleline -->     
    </div>
</div>
 
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}