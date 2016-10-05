<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">Lehrpläne</li>
            {if $my_enrolments != ''}
                {foreach item=cur_menu from=$my_enrolments name=enrolments}
                    {if $cur_menu->semester_id eq $my_semester_id}
                        {if  $cur_menu->id eq $cur_menu->base_curriculum_id || $cur_menu->base_curriculum_id eq null}
                            <li {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} class="active treeview"{/if}{/if}>

                                <a href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}">
                                    <i class="fa fa-dashboard"></i><span>{$cur_menu->curriculum}</span><small class="label pull-right bg-green">{$cur_menu->groups}</small>
                                </a>
                            </li>
                            {if {$smarty.foreach.enrolments.index} eq 4} 
                                <li class=" treeview"><a><span>Weitere Einträge</span><i class="fa fa-angle-left pull-right"></i></a> 
                                <ul class="treeview-menu" style="display: none;">
                                {assign var="submenu" value=true} 
                                {if {$smarty.foreach.enrolments.index} > 4} 
                                    <li {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} {/if}{/if}>
                                        <a href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}">
                                            <i class="fa fa-dashboard"></i><span>{$cur_menu->curriculum}</span><small class="label pull-right bg-green">{$cur_menu->groups}</small>
                                        </a>
                                    </li>
                                {/if}
                                
                            {/if}    
                        {/if}
                    {/if}
                {/foreach}
                {if $submenu eq true}
                    </li></ul>
                {/if}
            {else}<li><a href="">
                                <i class="fa fa-dashboard"></i><span>Sie sind in keinen Lehrplan <br>eingeschrieben</span>
                      </a></li>
            {/if}   
            
            <!-- Institution Menu -->
            {if checkCapabilities('menu:readMyInstitution', $my_role_id, false)}
                <li class="header">Institution: {$my_institution->institution}</li>
                {if checkCapabilities('menu:readObjectives', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'objectives'}active{/if}">
                    <a href="index.php?action=objectives&reset=true">
                        <i class="fa fa-edit"></i> <span>Lernstand eingeben</span>
                    </a>
                </li>
                {/if}
                {if checkCapabilities('menu:readCourseBook', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'courseBook'}active{/if}">
                    <a href="index.php?action=courseBook&reset=true">
                        <i class="fa fa-book"></i> <span>Kursbuch</span>
                    </a>
                </li>
                {/if}

                {if checkCapabilities('menu:readCurriculum', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'curriculum'}active{/if}">
                    <a href="index.php?action=curriculum&reset=true">
                        <i class="fa fa-th"></i> <span>Lehrpläne</span>
                    </a>
                </li>                  
                {/if}

                {if checkCapabilities('menu:readGroup', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'group'}active{/if}">
                        <a href="index.php?action=group&reset=true">
                            <i class="fa fa-group"></i><span>Lerngruppen</span>
                        </a>
                    </li>
                {/if}

                {if checkCapabilities('menu:readUser', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'user'}active{/if}">
                        <a href="index.php?action=user&reset=true">
                            <i class="fa fa-user"></i><span>Benutzer</span>
                        </a>
                    </li>
                {/if}

                {if checkCapabilities('menu:readRole', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'role'}active{/if}">
                        <a href="index.php?action=role&reset=true">
                            <i class="fa fa-key"></i><span>Rollenverwaltung</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readGrade', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'grade'}active{/if}">
                        <a href="index.php?action=grade">
                            <i class="fa fa-signal"></i><span>Klassenstufen</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readSubject', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'subject'}active{/if}">
                        <a href="index.php?action=subject&reset=true">
                            <i class="fa fa-language"></i><span>Fächer</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readSemester', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'semester'}active{/if}">
                        <a href="index.php?action=semester&reset=true">
                            <i class="fa fa-history"></i><span>Lernzeiträume</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readBackup', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'backup'}active{/if}">
                        <a href="index.php?action=backup&reset=true">
                            <i class="fa fa-cloud-download"></i><span>Backup</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readCertificate', $my_role_id, false)}   
                    <li class="treeview {if $page_action eq 'certificate'}active{/if}">
                        <a href="index.php?action=certificate&reset=true">
                            <i class="fa fa-files-o"></i><span>Zertifikate</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readInstitution', $my_role_id, false)}   
                    <li class="treeview {if $page_action eq 'institution'}active{/if}">
                        <a href="index.php?action=institution&reset=true">
                            <i class="fa fa-university"></i><span>Institutionen</span>
                        </a>
                    </li>
                {/if}
            {/if}
            
            {if checkCapabilities('menu:readLog', $my_role_id, false)}
            <li class="header">Administration</li>    
            
            <li {if $page_action eq 'log'}class="active"{/if}>
                <a href="index.php?action=log">
                    <i class="fa fa-list"></i><span>Berichte</span>
                </a>
            </li>
            {/if}
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>






