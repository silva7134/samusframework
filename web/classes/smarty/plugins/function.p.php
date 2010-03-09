<?php

/**
 * SuperTag <p>
 *
 * @param $params
 * @param $smarty
 * @return string
 */
function smarty_function_p($params, &$smarty) {
$str = "<p ";
	
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
	
	$str .= ">".$params["var"].$params[$_SESSION["lang"]]."</p>";
	return $str;
}
?>
