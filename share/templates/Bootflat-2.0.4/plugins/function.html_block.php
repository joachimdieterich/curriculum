<?php
/*
 *     Smarty plugin
 * 
 * -------------------------------------------------------------
 * Smarty {html_paginator} plugin
 * File:        function.paginator.php
 * Type:        function
 * Name:        paginator
 * Description: Paginator renderer.
 *
 * -------------------------------------------------------------
 * @license GNU Public License (GPL)
 *
 * -------------------------------------------------------------
 * 
 */

function smarty_function_html_block($params, $template) {
    global $USER,$CFG;
    foreach($params as $_key => $_val) {
        switch ($_key) {
            case 'blockdata':   switch ($_val->block) {
                                    case 'moodle':  $width  = 'col-md-4';
                                                    $status = '';
                                                    foreach($params['blockdata'] as $key => $val) { $$key = $val; }
                                                    if ($USER->role_id === $role_id OR $role_id == $CFG->standard_role){
                                                    $html  = '<div class="'.$width.'">
                                                                <div class="panel panel-default '.$status.' dashbox">
                                                                    <div class="panel-heading">
                                                                          '.$name.'
                                                                          <div class="box-tools pull-right">';
                                                                            if (checkCapabilities('block:add', $USER->role_id, false)){
                                                                                $html  .= '<span class="fa fa-edit" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"></button>';
                                                                            }
                                                                            $html  .= '
                                                                          </div>
                                                                    </div><!-- /.panel-header -->
                                                                    <div class="panel-body text-center">
                                                                        <form target="_blank" action="'.$configdata.'" method="post">
                                                                           <div class="form-group has-feedback">
                                                                             <input type="text" name="username" class="form-control" placeholder="Benutzername">
                                                                           </div>
                                                                           <div class="form-group has-feedback">
                                                                             <input type="password" name="password" class="form-control" placeholder="Passwort">
                                                                           </div>
                                                                           <div class="row">
                                                                             <div class="col-xs-4">
                                                                               <button type="submit" class="btn btn-primary btn-block btn-flat">Anmelden</button>
                                                                             </div><!-- /.col -->
                                                                           </div>
                                                                         </form>
                                                                    </div>
                                                                </div>
                                                           </div>';
                                                        if ($visible == 1){
                                                            return $html; 
                                                        }
                                                    }
                                        break;
                                    case 'html':    $width  = 'col-md-4';
                                                    $status = '';
                                                    foreach($params['blockdata'] as $key => $val) { $$key = $val; }
                                                    if ($USER->role_id === $role_id OR $role_id == $CFG->standard_role){
                                                        $html  = '<div class="'.$width.'">
                                                                    <div class="panel panel-default '.$status.' dashbox" >
                                                                        <div class="panel-heading">
                                                                              '.$name.'
                                                                              <div class="box-tools pull-right">';
                                                                                if (checkCapabilities('block:add', $USER->role_id, false)){
                                                                                    $html  .= '<span class="fa fa-edit" data-widget="edit" onclick="formloader(\'block\',\'edit\','.$id.');"></span>';
                                                                                }
                                                                                $html  .= '
                                                                              </div>
                                                                        </div><!-- /.box-header -->
                                                                        <div class="panel-body" style="overflow: scroll; width: 100%; max-height: 300px;">
                                                                            '.$configdata.'
                                                                        </div>
                                                                    </div>
                                                               </div>';

                                                        if ($visible == 1){
                                                            return $html; 
                                                        }
                                                    }
                                        break;

                                    default:
                                        break;
                                }
                               //$_html = RENDER::$blockfunction($params);
                break;
            default: break;
        } 
    } 
    
     return $_html;
}