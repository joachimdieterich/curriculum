<?php

/**
 * Project:     SmartyPaginate: Pagination for the Smarty Template Engine
 * File:        SmartyPaginate.class.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.phpinsider.com/php/code/SmartyPaginate/
 * @copyright 2001-2005 New Digital Group, Inc.
 * @author Monte Ohrt <monte at newdigitalgroup dot com>
 * @package SmartyPaginate
 * @version 1.6
 */


class SmartyPaginate {

    /**
     * Class Constructor
     */
     function __construct() { }

    /**
     * initialize the session data
     *
     * @param string $id the pagination id
     * @param string $formvar the variable containing submitted pagination information
     */
     static function connect($id = 'default', $formvar = null) {
        if(!isset($_SESSION['SmartyPaginate'][$id])) {
            SmartyPaginate::reset($id);
            $_SESSION['SmartyPaginate'][$id]['pagi_selection'] = array();
        }
        
        $_formvar = isset($formvar) ? $formvar : $_GET;                         // use $_GET by default unless otherwise specified
        if(isset($_formvar['p_ord']) && isset($_formvar['updown'])) {           // set sort
                $_SESSION['SmartyPaginate'][$id]['pagi_orderby'] = $_formvar['p_ord'];
                $_SESSION['SmartyPaginate'][$id]['pagi_updown'] = $_formvar['updown'];
        }
		
        // set the current page
        $_total = SmartyPaginate::getTotal($id);
        if(isset($_formvar[SmartyPaginate::getUrlVar($id)]) && $_formvar[SmartyPaginate::getUrlVar($id)] > 0 && (!isset($_total) || $_formvar[SmartyPaginate::getUrlVar($id)] <= $_total))
            $_SESSION['SmartyPaginate'][$id]['current_item'] = $_formvar[$_SESSION['SmartyPaginate'][$id]['urlvar']];
    }

    /**
     * see if session has been initialized
     * @param string $id the pagination id
     */
    static function isConnected($id = 'default') {
        return isset($_SESSION['SmartyPaginate'][$id]);
    }    
        
    /**
     * reset/init the session data
     * @param string $id the pagination id
     */
    static function reset($id = 'default') {
        $_SESSION['SmartyPaginate'][$id] = array(
            'item_limit' => 10,
            'item_total' => null,
            'current_item' => 1,
            'urlvar' => 'next',
            'url' => $_SERVER['PHP_SELF'],
            'prev_text' => 'prev',
            'next_text' => 'next',
            'first_text' => 'first',
            'last_text' => 'last',
            'pagi_search' => null,
            'pagi_searchfield'=> null,
            'pagi_orderby' => null,
            'pagi_updown'=> null
            );
    }
    
    /**
     * clear the SmartyPaginate session data
     *
     * @param string $id the pagination id
     */
    static function disconnect($id = null) {
        if(isset($id))
            unset($_SESSION['SmartyPaginate'][$id]);
        else
            unset($_SESSION['SmartyPaginate']);
    }

    /**
     * set maximum number of items per page
     * @param string $id the pagination id
     */
    static function setLimit($limit, $id = 'default') {
        if(!preg_match('!^\d+$!', $limit)) {
            trigger_error('SmartyPaginate setLimit: limit must be an integer.');
            return false;
        }
        settype($limit, 'integer');
        if($limit < 1) {
            trigger_error('SmartyPaginate setLimit: limit must be greater than zero.');
            return false;
        }
        $_SESSION['SmartyPaginate'][$id]['item_limit'] = $limit;
    }    

    /**
     * get maximum number of items per page
     * @param string $id the pagination id
     */
    static function getLimit($id = 'default') {
        if (isset($_SESSION['SmartyPaginate'][$id]['item_limit'])){
            return $_SESSION['SmartyPaginate'][$id]['item_limit'];
        } else {return false;}
    }    
    static function getView($id = 'default') {
        if (isset($_SESSION['SmartyPaginate'][$id]['pagi_view'])){
            return $_SESSION['SmartyPaginate'][$id]['pagi_view'];
        } else {return 'table';}
    }    
            
