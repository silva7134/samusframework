<?php

/**
 * Lang controla exibições codicionais de conteudo 
 * 
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_lang($params, &$smarty) {
	return $params[$_SESSION["lang"]];
}
?>
