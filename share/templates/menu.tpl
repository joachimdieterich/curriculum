<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">Lernzeitraum</li>
            {if isset($mySemester) AND count($mySemester) > 1}
            <li class="treeview">
                <div class="dropdown"><i class="fa fa-calendar"></i>
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                          {$my_semester}
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu " aria-labelledby="dropdownMenu1" >
                            {section name=res loop=$mySemester}  
                                <li><a {*href="index.php?action={$page_action}&mySemester={$mySemester[res]->id}"*} onclick="setSemester({$mySemester[res]->id});">{$mySemester[res]->semester} ({$mySemester[res]->institution})</a></li>
                                <OPTION label="{$mySemester[res]->semester} ({$mySemester[res]->institution})" {if isset($my_semester_id)}{if $mySemester[res]->id eq $my_semester_id}selected{/if}{/if} ></OPTION>
                            {/section} 
                        </ul>
                </div>
            </li>
            {/if}
            
            {if checkCapabilities('menu:readMyCurricula', $my_role_id, false)}
            <li class="header">Lehrpläne</li>
            <li class="treeview">
                {if $my_enrolments != ''}
                    {foreach item=cur_menu from=$my_enrolments}
                        {if $cur_menu->semester_id eq $my_semester_id}
                        <li {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} class="active"{/if}{/if}>
                            <a href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}">
                                <i class="fa fa-dashboard"></i><span>{$cur_menu->curriculum}</span><small class="label pull-right bg-green">{$cur_menu->groups}</small>
                            </a>
                        </li>
                        {/if}
                    {/foreach}
                {else}<li><p>Sie sind in keinem Lehrplan eingeschrieben</p></li>
                {/if}   
            {/if} 
            
            <!-- Institution Menu -->
            {if checkCapabilities('menu:readMyInstitution', $my_role_id, false)}
                <li class="header">Institution</li>
                {if checkCapabilities('menu:readObjectives', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'objectives'}active{/if}">
                    <a href="index.php?action=objectives&reset=true">
                        <i class="fa fa-edit"></i> <span>Lernstand eingeben</span>
                    </a>
                </li>
                {/if}
                {if checkCapabilities('menu:readCourseBook', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'courseBook'}active{/if}">
                    <a href="index.php?action=coursebook&reset=true">
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
                            <i class="fa fa-calendar"></i><span>Lernzeiträume</span>
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
            
        </section>
        <!-- /.sidebar -->
      </aside>






