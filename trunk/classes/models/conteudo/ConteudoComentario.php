<?php

require_once ('samus/Samus_Model.php');
require_once 'models/conteudo/PaginaConteudoComentario.php';

class ConteudoComentario extends Samus_Model {

    /**
     * Nome da pessoa que fez o comentсrio
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Email do enviador
     * @var string VARCHAR(120)
     */
    protected $email;

    /**
     * Data e hora do envio
     * @var string DATETIME
     */
    protected $data;

    /**
     * Comentсrio enviado
     * @var string TEXT
     */
    protected $comentario;

    /**
     * Status do comentario
     * @var int INTEGER
     */
    protected $status;

    /**
     * Define um comentсrio pai para funcionar como resposta
     * @var int INTEGER
     */
    protected $comentarioPai;

    /**
     * Pagina que foi feita o comentario
     * @var Pagina INTEGER
     */
    protected $pagina;

    /**
     * @var string  status de comentсrio aceito
     */
    const STATUS_ACEITO = 1;

    /**
     * @var string status de comentario cancelado
     */
    const STATUS_CANCELADO = 2;

    /**
     * @var string status de comentсrios recem feitos e aguardando aceitaчуo
     */
    const STATUS_AGUARDANDO_ACEITACAO = 3;

    protected $respostas = array();


    /**
     * @return string
     */
    public function getComentario() {
        return $this->comentario;
    }

    /**
     * @return string
     */
    public function getData() {
        return $this->data;
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
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $comentario
     */
    public function setComentario($comentario) {
        $this->comentario = $comentario;
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
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * Obtem todas as respostas de um determinado comentario
     * @return array
     */
    public function getComentariosRespostas() {

        if(empty($this->respostas)) {

            $this->respostas = $this->getDao()->loadArrayList("comentarioPai=".$this->getId());
        }

        return $this->respostas;
         
    }

    
    public static function findComentariosByPagina(Pagina $pagina , $status=1) {
        $cc = new ConteudoComentario();
        return $cc->getDao()->loadArrayList("pagina=$pagina AND status=$status" , 'id DESC');
    }

    /**
     * Obtem os ultimos comentсrios de um site
     * @param int $conteudoSite
     * @param int $status
     * @return array
     */
    public static function findUltimosComentarios($conteudoSite , $status=1) {

        $pagina = new Pagina();
        $pagina->getDao()->setAtributes('id' , 'titulo');
        $where = "";

        foreach ($pagina->getDao()->loadArrayList("conteudoSite=$conteudoSite") as $p) {
            $where .= " pagina=$p OR";
        }

        $where = substr($where , 0 , -2);

        $cc = new ConteudoComentario();
        if(!empty($where)) {
           return $cc->getDao()->loadArrayList(" ($where) AND status=$status" , 'id DESC');
        } else {
            return array();
        }
    }


    public static function findUltimosComentariosEPagina($conteudoSite , $status=1) {
        return self::findUltimosComentarios($conteudoSite , $status);
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     *
     * @return ConteudoComentario
     */
    public function getComentarioPai() {
        return $this->comentarioPai;
    }

    /**
     * @param ConteudoComentario $comentarioPai
     */
    public function setComentarioPai(ConteudoComentario $comentarioPai) {
        $this->comentarioPai = $comentarioPai;
    }

    public function getRespostas() {
        return $this->respostas;
    }

    public function setRespostas($respostas) {
        $this->respostas = $respostas;
    }

    public function getPagina() {
        return $this->pagina;
    }

    public function setPagina(Pagina $pagina) {
        $this->pagina = $pagina;
    }
}

?>