    /**
     * setSort
     */
    static function setSort($porder, $so = "DESC", $id = 'default') {
            if(!$porder) {
                    trigger_error('SmartyPaginate setSort: You must set a entity-type from your database.');
                    return false;
            }
            if ($porder) {
                if (isset($_SESSION['SmartyPaginate'][$id]['pagi_orderby'])){
                        if ($_SESSION['SmartyPaginate'][$id]['pagi_orderby'] != $porder) { //if new orderby, sort ASC
                            $so = "ASC";
                        }
                }
                $_SESSION['SmartyPaginate'][$id]['pagi_orderby'] = $porder;
            }
            if (isset($_SESSION['SmartyPaginate'][$id]['pagi_orderby'])) {
                $_SESSION['SmartyPaginate'][$id]['pagi_updown'] = $so;
            }
            
    }
    static function setOrder($porder, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['pagi_orderby'] = $porder;
    }
    static function setView($view, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['pagi_view'] = $view;
    }
    static function setSearch($porder,  $search=null, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['pagi_orderby'] = $porder;
            
        if (isset($search)){
            $_SESSION['SmartyPaginate'][$id]['pagi_search'] = $search;
        }
    }
    static function setSearchField($searchfield, $id = 'default') {
        if (isset($searchfield)){
            $_SESSION['SmartyPaginate'][$id]['pagi_searchfield'] = $searchfield;
        }
    }
    
