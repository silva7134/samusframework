<?php

/**
 * Carrega um arquivo JavaScript
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_load_js($params, &$smarty) {
	return '<script type="text/javascript" src="'.WEB_DIR.Samus::getJavaScriptDirectory().'/'.$params['src'].'"></script>'; 
}
?>

