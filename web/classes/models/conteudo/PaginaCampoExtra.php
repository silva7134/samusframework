<?php
require_once ('samus/Samus_Model.php');
require_once 'models/conteudo/CampoExtra.php';
require_once 'models/conteudo/Pagina.php';

/**
 * Classe associativa entre paginas e campos
 *
 */
class PaginaCampoExtra extends Samus_Model {

	/**
	 * Campo extra 
	 *
	 * @var CampoExtra INTEGER
	 */
	protected $campoExtra;
	
	/**
	 * Pagina do campoExtra
	 *
	 * @var Pagina INTEGER
	 */
	protected $pagina;
	
	/**
	 * @return CampoExtra
	 */
	public function getCampoExtra() {
		return $this->campoExtra;
	}

	/**
	 * @return Pagina
	 */
	public function getPagina() {
		return $this->pagina;
	}

	/**
	 * @param CampoExtra $campoExtra
	 */
	public function setCampoExtra(CampoExtra $campoExtra) {
		$this->campoExtra = $campoExtra;
	}

	/**
	 * @param Pagina $pagina
	 */
	public function setPagina(Pagina $pagina) {
		$this->pagina = $pagina;
	}




	
	
}


?>