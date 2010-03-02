<?php
require_once 'util/UtilString.php';

function smarty_modifier_underline_to_space($string)
{
    return UtilString::underlineToSpace($string);
}

?>
