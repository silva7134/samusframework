<?php

/**
 * Classe ContatoSetor com os setores de contato
 * @author Vinicius Fiorio - samusdev@gmail.com
 * @package contato
 * @name contato_setores
 */
class ContatoSetor extends Samus_Model {

    /**
     * Nome do setor
     *
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Email do setor
     *
     * @var string VARCHAR(120)
     */
    protected $email;

    /**
     * @var ConteudoSite INTEGER
     */
    protected $conteudoSite;
    

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @return ConteudoSite
     */
    public function getConteudoSite() {
        return $this->conteudoSite;
    }

    public function setConteudoSite(ConteudoSite $conteudoSite) {
        $this->conteudoSite = $conteudoSite;
    }



    /**
     * @param ContatoSetor $contatoSetor
     * @return ContatoSetor
     */
    public static function cast(ContatoSetor $contatoSetor) {
        return $contatoSetor;
    }

}
?>
