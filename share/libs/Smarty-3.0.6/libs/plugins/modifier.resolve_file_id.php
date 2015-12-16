<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty File ID resolve plugin
 * 
 * Type:     modifier<br>
 * Name:     resolve_file_id<br>
 * Purpose:  resolve file id to full Filepath 
 * 
 * @link 
 * @author Joachim Dieterich
 * @param string $ 
 * @return string 
 */
function smarty_modifier_resolve_file_id($id, $size= false, $alt=false)
{   if ($alt == false){
    global $CFG;
    $alt = $CFG->standard_avatar;
}
    $file       = new File();
    $file->id   = $id;
    $file->load();
    if (isset($file->filename)){
        switch ($size) {
            case 'xt':  return $file->file_version['xt']['full_path'];
                break;
            case 't':   return $file->file_version['t']['full_path'];
                break;
            case 'qs':  return $file->file_version['qs']['full_path'];
                break;
            case 'xs':  return $file->file_version['xs']['full_path'];
                break;
            case 's':   return $file->file_version['s']['full_path'];
                break;
            case 'm':   return $file->file_version['m']['full_path'];
                break;
            case 'l':   return $file->file_version['l']['full_path'];
                break;
            case false: return $file->full_path;
                break;

            default:
                break;
        }

    } else {
        return $alt;
    }
} 

?>