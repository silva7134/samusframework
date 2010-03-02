<?php
include_once 'models/contato/ContatoSetor.php';
/**
 * Classe Contato
 * @author Vinicius Fiorio - samusdev@gmail.com
 * @package contato
 * @name contatos
 */
class Contato extends Samus_Model {

    /**
     * Nome
     *
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Email do enviador
     *
     * @var string VARCHAR(120)
     */
    protected $email;

    /**
     * Telefone do enviador
     *
     * @var string VARCHAR(75)
     */
    protected $telefone;

    /**
     * Mensagem em html
     *
     * @var string LONGTEXT
     */
    protected $mensagem;

    /**
     * Data e hora do envio dd/mm/aaaa hh:mm:ss
     *
     * @var string DATETIME
     */
    protected $data;

    /**
     * Setor de destino do email
     *
     * @var ContatoSetor INTEGER
     */
    protected $contatoSetor;

    /**
     * Define o assunto da resposta automatica
     * @var string VARCHAR(120)
     */
    protected $respostaAutomaticaAssunto;

    /**
     * Define a mensagem da resposta automatica
     * @var string TEXT
     */
    protected $respostaAtuomaticaMensagem;

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
    }

    public function getDataFormatada() {
        return Util::converterData($this->getData()) . " às ". substr($this->getData() , -8 , 5);
    }

    /**
     * Obtem o nome do setor que recebeu a mensagem
     *
     * @return string
     */
    public function getSetorNome() {
        return $this->getContatoSetor()->getNome();
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getTelefone() {
        return $this->telefone;
    }

    /**
     * @param string $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string $mensagem
     */
    public function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }


    /**
     * @return ContatoSetor
     */
    public function getContatoSetor() {
        return $this->contatoSetor;
    }

    /**
     * Especifica o setor de contato
     *
     * @param ContatoSetor $contatoSetor
     */
    public function setContatoSetor(ContatoSetor $contatoSetor) {
        $this->contatoSetor = $contatoSetor;
    }

    /**
     * @param Contato $contato
     * @return Contato
     */
    public static function cast(Contato $contato) {
        return $contato;
    }

}
?>