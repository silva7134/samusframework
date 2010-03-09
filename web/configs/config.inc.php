<?php
/*******************************************************************************
 * CONFIGURAÇÕES GLOBAIS
 ******************************************************************************/

header('Content-Type: text/html; charset=ISO-8859-1');
error_reporting(E_ERROR | E_PARSE | E_WARNING | E_COMPILE_WARNING | E_RECOVERABLE_ERROR  );
//error_reporting(E_ALL);
//error_reporting(0);


session_start();
session_cache_expire(60);