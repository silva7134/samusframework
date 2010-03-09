<?php

/**
 * Clase associativa que agrupa as fotos de uma página
 * @name abum_fotos
 */
class AlbumFoto extends Samus_Model {

    /**
     * Nome do album
     *
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Descricao do album
     *
     * @var string TEXT
     */
    protected $descricao;

    protected $fotos = array();

    public function __construct($id="") {
        require_once 'models/conteudo/PaginaFoto.php';
        parent::__construct($id);
    }

    /**
     * Casting de tipo
     * @param AlbumFoto $albumFoto
     * @return AlbumFoto
     */
    public static function cast(AlbumFoto $albumFoto) {
        return $albumFoto;
    }

    /**
     * @return string
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }


    public function getPaginaFoto() {
        return $this->fotos;
    }

    public function setPaginaFoto($paginaFoto) {
        $this->fotos = $paginaFoto;
    }



}


?>