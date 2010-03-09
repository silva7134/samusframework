<?php

 
require_once 'models/util/endereco/Pais.php';

class Estado extends Samus_Model {

	/**
	 * Nome por extenso do estado
	 *
	 * @var string VARCHAR(75)
	 */
	protected $nome;
	
	/**
	 * Sigla do estado 
	 *
	 * @var string VARCHAR(5)
	 */
	protected $uf;
	
	/**
	 * Pais do estados
	 *
	 * @var Pais INTEGER(7)
	 */
	protected $pais;
	
	public function findEstadoByNome($nome) {
		
		$estados = $this->getDao()->loadArrayList("nome='$nome'");
		
		if(!empty($estados))
			return $estados[0];
		else 
			return null;
		
	}

	
	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @return Pais
	 */
	public function getPais() {
		return $this->pais;
	}

	/**
	 * @return string
	 */
	public function getUf() {
		return $this->uf;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * @param Pais $pais
	 */
	public function setPais(Pais $pais) {
		$this->pais = $pais;
	}

	/**
	 * @param string $uf
	 */
	public function setUf($uf) {
		$this->uf = $uf;
	}

	
}


?>