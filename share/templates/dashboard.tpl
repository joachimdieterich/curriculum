{extends file="base.tpl"}

{block name=title}{$page_title}{/block}
{block name=description}{$smarty.block.parent}{/block}
{block name=nav}{$smarty.block.parent}{/block}

{block name=additional_scripts}{$smarty.block.parent}
 
{/block}
{block name=additional_stylesheets}{$smarty.block.parent}{/block}

{block name=content}
    
<!-- Content Header (Page header) -->
{content_header p_title=$page_title pages=$breadcrumb help='http://docs.joachimdieterich.de/index.php?title=Startseite'}    
 
<!-- Main content -->
<section class="content">
    <!-- Info boxes -->
    <div class="row" >
        
         <div class="col-md-4 ">
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Erfolge</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                <div class="box-body">
                {if isset($enabledObjectives)} 
                  Hier siehst du, welche Ziele du in den letzten <strong>{$my_acc_days}</strong> Tagen erreicht hast.
                      {foreach key=enaid item=ena from=$enabledObjectives}
                          <div class="panel panel-success">
                              <div class="panel-heading">
                                <h3 class="panel-title"> {$ena->curriculum}<span class="pull-right">{$ena->accomplished_teacher}</span></h3>
                              </div>
                              <div class="panel-body">
                                  {$ena->enabling_objective|truncate:100}<!--{$ena->description}-->
                              </div> 
                          </div>
                      {/foreach}
                  {else}<p>In den letzten <strong>{$my_acc_days}</strong> Tagen hast du keine Ziele abgeschlossen.</p>{/if}
                </div>
            </div>  
        </div>
        
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Pinnwand</h3>
                  <div class="box-tools pull-right">
                    {if checkCapabilities('dashboard:editBulletinBoard', $my_role_id, false)}  
                    <button class="btn btn-box-tool" data-widget="edit" onclick="formloader('bulletinBoard','edit');"><i class="fa fa-edit"></i></button>
                    {/if}
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                {if $bulletinBoard}
                    <h4>{$bulletinBoard->title}</h4>
                    {$bulletinBoard->text}
                {/if}
                </div>
            </div>  
        </div>  
        
       
   
        {if isset($myInstitutions)}     
        {foreach key=insid item=ins from=$myInstitutions}
            <div class="col-md-4">
                <div class="box box-widget widget-user">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header bg-aqua-active" style="background: url('{$access_file}{$ins->file_id|resolve_file_id}') center right;background-size: contain; background-repeat: no-repeat;">
                    <h3 class="widget-user-username">{$ins->institution}</h3>
                    <h5 class="widget-user-desc">{$ins->schooltype_id}</h5>
                  </div>
                  <div class="box-body">
                    <div class="row">
                      <div class="col-sm-4 border-right">
                        <div class="description-block">
                          <h5 class="description-header">3,200</h5>
                          <span class="description-text">SCHÜLER</span>
                        </div><!-- /.description-block -->
                      </div><!-- /.col -->
                      <div class="col-sm-4 border-right">
                        <div class="description-block">
                          <h5 class="description-header">13,000</h5>
                          <span class="description-text">ERREICHTE ZIELE</span>
                        </div><!-- /.description-block -->
                      </div><!-- /.col -->
                      <div class="col-sm-4">
                        <div class="description-block">
                          <h5 class="description-header">35</h5>
                          <span class="description-text">LEHRER</span>
                        </div><!-- /.description-block -->
                        {*<strong>{$ins->institution}</strong><br>
                                    {$ins->schooltype_id}<br><br>
                                    {$ins->description}<br>

                                    {$ins->state_id}, {$ins->country}<br>
                                    {$ins->creator_id}*}
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                  </div>
                </div><!-- /.widget-user -->
           </div><!-- /.col -->
        {/foreach}
        {/if}

        
        {if isset($stat_users_online) && checkCapabilities('menu:readPassword', $my_role_id, false)}
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Abgeschlossene Ziele</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-footer no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li><a href="#">Gesamt <span class="pull-right text-red">{$stat_acc_all}</span></a></li>
                    <li><a href="#">davon Heute <span class="pull-right text-red">{$stat_acc_today}</span></a></li>
                    <li><a href="#">User Online <span class="pull-right text-red">{$stat_users_online}</span></a></li>
                    <li><a href="#">Heute <span class="pull-right text-green"> {$stat_users_today}</span></a></li>
                  </ul>
                </div><!-- /.footer -->
            </div><!-- /.box -->
        </div>    
        {/if}
        
        {if isset($myClasses)}
        {foreach key=claid item=cla from=$myClasses}    
        <div class="col-md-4 ">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-yellow">
                <h3 class="widget-user-username">{$cla->group}</h3>
                <h5 class="widget-user-desc">{$cla->institution_id|truncate:50}</h5>
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    {foreach item=cur_menu from=$my_enrolments}
                        {if $cur_menu->group_id eq $cla->id}
                            <li><a href="index.php?action=view&curriculum={$cur_menu->id}&group={$cur_menu->group_id}">{$cur_menu->curriculum} </a></li>
                        {/if}
                    {/foreach}
                </ul>
              </div>
            </div><!-- /.widget-user -->
        </div><!-- /.col -->        
        {/foreach}  
        {/if}        

        {if checkCapabilities('page:showCronjob', $my_role_id, false)}
        <div class="col-md-4 ">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Abgelaufene Ziele</h3>
                    <div class="box-tools pull-right">
                      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                {*$cronjob*}
                </div>
             </div>
        </div>
        {/if}

        <!-- Hilfe -->  
        <div class="col-md-4 ">
             <div class="box box-primary">
                 <div class="box-header with-border">
                       <h3 class="box-title">Hilfe</h3>
                       <div class="box-tools pull-right">
                         <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                         <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>
                     </div><!-- /.box-header -->
                 <div class="box-body text-center">
                     {if $my_role_id eq 0}
                     <video width="100%" controls preload="none">
                         <source src="{$media_url}/docs/Teilnehmer.mp4" type="video/mp4">
                     </video>
                     {/if}
                     {if $my_role_id eq 7 || $my_role_id eq 1}
                     <video  width="100%"  controls preload="none">
                         <source src="{$media_url}/docs/Lehrer.mp4" type="video/mp4">
                     </video>
                     {/if}
                     {if $my_role_id eq 6}
                     <video  width="100%"  controls preload="none">
                         <source src="{$media_url}/docs/Schuladmin.mp4" type="video/mp4">
                     </video>
                     {/if}
                     <a  href="http://docs.joachimdieterich.de"><img src="{$media_url}/images/wiki.png"></a>
                 </div>  
             </div>
        </div>
                 
        <!-- Moodle Login -->         
        <div class="col-md-4 ">
             <div class="box box-primary">
                 <div class="box-header with-border">
                       <h3 class="box-title">Moodle</h3>
                       <div class="box-tools pull-right">
                         <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                         <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>
                 </div><!-- /.box-header -->
                 <div class="box-body text-center">
                     <form target="_blank" action="https://lms.bildung-rp.de/mz-suew/login/index.php" method="post">
                        <div class="form-group has-feedback">
                          <input type="text" name="username" class="form-control" placeholder="Benutzername">
                          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                          <input type="password" name="password" class="form-control" placeholder="Passwort">
                          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                          <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Anmelden</button>
                          </div><!-- /.col -->
                        </div>
                      </form>
                 </div>
             </div>
        </div>
        
        <!-- INMIS -->         
        <div class="col-md-4 ">
             <div class="box box-primary">
                 <div class="box-header with-border">
                       <h3 class="box-title">Links</h3>
                       <div class="box-tools pull-right">
                         <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                         <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>
                 </div><!-- /.box-header -->
                 <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div class="product-img">
                              <img src="https://omega.bildung-rp.de/images/omega.png" alt="Logo" title="Logo">
                            </div>
                            <div class="product-info">
                                <a target="_blank" href="https://omega.bildung-rp.de" class="product-title">OMEGA<span class="label label-primary pull-right">https://omega.bildung-rp.de</span></a>
                              <span class="product-description">
                                Online-Medien-Gesamtangebot des Landes Rheinland-Pfalz
                              </span>
                            </div>
                        </li><!-- /.item -->
                        <li class="item">
                            <div class="product-img">
                              <img src="http://bildung-rp.de/typo3temp/_processed_/csm_bsrss_d0d2438a43.gif" alt="Logo" title="Logo">
                            </div>
                            <div class="product-info">
                                <a target="_blank" href="http://www.bildung-rp.de" class="product-title">Bildungsserver<span class="label label-primary pull-right">http://www.bildung-rp.de</span></a>
                              <span class="product-description">
                                Rheinland-Pfalz
                              </span>
                            </div>
                        </li><!-- /.item -->
                        <li class="item">
                            <div class="product-img">
                              <img src="https://bildung-rp.de/fileadmin/_processed_/csm_120716_Banner_FortbildungOnline_klein_603b051cb2.jpg" alt="Logo" title="Logo">
                            </div>
                            <div class="product-info">
                                <a target="_blank" href="https://tis.bildung-rp.de" class="product-title">TIS<span class="label label-primary pull-right">https://tis.bildung-rp.de</span></a>
                              <span class="product-description">
                                Fortbildung Online
                              </span>
                            </div>
                        </li><!-- /.item -->
                    <li class="item">
                      <div class="product-img">
                        <img src="http://www.kmz-cochem.de/wp-content/uploads/2010/11/inMIS_beschriftet.png" alt="Logo" title="Logo">
                      </div>
                      <div class="product-info">
                          <a target="_blank" href="https://inmis.bildung-rp.de" class="product-title">inMis<span class="label label-primary pull-right">https://inmis.bildung-rp.de</span></a>
                        <span class="product-description">
                          MedienInformationsSystem des Landes Rheinland-Pfalz
                        </span>
                      </div>
                    </li><!-- /.item -->
                  </ul> 
                     <a > </a>
                </div>
             </div>
        </div>         
                 
                
        <div class="col-md-4 ">
            <div class="box box-primary">
                <div class="box-header with-border">
                      <h3 class="box-title">Allgemeine Informationen</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                <div class="box-body">
                    <strong>Datenschutzerklärung und Nutzungsbedingungen</strong><br>
                     Die Datenschutzerklärung und Nutzungsbedingungen für diese Lernplattform können Sie <a href="{$media_url}/docs/curriculum_Terms_Of_Use_2015.pdf">hier</a> einsehen. <br><br>
                     <strong>Ansprechpartner</strong><br>
                     Die Ansprechpartner für diese Zertifizierungsplattform können Sie unter folgender Emailadresse mail@joachimdieterich.de erreichen.<br> <br>
                     <strong>Impressum</strong><br>
                     Das Impressum dieses System können Sie <a href="http://joachimdieterich.de/index.php/impressum">hier</a> einsehen.
                </div>    
            </div>
        </div>         

    </div>
</section>                 
{/block}

{block name=sidebar}{$smarty.block.parent}{/block}
{block name=footer}{$smarty.block.parent}{/block}