<?php
require_once ("flash/Swf.php");

function smarty_function_swf($params, &$smarty) {
	$swf = new Swf();
    return $swf->exibirSwf($params['src']
		, $params['width']
		, $params['height']
		, $params['wmode']
		, $params['quality']
		, $params['scale']
		, $params['title']);

}
?>