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
                {foreach key=artid item=cb from=$coursebook}
                {if $p_date neq $cb->creation_time|date_format:"%d.%m.%Y"} <!-- only print time label if last artefact timestamp neq this timestamp --> 
                <!-- timeline time label -->
                <li class="time-label">
                  <span class="bg-red">
                    {$cb->creation_time|date_format:"%d.%m.%Y"}
                  </span>
                </li>
                {assign var="p_date"  value={$cb->creation_time|date_format:"%d.%m.%Y"}}
                {/if}
                <!-- /.timeline-label -->    
               
                <!-- timeline item enabling objectives-->
                <li>
                  <i class="fa fa-check bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {$cb->creation_time}</span>
                    <h3 class="timeline-header"><a href="#">Lehrplanname?</a> {$cb->creator}</h3>
                    <div class="timeline-body">
                        Eintrag
                        <h4>{$cb->topic}</h4> 
                       {$cb->description}
                    </div>
                    <div class="timeline-footer">
                      <a class="btn btn-primary btn-xs">Read more</a>
                      <a class="btn btn-danger btn-xs">Delete</a>
                    </div>
                  </div>
                </li>
                
                
                     
                {/foreach}
                <li>
                  <i class="fa fa-clock-o bg-gray"></i>
                </li>
           </ul><!-- timleline -->  
           
           
           <div class="box box-primary">
                <div class="box-body">
                    
                    
                    {html_paginator id='cbP'}
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}