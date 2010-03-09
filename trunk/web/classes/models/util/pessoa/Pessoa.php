<?php
 
require_once 'models/util/endereco/Endereco.php';
require_once 'models/util/telefone/Telefone.php';

class Pessoa extends Samus_Model {

    /**
     * Endereço da pessoa cadastrada
     *
     * @var Endereco INTEGER(10)
     */
    protected $endereco;


    /**
     * Nome completo da pessoa
     *
     * @var string VARCHAR(120) NOT NULL
     */
    protected $nome;

    /**
     * Email válido
     *
     * @var string VARCHAR(120) NOT NULL
     */
    protected $email;

    /**
     * Data de nascimento no formato aaaa/mm/dd
     *
     * @var string DATE
     */
    protected $nascimento;



    /**
     * @return string
     */
    public function getEmail() {
	return $this->email;
    }

    /**
     * @return Endereco
     */
    public function getEndereco() {
	return $this->endereco;
    }

    /**
     * @return string
     */
    public function getNascimento() {
	return $this->nascimento;
    }

    /**
     * @return string
     */
    public function getNome() {
	return  ucfirst(strtolower($this->nome));
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
	$this->email = $email;
    }

    /**
     * @param Endereco $endereco
     */
    public function setEndereco(Endereco $endereco) {
	$this->endereco = $endereco;
    }

    /**
     * @param string $nascimento
     */
    public function setNascimento($nascimento) {
	$this->nascimento = $nascimento;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
	$this->nome = $nome;
    }

	
}


?>