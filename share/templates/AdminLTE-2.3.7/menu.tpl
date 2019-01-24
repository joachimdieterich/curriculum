<!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <div id="menu_top_placeholder"></div>
          <ul class="sidebar-menu">
            {if isset($myChildren)}
                <li class="header bg-light-blue">Meine Kinder</li>
                {Form::input_select('my_children', '', $myChildren, 'firstname, lastname', 'id', $my_child_id, '',"window.location.assign('index.php?action=children&reset=true&child_id='+this.value);" ,'Bitte auswählen...','col-xs-0', 'col-xs-12')}
            {/if}  
            <li class="header bg-light-blue">{$lang['SYS_CURRICULA']}</li>
            {if $my_enrolments != ''}
                {if ($cfg_guest_usr == $my_username) || ($my_role_name eq 'Indexer') || count($my_enrolments) > 10}
                    <select id="guest_menu" name="guest_menu" class="select2 form-control" onchange="location = this.value;">
                        <option value="false">Bitte Lehrplan wählen...</option>
                        {foreach item=cur_menu from=$my_enrolments name=enrolments}
                            {if $cur_menu->semester_id eq $my_semester_id}
                                <option label="{$cur_menu->curriculum}" value="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}" {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} selected="selected"{/if}{/if}>{$cur_menu->curriculum}</option>
                            {/if}
                        {/foreach}
                    </select>

                    {else}
                        {$menu_index = 0}
                        {foreach item=cur_menu from=$my_enrolments name=enrolments}
                            {if $cur_menu->semester_id eq $my_semester_id}
                                    {$menu_index = $menu_index + 1}
                                {if {$menu_index} neq 5} 
                                    <li {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} class="active treeview"{/if}{/if}>                                
                                        <a class="text-ellipse" href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}" >
                                            {*<span style="position: absolute;left: 0;top: 0;bottom:0px;right:0; background: url('{$access_file}{$cur_menu->icon_id|resolve_file_id:"t"}') center; background-size: cover; "></span>
                                            <span style="position: absolute;left: 0;top: 0;bottom:0px;right:0; background: {ak_convert_hex2rgba($cur_menu->color)};"></span>*}
                                            {*<i class="fa fa-dashboard"></i><span style="position: absolute; color:#FFF;">*}{$cur_menu->curriculum}{*</span>&nbsp;*}<span class="label pull-right bg-green">{$cur_menu->groups}</span>
                                        </a>

                                        <div class="progress xxs margin-bottom-none">
                                            <div class="progress-bar progress-bar-success" style="width: {$cur_menu->completed}%" role="progressbar" aria-valuenow="{$cur_menu->completed}" aria-valuemin="0" aria-valuemax="100">
                                              <span class="sr-only">{$cur_menu->completed}% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    {else}
                                    <li class=" treeview"><a><span>Weitere Einträge</span><i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu" style="display: none;">
                                        {assign var="submenu" value=true} 
                                        <li {if isset($page_curriculum )}{if ($page_curriculum eq $cur_menu->id) && ($page_group eq $cur_menu->group_id)} class="active treeview"{/if}{/if}>                                
                                        <a class="text-ellipse" href="index.php?action=view&curriculum_id={$cur_menu->id}&group={$cur_menu->group_id}" >
                                            {*<span style="position: absolute;left: 0;top: 0;bottom:0px;right:0; background: url('{$access_file}{$cur_menu->icon_id|resolve_file_id:"t"}') center; background-size: cover; "></span>
                                            <span style="position: absolute;left: 0;top: 0;bottom:0px;right:0; background: {ak_convert_hex2rgba($cur_menu->color)};"></span>*}
                                            {*<i class="fa fa-dashboard"></i><span style="position: absolute; color:#FFF;">*}{$cur_menu->curriculum}{*</span>&nbsp;*}<span class="label pull-right bg-green">{$cur_menu->groups}</span>
                                        </a>

                                        <div class="progress xxs margin-bottom-none">
                                            <div class="progress-bar progress-bar-success" style="width: {$cur_menu->completed}%" role="progressbar" aria-valuenow="{$cur_menu->completed}" aria-valuemin="0" aria-valuemax="100">
                                              <span class="sr-only">{$cur_menu->completed}% Complete</span>
                                            </div>
                                        </div>
                                    </li>
                                    {/if}  
                                
                            {/if}
                        {/foreach}
                    {/if}
                {if isset($submenu)}
                    {if $submenu eq true}
                        </li></ul>
                    {/if}
                {/if}
            {else}<li><a href="">
                                <i class="fa fa-dashboard"></i><span>Sie sind in keinen Lehrplan <br>eingeschrieben</span>
                      </a></li>
            {/if}   
            
            <!-- Institution Menu -->
            {if checkCapabilities('menu:readMyInstitution', $my_role_id, false)}
                <li class="header bg-light-blue">Institution</li>
                {if isset($mySemester) AND count($mySemester) > 1}
                    {Form::input_select('semester_id', '', $mySemester, 'semester, institution', 'id', $my_semester_id, null,  "processor('semester','set',this.getAttribute('data-id'));", '---', '', '')}                  
                {else if isset($my_institutions) AND count($my_institutions) > 1}
                    {Form::input_select('institution_id', '', $my_institutions, 'institution', 'institution_id', $my_institution_id, null, "processor('config','institution_id',this.getAttribute('data-id'));", '---', '', '')}                  
                {/if} 
                
                {if checkCapabilities('menu:readObjectives', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'objectives'}active{/if}">
                    <a href="index.php?action=objectives&reset=true">
                        <i class="fa fa-edit"></i> <span>Lernstand eingeben</span>
                    </a>
                </li>
                {/if}
                {if checkCapabilities('menu:readCourseBook', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'courseBook'}active{/if}">
                    <a href="index.php?action=courseBook">
                        <i class="fa fa-book"></i> <span>Kursbuch</span>
                    </a>
                </li>
                {/if}
                {if checkCapabilities('menu:readWallet', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'wallet'}active{/if}">
                    <a href="index.php?action=wallet">
                        <i class="fa fa-newspaper-o"></i> <span>Sammelmappe</span>
                    </a>
                </li>
                {/if}

                {if checkCapabilities('menu:readCurriculum', $my_role_id, false)}
                <li class="treeview {if $page_action eq 'curriculum'}active{/if}">
                    <a href="index.php?action=curriculum">
                        <i class="fa fa-th"></i> <span>Lehrpläne</span>
                    </a>
                </li>                  
                {/if}

                {if checkCapabilities('menu:readGroup', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'group'}active{/if}">
                        <a href="index.php?action=group">
                            <i class="fa fa-group"></i><span>Lerngruppen</span>
                        </a>
                    </li>
                {/if}

                {if checkCapabilities('menu:readUser', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'user'}active{/if}">
                        <a href="index.php?action=user">
                            <i class="fa fa-user"></i><span>Benutzerverwaltung</span>
                        </a>
                    </li>
                {/if}

                {if checkCapabilities('menu:readRole', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'role'}active{/if}">
                        <a href="index.php?action=role">
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
                        <a href="index.php?action=subject">
                            <i class="fa fa-language"></i><span>Fächer</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readSemester', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'semester'}active{/if}">
                        <a href="index.php?action=semester">
                            <i class="fa fa-history"></i><span>Lernzeiträume</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readBackup', $my_role_id, false)}
                    <li class="treeview {if $page_action eq 'backup'}active{/if}">
                        <a href="index.php?action=backup">
                            <i class="fa fa-cloud-download"></i><span>Backup</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readCertificate', $my_role_id, false)}   
                    <li class="treeview {if $page_action eq 'certificate'}active{/if}">
                        <a href="index.php?action=certificate">
                            <i class="fa fa-certificate"></i><span>Zertifikate</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readInstitution', $my_role_id, false)}   
                    <li class="treeview {if $page_action eq 'institution'}active{/if}">
                        <a href="index.php?action=institution">
                            <i class="fa fa-university"></i><span>Institutionen</span>
                        </a>
                    </li>
                {/if}
                {if checkCapabilities('menu:readSchooltype', $my_role_id, false)}   
                    <li class="treeview {if $page_action eq 'schooltype'}active{/if}">
                        <a href="index.php?action=schooltype">
                            <i class="fa fa-list-alt"></i><span>Schul-/Institutionstypen</span>
                        </a>
                    </li>
                {/if}
            {/if}
            
            {if checkCapabilities('menu:readLog', $my_role_id, false)}
            <li class="header bg-light-blue">Administration</li>    
            
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