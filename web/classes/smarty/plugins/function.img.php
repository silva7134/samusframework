<?php
require_once ("foto/Foto.php");

function smarty_function_img($params, &$smarty) {

    $src = $params["src"];

//    if(filter_var($src, FILTER_VALIDATE_URL)) {
//        return "<img src='$src' alt='$alt' width='" .$params['width']. "' class='".$params['class']."' style='".$params['style']."' />" ;
//    } else {
        return "<img src='../img-$src-".$params['width']."-".$params['height']."' alt='".$params['alt']."' class='".$params['class']."' style='".$params['style']."' />" ;
//    }


}
?>
