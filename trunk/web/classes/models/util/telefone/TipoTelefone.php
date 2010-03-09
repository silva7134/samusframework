<?php

 


/**
 * Representa o tipo de telefone de um numero geralmente,
 * - Residencial
 * - Trabalho
 * - Celular
 *
 */
class TipoTelefone extends Samus_Model {

	/**
	 * Nome do tipo de telefone
	 *
	 * @var string VARCHAR(45)
	 */
	protected $nome;

	
	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome) {
		$this->nome = $nome;
	}

	
	
}


?>