<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Samus ucwords
 *
 * Type:     modifier<br>
 * Name:     ucwords<br>
 * Purpose:  converte a primeira letra para maiusculo
 * @author   Samus
 * @param string
 * @return string
 */
function smarty_modifier_ucwords($string) {
	return ucwords($string);
}

?>