    static function setSelection($selection, $id = 'default'){
        switch ($selection) {
            case 'page':    $data = SmartyPaginate::_getData($id);
                            if (SmartyPaginate::getLimit($id) == count($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){
                                unset($_SESSION['SmartyPaginate'][$id]['pagi_selection']);
                            } else {
                                if (SmartyPaginate::getLimit($id) > SmartyPaginate::getTotal($id)){
                                    $limit = SmartyPaginate::getTotal($id);
                                } else {
                                    $limit = SmartyPaginate::getLimit($id);
                                }
                                unset($_SESSION['SmartyPaginate'][$id]['pagi_selection']);
                                for ($index = 0; $index <= $limit-1; $index++) {
                                    $_SESSION['SmartyPaginate'][$id]['pagi_selection'][] = $data[$index]->id;
                                }
                            }
                break;
            case 'all':     if (SmartyPaginate::getTotal($id) == count($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){
                                unset($_SESSION['SmartyPaginate'][$id]['pagi_selection']);
                            } else {
                                $_SESSION['SmartyPaginate'][$id]['pagi_selection'] = SmartyPaginate::_getSelectAll($id);
                            }
                break;
            case 'none':    if (isset($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){ //if selection > unselect all
                                unset($_SESSION['SmartyPaginate'][$id]['pagi_selection']);
                            } 
                break;    

            default:   if (isset($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){
                            if (is_array($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){
                                $match = array_search($selection, $_SESSION['SmartyPaginate'][$id]['pagi_selection']);      
                                if ($match === false){ // not in selection -> add id to selection
                                    $_SESSION['SmartyPaginate'][$id]['pagi_selection'][] = $selection;
                                } else { // in selection -> remove id from selection                
                                    unset($_SESSION['SmartyPaginate'][$id]['pagi_selection'][array_search($selection, $_SESSION['SmartyPaginate'][$id]['pagi_selection'])]);
                                    $_SESSION['SmartyPaginate'][$id]['pagi_selection'] = array_values($_SESSION['SmartyPaginate'][$id]['pagi_selection']);
                                }
                            } 
                        } else { 
                            $_SESSION['SmartyPaginate'][$id]['pagi_selection'] = array($selection);
                        }
                break;
        }
        if (isset($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){
            return $_SESSION['SmartyPaginate'][$id]['pagi_selection'];
        } else {
            return 0;
        }   
    }
    
    static function setSelectAll($selection, $id = 'default'){
        $_SESSION['SmartyPaginate'][$id]['pagi_selectAll'] = $selection;
    }
    
    static function setConfig($cfg, $id = 'default'){
        $_SESSION['SmartyPaginate'][$id]['pagi_cfg'] = $cfg;
    }

    static function setData($data, $id = 'default'){
        $_SESSION['SmartyPaginate'][$id]['pagi_data'] = $data;
    }

    /**
     * set the total number of items
     *
     * @param int $total the total number of items
     * @param string $id the pagination id
     */
    static function setTotal($total, $id = 'default') {
        if(!preg_match('!^\d+$!', $total)) {
            trigger_error('SmartyPaginate setTotal: total must be an integer.');
            return false;
        }
        settype($total, 'integer');
        if($total < 0) {
            trigger_error('SmartyPaginate setTotal: total must be positive.');
            return false;
        }
        $_SESSION['SmartyPaginate'][$id]['item_total'] = $total;
    }

    /**
     * get the total number of items
     *
     * @param string $id the pagination id
     */
    static function getTotal($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['item_total'];
    }    

    /**
     * set the url used in the links, default is $PHP_SELF
     *
     * @param string $url the pagination url
     * @param string $id the pagination id
     */
    static function setUrl($url, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['url'] = $url;
    }

    /**
     * get the url variable
     *
     * @param string $id the pagination id
     */
    static function getUrl($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['url'];
    }    
    /**
     * set width of paginator e.g. col-sm-12
     * @param int $width
     * @param int $id
     */
    static function setWidth($width, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['width'] = $width;
    }
    /**
     * get width of paginator
     * @param int $id
     * @return int
     */
    static function getWidth($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['width'];
    }   
    /**
     * 
     * @param string $col name of column
     * @param string $id the pagination id
     * @param boolean $status
     */
    static function setColumnVisibility($col, $id = 'default', $status = true){
        $_SESSION['SmartyPaginate'][$id][$col] = $status;
    }
    /**
     * 
     * @param string $col name of column
     * @param string $id the pagination id
     * @return boolean
     */
    static function getColumnVisibility($col, $id = 'default'){
        if (isset($_SESSION['SmartyPaginate'][$id][$col])){
            return $_SESSION['SmartyPaginate'][$id][$col];
        } else {
            return true; //if visibility is not set for this col return default == true
        }
    }
    /**
     * get all visible colums as array
     * @param type $id
     * @return type
     */
    static function getVisibleColumns($id = 'default'){
        $config  = SmartyPaginate::_getConfig($id);                // get config
        $visible = array();
        foreach($config AS $k => $v){
            if (SmartyPaginate::getColumnVisibility($k, $id) == true AND $k != '' AND $k !='p_options' AND $k != 'id'){
                $visible[$k] = $config[$k];
            }
        }
        return $visible;
    }
    
    /**
     * title of paginator (e.g. for printing
     * @param string $title
     * @param string $id
     */
    static function setTitle($title, $id = 'default'){
        $_SESSION['SmartyPaginate'][$id]['title'] = $title;
    }
    
    static function getTitle($id = 'default'){
        if (isset($_SESSION['SmartyPaginate'][$id]['title'])){
            return $_SESSION['SmartyPaginate'][$id]['title'];
        } else {
            return '';
        }
    }
    
    /**
     * set the url variable ie. ?next=10
     *                           ^^^^
     * @param string $url url pagination varname
     * @param string $id the pagination id
     */
    static function setUrlVar($urlvar, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['urlvar'] = $urlvar;
    }

    /**
     * get the url variable
     *
     * @param string $id the pagination id
     */
    static function getUrlVar($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['urlvar'];
    }    

        
    /**
     * set the current item (usually done automatically by next/prev links)
     *
     * @param int $item index of the current item
     * @param string $id the pagination id
     */
    static function setCurrentItem($item, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['current_item'] = $item;
    }

    /**
     * get the current item
     *
     * @param string $id the pagination id
     */
    static function getCurrentItem($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['current_item'];
    }    

    /**
     * get the current item index
     *
     * @param string $id the pagination id
     */
    static function getCurrentIndex($id = 'default') {
        
        return $_SESSION['SmartyPaginate'][$id]['current_item'] - 1;
         
    }    
    
    /**
     * get the last displayed item
     *
     * @param string $id the pagination id
     */
    static function getLastItem($id = 'default') {
        $_total = SmartyPaginate::getTotal($id);
        $_limit = SmartyPaginate::getLimit($id);
        $_last = SmartyPaginate::getCurrentItem($id) + $_limit - 1;
        return ($_last <= $_total) ? $_last : $_total; 
    } 
    
    /**
     * Reset sort/order and get index of last page to jumpt to that page after adding a new entry
     * @param string $id the pagination id
     */
    static function getLastPageIndexURL($id = 'default', $sort = 'id', $order = 'ASC'){ 
        $_total = SmartyPaginate::getTotal($id);
        $_limit = SmartyPaginate::getLimit($id);
        unset($_SESSION['SmartyPaginate'][$id]['pagi_search']);
        $_SESSION['PAGE']->target_url = removeUrlParameter($_SESSION['PAGE']->target_url, array($id,'order','sort'));
        SmartyPaginate::setSort($sort, $order, $id); //set sort/order of paginator to show new entry at the end / the begin of the list.
        return $_SESSION['PAGE']->target_url.'&order=ASC&sort=id&'.$id.'='.(($_total % $_limit > 0) ? ($_total  - ceil($_total % $_limit) +1) : $_total - $_limit +1 ); 
    }
    
    /**
     * assign $paginate var values
     *
     * @param obj &$smarty the smarty object reference
     * @param string $var the name of the assigned var
     * @param string $id the pagination id
     */
    static function assign(&$smarty, $var = 'paginate', $id = 'default') {
        if(is_object($smarty) && (strtolower(get_class($smarty)) == 'smarty' || is_subclass_of($smarty, 'smarty'))) {
            $_paginate['total'] = SmartyPaginate::getTotal($id);
            $_paginate['first'] = SmartyPaginate::getCurrentItem($id);
            $_paginate['last'] = SmartyPaginate::getLastItem($id);
            $_paginate['page_current'] = ceil(SmartyPaginate::getLastItem($id) / SmartyPaginate::getLimit($id));
            $_paginate['page_total'] = ceil(SmartyPaginate::getTotal($id)/SmartyPaginate::getLimit($id));
            $_paginate['size'] = $_paginate['last'] - $_paginate['first'];
            $_paginate['url'] = SmartyPaginate::getUrl($id);
            $_paginate['urlvar'] = SmartyPaginate::getUrlVar($id);
            $_paginate['current_item'] = SmartyPaginate::getCurrentItem($id);
            $_paginate['prev_text'] = SmartyPaginate::getPrevText($id);
            $_paginate['next_text'] = SmartyPaginate::getNextText($id);
            $_paginate['limit'] = SmartyPaginate::getLimit($id);
            
            $_item = 1;
            $_page = 1;
            while($_item <= $_paginate['total'])           {
                $_paginate['page'][$_page]['number'] = $_page;   
                $_paginate['page'][$_page]['item_start'] = $_item;
                $_paginate['page'][$_page]['item_end'] = ($_item + $_paginate['limit'] - 1 <= $_paginate['total']) ? $_item + $_paginate['limit'] - 1 : $_paginate['total'];
                $_paginate['page'][$_page]['is_current'] = ($_item == $_paginate['current_item']);
                $_item += $_paginate['limit'];
                $_page++;
            }
            $smarty->assign($var, $_paginate);
        } else {
            trigger_error("SmartyPaginate: [assign] I need a valid Smarty object.");
            return false;            
        }        
    }    

    
    /**
     * set the default text for the "previous" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
    static function setPrevText($text, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['prev_text'] = $text;
    }

    /**
     * get the default text for the "previous" page link
     *
     * @param string $id the pagination id
     */
   static function getPrevText($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['prev_text'];
    }    
    
    /**
     * set the text for the "next" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
   static function setNextText($text, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['next_text'] = $text;
    }
    
    /**
     * get the default text for the "next" page link
     *
     * @param string $id the pagination id
     */
   static function getNextText($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['next_text'];
    }    

    /**
     * set the text for the "first" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
   static function setFirstText($text, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['first_text'] = $text;
    }
    
    /**
     * get the default text for the "first" page link
     *
     * @param string $id the pagination id
     */
   static function getFirstText($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['first_text'];
    }    
    
    /**
     * set the text for the "last" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
   static function setLastText($text, $id = 'default') {
        $_SESSION['SmartyPaginate'][$id]['last_text'] = $text;
    }
    
    /**
     * get the default text for the "last" page link
     *
     * @param string $id the pagination id
     */
  static function getLastText($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['last_text'];
    }    
    
    /**
     * set default number of page groupings in {paginate_middle}
     *
     * @param string $id the pagination id
     */
  static  function setPageLimit($limit, $id = 'default') {
        if(!preg_match('!^\d+$!', $limit)) {
            trigger_error('SmartyPaginate setPageLimit: limit must be an integer.');
            return false;
        }
        settype($limit, 'integer');
        if($limit < 1) {
            trigger_error('SmartyPaginate setPageLimit: limit must be greater than zero.');
            return false;
        }
        $_SESSION['SmartyPaginate'][$id]['page_limit'] = $limit;
    }    

    /**
     * get default number of page groupings in {paginate_middle}
     *
     * @param string $id the pagination id
     */
   static function getPageLimit($id = 'default') {
        return $_SESSION['SmartyPaginate'][$id]['page_limit'];
    }
            
    /**
     * get the previous page of items
     *
     * @param string $id the pagination id
     */
   static function _getPrevPageItem($id = 'default') {
        
        $_prev_item = $_SESSION['SmartyPaginate'][$id]['current_item'] - $_SESSION['SmartyPaginate'][$id]['item_limit'];
        
        return ($_prev_item > 0) ? $_prev_item : false; 
    }    

    /**
     * get the previous page of items
     *
     * @param string $id the pagination id
     */
   static function _getNextPageItem($id = 'default') {
                
        $_next_item = $_SESSION['SmartyPaginate'][$id]['current_item'] + $_SESSION['SmartyPaginate'][$id]['item_limit'];
        
        return ($_next_item <= $_SESSION['SmartyPaginate'][$id]['item_total']) ? $_next_item : false; 
    }    
    
    /**
     * getSort
     */
   static function getSort($what, $id) {
		if ($what == "order" || $what == "sort" || $what == "search" || isset($id)) {
			if ($what == "sort") {
                               if (isset($_SESSION['SmartyPaginate'][$id]['pagi_updown'])){
				return $_SESSION['SmartyPaginate'][$id]['pagi_updown'];
                               } else {return '';}
			}
			if ($what == "order") {
                            if (isset($_SESSION['SmartyPaginate'][$id]['pagi_orderby'])){
                                return "ORDER BY ". $_SESSION['SmartyPaginate'][$id]['pagi_orderby']. " ";
                            } else {
				return false;
                            }
			}
			if ($what == "search") {
                            if (isset($_SESSION['SmartyPaginate'][$id]['pagi_search']) && isset($_SESSION['SmartyPaginate'][$id]['pagi_orderby'])){
                                return " AND ". $_SESSION['SmartyPaginate'][$id]['pagi_orderby']. " LIKE '%" .$_SESSION['SmartyPaginate'][$id]['pagi_search']. "%' ";
                            } else {
				return '';
                            }
			}
		} else {
			return " ";
		}
	}    
        
      static function _getSort($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_updown'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_updown'];
            } else {
                return '';
            }
      }
      static function _getOrder($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_orderby'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_orderby'];
            } else {
                return '';
            }
      }
      static function _getSearch($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_search'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_search'];
            } else {
                return null;
            }
      }
      static function _getSearchField($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_searchfield'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_searchfield'];
            } else {
                return null;
            }
      }
      static function _getSelection($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_selection'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_selection'];
            } else {
                return null;
            }
      }
      
      static function _getSelectAll($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_selectAll'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_selectAll'];
            } else {
                return null;
            }
      }
    
      static function _getConfig($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_cfg'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_cfg'];
            } else {
                return null;
            }
      }
      static function _getData($id = 'default'){
          if (isset($_SESSION['SmartyPaginate'][$id]['pagi_data'])){
                return $_SESSION['SmartyPaginate'][$id]['pagi_data'];
            } else {
                return null;
            }
      }        
}
?>