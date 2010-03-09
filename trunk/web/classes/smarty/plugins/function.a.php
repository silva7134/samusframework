<?php

/**
 * SuperTag <a> 
 * 
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_a($params, &$smarty) {
	$str = "<a ";
	
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
	
	$str .= ">".$params["var"].$params[$_SESSION["lang"]]."</a>";
	return $str;
}
?>
