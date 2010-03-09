<?php


class User extends Samus_Model {

    /**
     * Nome do usuário
     * @var string VARCHAR(60)
     */
    protected $nomeCompleto;

    /**
     * Email do usuário
     * @var string VARCHAR(60)
     */
    protected $email;

    /**
     * Senha unica
     * @var string VARCHAR(45)
     */
    protected $senha;

    /**
     * Idade do usuário
     * @var int INTEGER
     */
    protected $idade;


    public function getNomeCompleto() {
	return $this->nomeCompleto;
    }

    public function setNomeCompleto($nomeCompleto) {
	$this->nomeCompleto = $nomeCompleto;
    }

    public function getEmail() {
	return $this->email;
    }

    public function setEmail($email) {
	$this->email = $email;
    }

    public function getSenha() {
	return $this->senha;
    }

    public function setSenha($senha) {
	$this->senha = $senha;
    }

    public function getIdade() {
	return $this->idade;
    }

    public function setIdade($idade) {
	$this->idade = $idade;
    }



}



?>
