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
function smarty_function_div($params, &$smarty) {
	$str = "<div ";
	
	$ai = new ArrayIterator($params);
	while($ai->valid()) {
		
		$exibir = true;
		foreach(Samus::getLangs() as $lang) {
			if($ai->key() == $lang || $ai->key() == "var") {
				$exibir = false;
				break;
			}
		}
		
		if($exibir)
		$str .= $ai->key()."='".str_replace("'",'"',$ai->current())."' ";
		
		$ai->next();
	}
	
	$str .= ">".$params["var"].$params[$_SESSION["lang"]]."</div>";
	return $str;
}
?>
