<?php

/**
 * Exibe uma div normal do html, mas adiciona uma variavel de  template "var"
 * e fecha a tag:
 * 
 * Ex.: << div class="blue" var=$text >>
 * 
 * Result: <div class="blue">Text Value</div>
 * 
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_count($params, &$smarty) {
    return count($params['var']);
}
?>
