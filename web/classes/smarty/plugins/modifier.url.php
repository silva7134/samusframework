<?php
function smarty_modifier_url($text) {
    return str_replace(" ","_",$text);
}


?>
