<?php

/**
 * CRUD Exception
 *
 * Captura as exeções lançadas pelo CRUD PHP
 *
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.1
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 */
class UploadException extends Exception {

   public function __construct($message, $code = 0) {
	  parent::__construct($message, $code);
   }

}

?>
