<?php
 
require_once 'models/util/endereco/Cidade.php';

class Bairro extends Samus_Model {

	/**
	 * Nome do bairro
	 *
	 * @var string VARCHAR(120)
	 */
	protected $nome;
	
	/**
	 * Cidade onde o bairro fica localizado
	 *
	 * @var Cidade INTEGER(5)
	 */
	protected $cidade;

        /**
         * Salva o bairro mas antes verifica se ja existe
         */
        public function salvarEVerificar() {
            $this->getDao()->load("nome='$this->nome' AND cidade=$this->cidade");
            $this->getDao()->save();
        }
		
	/**
	 * @return Cidade
	 */
	public function getCidade() {
		return $this->cidade;
	}

	/**
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}

	/**
	 * @param Cidade $cidade
	 */
	public function setCidade(Cidade $cidade) {
		$this->cidade = $cidade;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome) {
		$nome = ucwords(strtolower($nome));
		$this->nome = $nome;
	}

	
}


?>