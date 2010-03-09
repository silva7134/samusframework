<?php
require_once 'samus/Samus_Model.php';

/**
 * @author Vinicius Fiorio - samusdev@gmail.com
 */
class MailNome extends Samus_Model {

    /**
     * Email unico do cadastrado
     * @var string VARCHAR(120)
     */
    protected $email;

    /**
     * Nome qualquer
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Telefone
     * @var string VARCHAR(120)
     */
    protected  $telefone;

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * @return MailNomeCO
     */
    public function getCO() {
        return parent::getCO();
    }


}
?>
