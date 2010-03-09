<?php
require_once 'models/conteudo/AlbumFoto.php';
require_once 'models/conteudo/PaginaCategoria.php';
require_once 'models/conteudo/SuperLink.php';
require_once 'models/conteudo/PaginaCampoExtra.php';
require_once 'models/conteudo/PaginaFoto.php';
require_once 'models/conteudo/PaginaArquivo.php';
require_once 'models/conteudo/PaginaConteudoComentario.php';
require_once 'models/conteudo/ConteudoComentario.php';

/**
 * Representa uma página de conteudo
 * @name Pagina
 */
class Pagina extends Samus_Model {

    /**
     * Define o site da página, independente do superlink que define as configurações o conteudoSite
     * @var ConteudoSite INTEGER
     */
    protected $conteudoSite;

    /**
     * Título da página criada
     * @var string VARCHAR(120) PRIMARY KEY NOT NULL
     */
    protected $titulo;

    /**
     * Define um subtitulo para o coteudo, pode também servir de chamada
     * @var string VARCHAR(120)
     */
    protected $subTitulo;

    /**
     * Título limpo gerado com CleanStrign
     * @var string VARCHAR(120) PRIMARY KEY
     */
    protected $cleanTitulo;


    /**
     * Aqui tem todo o conteúdo exibido na página
     * @var string TEXT
     */
    protected $texto;

    /**
     * SuperLink do conteudo criado
     * @var SuperLink INTEGER(10)
     */
    protected $superLink;

    /**
     * Data e hora que será exibida na página
     * @var string DATETIME
     */
    protected $dataExibicao;

    /**
     * Data e hora que a página foi criada
     * @var string DATETIME
     */
    protected $dataCriacao;

    /**
     * Data e hora da ultima modificação do arquivo
     * @var string DATETIME
     */
    protected $dataModificacao;

    /**
     * Se TRUE esta página não pode ser excluida do banco
     * @var boolean INTEGER(10)
     */
    protected $fechado;

    /**
     * Define se a página esta publicada ou não
     * @var int INTEGER(10)
     */
    protected $status;

    /**
     * Pagina pai
     * @var Pagina INTEGER(10)
     */
    protected $paginaPai;

    /**
     * Define se a página é um destaque, pode ser desabilitado conforme opção do superLink
     * @var boolean BOOLEAN
     */
    protected $destaque;

    protected $campoExtra = array();        //obtem um array de campos extras
    protected $categoria = array();         //obtem o array de categorias
    protected $conteudoComentario = array();

    /**
     * Ordem de exibição da página
     * @var int INTEGER
     */
    protected $ordem;

    const ON_DELETE = "CASCADE";

    const ON_UPDATE = "CASCADE";

    const STATUS_PUBLICADO = 1;

    const STATUS_INATIVO = 2;

    public function __construct($id="") {
        parent::__construct($id);
    }
    /**
     * Retorna uma instnacia de uma Pagina
     * @param int|string $id
     * @return Pagina
     */
    public static function getInstance($id="") {
        $p = new Pagina($id);
        return $p;
    }

