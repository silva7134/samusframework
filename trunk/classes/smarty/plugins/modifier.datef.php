<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared', 'make_timestamp');
/**
 * Formata uma data com os mesmos parametros de date()
 *
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_make_timestamp()
 */
function smarty_modifier_datef($string, $format = 'd/m/y', $default_date = '') {
	if ($string != '') {
		$timestamp = new DateTime(smarty_make_timestamp($string));
	} elseif ($default_date != '') {
		$timestamp = new DateTime(smarty_make_timestamp($default_date));
	} else {
		return;
	}

	return $timestamp->format($format);

}

/* vim: set expandtab: */

?>
