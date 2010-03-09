<?php

 


class Pais extends Samus_Model {

	/**
	 * Nome do pais
	 *
	 * @var string VARCHAR(60)
	 */
	protected $nome;
	
	/**
	 * Sgla do pais correspondente
	 *
	 * @var string VARCHAR(10)
	 */
	protected $sigla;

	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @return string
	 */
	public function getSigla() {
		return $this->sigla;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome) {
		$this->nome = $nome;
	}

	/**
	 * @param string $sigla
	 */
	public function setSigla($sigla) {
		$this->sigla = $sigla;
	}

	
}


?>