    public function getTags() {
        return $this->getCO()->getTags();
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function getCampoExtra() {
        return $this->campoExtra;
    }

    public function setCampoExtra($campoExtra) {
        $this->campoExtra = $campoExtra;
    }

    /**
     * Cast de tipo
     * @param pagina $pagina
     * @return Pagina
     */
    public static function cast(Pagina $pagina) {
        return $pagina;
    }


    /**
     * @return ArquivoPasta
     */
    public function getArquivoPasta() {
        return $this->arquivoPasta;
    }

    /**
     * @return string
     */
    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    /**
     * @return string
     */
    public function getDataExibicao() {
        return $this->dataExibicao;
    }

    /**
     * @return string
     */
    public function getDataModificacao() {
        return $this->dataModificacao;
    }

    /**
     * @return int
     */
    public function getIndice() {
        return $this->indice;
    }



    /**
     * @return array
     */
    public function getPaginaCategorias() {
        return $this->paginaCategorias;
    }

    /**
     * @return SuperLInk
     */
    public function getSuperLink() {
        return $this->superLink;
    }

    /**
     * @return string
     */
    public function getTexto() {
        return stripslashes($this->texto);
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return stripslashes($this->titulo);
    }

    /**
     * @param ArquivoPasta $arquivoPasta
     */
    public function setArquivoPasta(ArquivoPasta $arquivoPasta) {
        $this->arquivoPasta = $arquivoPasta;
    }

    /**
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    /**
     * @param string $dataExibicao
     */
    public function setDataExibicao($dataExibicao) {
        $this->dataExibicao = $dataExibicao;
    }

    /**
     * @param string $dataModificacao
     */
    public function setDataModificacao($dataModificacao) {
        $this->dataModificacao = $dataModificacao;
    }

    /**
     * @param int $indice
     */
    public function setIndice($indice) {
        $this->indice = $indice;
    }


    /**
     * @return boolean
     */
    public function getFechado() {
        return $this->fechado;
    }

    /**
     * @param boolean $fechado
     */
    public function setFechado($fechado) {
        $this->fechado = $fechado;
    }


    /**
     * @param array $paginaCategorias
     */
    public function setPaginaCategorias($paginaCategorias) {
        $this->paginaCategorias = $paginaCategorias;
    }

    /**
     * @param SuperLInk $superLink
     */
    public function setSuperLink($superLink) {
        $this->superLink = $superLink;
    }

    /**
     * @param string $texto
     */
    public function setTexto($texto) {
        $this->texto = $texto;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }


    /**
     * @return array
     */
    public function getPaginaCampoExtras() {
        return $this->paginaCampoExtras;
    }

    /**
     * @param array $paginaCampoExtras
     */
    public function setPaginaCampoExtras($paginaCampoExtras) {
        $this->paginaCampoExtras = $paginaCampoExtras;
    }


    public static function getDir() {
        return WEB_DIR . "files/foto_pagina/";
    }

    /**
     * @return string
     */
    public function getCleanTitulo() {
        return $this->cleanTitulo;
    }

    /**
     * Especifica o título Limpo
     * @param string $cleanTitulo
     */
    public function setCleanTitulo($cleanTitulo) {
        $this->cleanTitulo = $cleanTitulo;
    }

    public function getOrdem() {
        return $this->ordem;
    }

    public function setOrdem($ordem) {
        $this->ordem = $ordem;
    }


    public function getComentario() {
        return $this->comentario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getConteudoComentario() {
        return $this->conteudoComentario;
    }

    public function setConteudoComentario($conteudoComentario) {
        $this->conteudoComentario = $conteudoComentario;
    }

    /**
     * Obtem o subTitulo
     * @return string
     */
    public function getSubTitulo() {
        return $this->subTitulo;
    }

    /**
     *
     * @param $subTitulo
     * @return string
     */
    public function setSubTitulo($subTitulo) {
        $this->subTitulo = $subTitulo;
    }

    /**
     *
     * @param boolean $object
     * @return Pagina|string|int
     */
    public function getPaginaPai($object=false) {
        if($object) {
            return new Pagina($this->paginaPai);
        } else {
            return $this->paginaPai;
        }
    }

    public function setPaginaPai($paginaPai) {
        $this->paginaPai = $paginaPai;
    }


    /**
     * @return PaginaCO
     */
    public function getCO() {
        require_once 'models/conteudo/PaginaCO.php';
        return parent::getCO();
    }

    /**
     * Obtem a foto principal (primeira postada)
     * @return PaginaFoto
     */
    public function getImg() {
        $fp = new PaginaFoto();
        $fp->getDao()->setAtributes('id' , 'foto' , 'external' , 'legenda' , 'nomeOriginal');
        $fp->getDao()->load("pagina=" . $this->getId() . " AND principal=1", "", 1);

        return $fp;
    }

    public function getDestaque() {
        return $this->destaque;
    }

    public function setDestaque($destaque) {
        $this->destaque = (boolean) $destaque;
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

    
    public function obterComentarios($order="id DESC" , $limit="") {


        $pc = new PaginaConteudoComentario();


        $comentarios = array();

        foreach($pc->getDao()->loadArrayList("pagina=" . $this->getId()) as $p) {
            /*@var $p PaginaConteudoComentario */
            if($p->getConteudoComentario()->getStatus() == ConteudoComentario::STATUS_ACEITO || $p->getConteudoComentario()->getStatus() == ConteudoComentario::STATUS_AGUARDANDO_ACEITACAO) {
                $comentarios[] = $p->getConteudoComentario();
            }
        }

        return $comentarios;

    }
    
    
    /**
     *
     * @return SuperLink
     */
    public function getSuperLinkObj() {

        if($this->superLink instanceof SuperLink) {
            return $this->superLink;
        } else {
            return new SuperLink($this->superLink);
        }
    }
    


}

?>