<?php

/**
 * SuperTag <span> 
 * 
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_underline_to_upper($params, &$smarty) {
	return UtilString::underlineToUpper($params['string'].$params['texto']);
}
?>
