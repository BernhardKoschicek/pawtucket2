<?php
/** ---------------------------------------------------------------------
 * app/helpers/listHelpers.php : miscellaneous functions
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2011-2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This source code is free and modifiable under the terms of
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 * 
 * @package CollectiveAccess
 * @subpackage utils
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License version 3
 * 
 * ----------------------------------------------------------------------
 */

 /**
   *
   */
   
require_once(__CA_MODELS_DIR__.'/ca_lists.php');
require_once(__CA_MODELS_DIR__.'/ca_list_labels.php');
require_once(__CA_MODELS_DIR__.'/ca_list_items.php');

	
	# ---------------------------------------
	/**
	 * Fetch item_id for item with specified idno in list
	 *
	 * @param string $ps_list List code or list label
	 * @return int list_id of list or null if no matching list was found
	 */
	$g_list_id_cache = array();
	function caGetListID($ps_list) {
		global $g_list_id_cache;
		if(isset($g_list_id_cache[$ps_list])) { return $g_list_id_cache[$ps_list]; }
		$t_list = new ca_lists();
		
		if (is_numeric($ps_list)) {
			if ($t_list->load((int)$ps_list)) {
				return $g_list_id_cache[$ps_list] = $t_list->getPrimaryKey();
			}
		}
		
		if ($t_list->load(array('list_code' => $ps_list))) {
			return $g_list_id_cache[$ps_list] = $t_list->getPrimaryKey();
		}
		
		$t_label = new ca_list_labels();
		if ($t_label->load(array('name' => $ps_list))) {
			return $g_list_id_cache[$ps_list] = $t_label->get('list_id');
		}
		return $g_list_id_cache[$ps_list] = null;
	}
	# ---------------------------------------
	/**
	 * Fetch item_id for item with specified idno in list
	 *
	 * @param string $ps_list_code List code
	 * @param string $ps_idno idno of item to get item_id for
	 * @return int item_id of list item or null if no matching item was found
	 */
	$g_list_item_id_cache = array();
	function caGetListItemID($ps_list_code, $ps_idno) {
		global $g_list_item_id_cache;
		if(isset($g_list_item_id_cache[$ps_list_code.'/'.$ps_idno])) { return $g_list_item_id_cache[$ps_list_code.'/'.$ps_idno]; }
		$t_list = new ca_lists();
		
		return $g_list_item_id_cache[$ps_list_code.'/'.$ps_idno] = $t_list->getItemIDFromList($ps_list_code, $ps_idno);
	}
	# ---------------------------------------
	/**
	 * Fetch idno for item with specified item_id
	 *
	 * @param int $pn_item_id item_id of item to get idno for
	 * @return string idno of list item or null if no matching item was found
	 */
	$g_list_item_idno_cache = array();
	function caGetListItemIdno($pn_item_id) {
		global $g_list_item_idno_cache;
		if(isset($g_list_item_idno_cache[$pn_item_id])) { return $g_list_item_idno_cache[$pn_item_id]; }
		$t_item = new ca_list_items($pn_item_id);
		return $g_list_item_idno_cache[$pn_item_id] = $t_item->get('idno');
	}
	# ---------------------------------------
	/**
	 * Fetch display label in current locale for item with specified idno in list
	 *
	 * @param string $ps_list_code List code
	 * @param string $ps_idno idno of item to get label for
	 * @param bool $pb_return_plural If true, return plural version of label. Default is to return singular version of label.
	 * @return string The label of the list item, or null if no matching item was found
	 */
	$g_list_item_label_cache = array();
	function caGetListItemForDisplay($ps_list_code, $ps_idno, $pb_return_plural=false) {
		global $g_list_item_label_cache;
		if(isset($g_list_item_label_cache[$ps_list_code.'/'.$ps_idno.'/'.(int)$pb_return_plural])) { return $g_list_item_label_cache[$ps_list_code.'/'.$ps_idno.'/'.(int)$pb_return_plural]; }
		$t_list = new ca_lists();
		
		return $g_list_item_label_cache[$ps_list_code.'/'.$ps_idno.'/'.(int)$pb_return_plural] = $t_list->getItemFromListForDisplay($ps_list_code, $ps_idno, $pb_return_plural);
	}
	# ---------------------------------------
	/**
	 * Fetch item_id for item with specified label. Value must match exactly.
	 *
	 * @param string $ps_list_code List code
	 * @param string $ps_label The label value to search for
	 * @return int item_id of list item or null if no matching item was found
	 */
	$g_list_item_id_for_label_cache = array();
	function caGetListItemIDForLabel($ps_list_code, $ps_label) {
		global $g_list_item_id_for_label_cache;
		if(isset($g_list_item_id_for_label_cache[$ps_list_code.'/'.$ps_label])) { return $g_list_item_id_for_label_cache[$ps_list_code.'/'.$ps_label]; }
		$t_list = new ca_lists();
		
		return $g_list_item_id_for_label_cache[$ps_list_code.'/'.$ps_label] = $t_list->getItemIDFromListByLabel($ps_list_code, $ps_label);
	}
	# ---------------------------------------
	/**
	 * Fetch item_id for default item in list
	 *
	 * @param string $ps_list_code List code
	 * @return int item_id of list item or null if no default item was found
	 */
	$g_default_list_item_id_cache = array();
	function caGetDefaultItemID($ps_list_code) {
		global $g_default_list_item_id_cache;
		if(isset($g_default_list_item_id_cache[$ps_list_code])) { return $g_default_list_item_id_cache[$ps_list_code]; }
		$t_list = new ca_lists();
		
		return $g_default_list_item_id_cache[$ps_list_code] = $t_list->getDefaultItemID($ps_list_code);
	}
	# ---------------------------------------
?>