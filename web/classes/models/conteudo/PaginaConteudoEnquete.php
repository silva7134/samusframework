<?php

require_once ('samus/Samus_Model.php');

class PaginaConteudoEnquete extends Samus_Model {

	/**
	 * Pagina da enquete
	 * @var Pagina INTEGER
	 */
	protected $pagina;
	
	/**
	 * Enquete da página
	 * @var ConteudoEnquete INTEGER
	 */
	protected $conteudoEnquete;
	
	/**
	 * @return ConteudoEnquete
	 */
	public function getConteudoEnquete() {
		return $this->conteudoEnquete;
	}
	
	/**
	 * @return Pagina
	 */
	public function getPagina() {
		return $this->pagina;
	}
	
	/**
	 * @param ConteudoEnquete $conteudoEnquete
	 */
	public function setConteudoEnquete(ConteudoEnquete $conteudoEnquete) {
		$this->conteudoEnquete = $conteudoEnquete;
	}
	
	/**
	 * @param Pagina $pagina
	 */
	public function setPagina(Pagina $pagina) {
		$this->pagina = $pagina;
	}

	
}

?>