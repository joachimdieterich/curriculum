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
    if (isset($file->filename) AND isset($file->file_version[$size]['full_path'])){
            return $file->file_version[$size]['full_path'];
    } else if (isset($file->filename) AND is_array($file->file_version)){   // fallback if size is not available
        foreach (array_reverse($file->file_version) as $fv){                // array_reverse to have less traffic
                return $fv['full_path'];
        }
    } else {  
        return $alt;
    }
} 
?>