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
<div class="row">
    <div class="col-xs-12">
    <!-- The time line -->
        <div class="timeline">
            <dl>
                {assign var="p_date"  value=' '} 
                {foreach key=artid item=art from=$artefact}
                {if $p_date neq $art->creation_time|date_format:"%d.%m.%Y"} <!-- only print time label if last artefact timestamp neq this timestamp --> 
                <!-- timeline time label -->
                <dt> {$art->creation_time|date_format:"%d.%m.%Y"}</dt>
                {assign var="p_date"  value={$art->creation_time|date_format:"%d.%m.%Y"}}
                {/if}
                <!-- /.timeline-label -->

                {if $art->artefact_type eq 'enablingObjective'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                    <div class="events">
                        <h4 class="events-heading"><a href="#">{$art->curriculum}</a> {$art->accomplished_teacher}</h4>
                        <div class="events-body">
                            Du hast folgendes Lernziel erfolgreich abgeschlossen.
                            <h4 class="events-heading">{$art->title}</h4> 
                           {$art->description}
                        </div>
                    </div>
                </dd>
                {/if}

                {if $art->artefact_type eq 'solution'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                    <div class="events">
                        <h4 class="events-heading"><a href="#">{$art->curriculum}</a></h4>
                        <div class="events-body">
                            Du hast folgende Daten zum Lernziel {$art->title} eingereicht.
                            <div class="mailbox-attachments clearfix">
                                {Render::thumb(array($art->id))}    
                            </div>
                        </div>
                    </div>
                </li>
                {/if}

                {if $art->artefact_type eq 'avatar'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <span class="time">{$art->creation_time|date_format:"%d %B"}</span>
                    <div class="events">
                        <h4 class="events-heading"><a href="#">Neues Profilbild</a></h4>
                        <div class="events-body">
                            Du hast folgendes Profilbild hochgeladen.
                            <div class="mailbox-attachments clearfix">
                                {Render::thumb(array($art->id))}    
                            </div>
                        </div>
                    </div>
                </dd>
                {/if}

                {if $art->artefact_type eq 'userFiles'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                    <div class="events">
                        <h4 class="events-heading"><a href="#">Dateiupload</a></h4>
                        <div class="events-body">
                            Du hast die folgende Datei hochgeladen.
                                <div class="pull-right mailbox-attachments clearfix">
                                    {Render::thumb(array($art->id))}    
                                </div>
                        </div>
                    </div>
                </dd>
                {/if}

                {if $art->artefact_type eq 'curriculum'}
                <!-- timeline item -->
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                    <div class="events">
                        <h4 class="events-heading"><a href="#">Material hochgeladen</a></h4>
                        <div class="events-body">
                            Du hast die folgende Datei für den Lehrplan "{$art->curriculum}", Lernziel: {$art->title} hochgeladen.
                            <div class="mailbox-attachments clearfix">
                                {Render::thumb(array($art->id))}    
                            </div>
                        </div>
                    </div>
                </dd>
                {/if}

                {if $art->artefact_type eq 'backup'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix"> 
                  <div class="circ"></div>
                    <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                    <div class="events">
                        <h4 class="events-heading"><a href="#">Backup erstellt </a></h4>
                        <div class="events-body">
                            Du hast ein Backup des Lehrplans "{$art->curriculum}" erstellt.
                            <div class="mailbox-attachments clearfix">
                                {Render::thumb(array($art->id))}    
                            </div>
                        </div>
                  </div>
                </dd>
                {/if}
                {if $art->artefact_type eq 'institution'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                  <div class="circ"></div>
                  <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                  <div class="events">
                    <h4 class="events-heading"><a href="#">Institutions-Logo hochgeladen </a></h4>
                    <div class="events-body">
                        Du hast Logo für eine Institution hochgeladen.
                        <div class="mailbox-attachments clearfix">
                            {Render::thumb(array($art->id))}    
                        </div>
                    </div>
                  </div>
                </dd>
                {/if}
                {if $art->artefact_type eq 'mail_inbox'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <div class="time">{$art->creation_time|date_format:"%d %B"}</div>
                    <div class="events">
                    <h4 class="events-heading"><a href="#">Nachricht erhalten </a></h4>
                    <div class="events-body">
                        Du folgende Nachricht von <b>{$art->accomplished_teacher}</b> erhalten.<br>
                        {$art->description}
                        <div class="mailbox-attachments clearfix">
                        {Render::thumb(Render::link($art->description, 'message'))}
                        </div> 
                    </div>
                  </div>
                </dd>
                {/if}
                {if $art->artefact_type eq 'mail_outbox'}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"></div>
                    <span class="time">{$art->creation_time|date_format:"%d %B"}</span>
                    <div class="events">
                    <h4 class="events-heading"><a href="#">Nachricht geschrieben </a></h4>
                    <div class="events-body">
                        Du folgende Nachricht an {$art->accomplished_teacher} geschrieben.<br>
                        {$art->description}
                        <div class="mailbox-attachments clearfix">
                        {Render::thumb(Render::link($art->description, 'message'))}
                        </div>
                    </div>
                  </div>
                </dd>
                {/if}
                <!-- END timeline item -->    

                {/foreach}
                <dd class="{if ($artid % 2 == 1)}pos-left{else}pos-right{/if} clearfix">
                    <div class="circ"> 
                        <i class="fa fa-clock-o bg-gray"></i>
                    </div> 
                    <span class="time">Eintrag hinzufügen</span>
                </dd>
            </dl>
        </div><!-- timleline -->     
    </div>
</div>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}