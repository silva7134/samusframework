<?php
require_once 'util/Paginacao.php';

function smarty_function_paginacao($params, &$smarty) {
	
	$pag = new Paginacao($params["tabela"] 
						, $params["filtros"] 
						, $params["total"]);

 	$pag->setUrl($params["url"]);
	
	$pag->exibirPaginacao($params["gets"]);
	
}
?>
