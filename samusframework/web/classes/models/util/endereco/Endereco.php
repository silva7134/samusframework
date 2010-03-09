<?php

 
require_once 'models/util/endereco/Bairro.php';

class Endereco extends Samus_Model {

	/**
	 * Nome da rua
	 *
	 * @var string VARCHAR(120)
	 */
	protected $logradouro;
	
	/**
	 * Numero da casa ou apartamento
	 *
	 * @var string VARCHAR(25)
	 */
	protected $numero;
	
	/**
	 * Complemento do endereço
	 *
	 * @var string VARCHAR(120)
	 */
	protected $complemento;
	
	/**
	 * CEP válido do endereço 
	 *
	 * @var string VARCHAR(10)
	 */
	protected $cep;
	
	/**
	 * Bairro do endereço correspondente
	 *
	 * @var Bairro INTEGER(10)
	 */
	protected $bairro;

	
	/**
	 * @return Bairro
	 */
	public function getBairro() {
		return $this->bairro;
	}

	/**
	 * @return string
	 */
	public function getCep() {
		return $this->cep;
	}

	/**
	 * @return string
	 */
	public function getComplemento() {
		return $this->complemento;
	}

	/**
	 * @return string
	 */
	public function getLogradouro() {
		return $this->logradouro;
	}

	/**
	 * @return string
	 */
	public function getNumero() {
		return $this->numero;
	}

	/**
	 * @param Bairro $bairro
	 */
	public function setBairro(Bairro $bairro) {
		$this->bairro = $bairro;
	}

	/**
	 * @param string $cep
	 */
	public function setCep($cep) {
		$this->cep = $cep;
	}

	/**
	 * @param string $complemento
	 */
	public function setComplemento($complemento) {
		$this->complemento = $complemento;
	}

	/**
	 * @param string $logradouro
	 */
	public function setLogradouro($logradouro) {
		$this->logradouro = $logradouro;
	}

	/**
	 * @param string $numero
	 */
	public function setNumero($numero) {
		$this->numero = $numero;
	}

	
	
}


?>