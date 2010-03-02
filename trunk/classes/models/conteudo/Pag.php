<?php
require_once 'models/conteudo/PaginaConteudoTag.php';
require_once 'models/conteudo/ConteudoTag.php';

class Pag extends Samus_Model {

    protected $titulo;
    protected $subTitulo;
    protected $cleanTitulo;
    protected $texto;
    protected $superLink;
    protected $dataExibicao;
    protected $ordem;
    protected $paginaPai;
    protected $destaque;
    protected $conteudoSite;

    public $fotos = array();
    public $arquivosArray = array();
    public $extras = array();

    public $_categoria;

    /**
     * Retorna a foto principal da pagina
     * @return PaginaFoto
     */
    public function getImg() {
        return $this->getCO()->getImg();
    }

    /**
     * Retorna um array das fotos da pagina
     * @return array
     */
    public function getFotos() {
        return $this->getCO()->getFotos();
    }

    public function getArquivos() {
        return $this->getCO()->getArquivos();
    }

    /**
     *
     * @return PagCO
     */
    public function getCO() {
        require_once 'models/conteudo/PagCO.php';
        return parent::getCO();
    }

    public function getPaginaFoto() {
        return $this->fotos;
    }

    public function setPaginaFoto($paginaFoto) {
        $this->fotos = $paginaFoto;
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     *
     * @param $titulo
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     *
     * @return string
     */
    public function getCleanTitulo() {
        return $this->cleanTitulo;
    }

    /**
     *
     * @param $cleanTitulo
     */
    public function setCleanTitulo($cleanTitulo) {
        $this->cleanTitulo = $cleanTitulo;
    }

    /**
     *
     * @return string
     */
    public function getTexto() {
        return stripslashes($this->texto);
    }

    /**
     *
     * @param $texto
     */
    public function setTexto($texto) {
        $this->texto = $texto;
    }

    /**
     *
     * @return string
     */
    public function getSuperLink() {
        return $this->superLink;
    }

    /**
     *
     * @param $superLink
     */
    public function setSuperLink($superLink) {
        $this->superLink = $superLink;
    }

    /**
     *
     * @return string
     */
    public function getDataExibicao() {
        return $this->dataExibicao;
    }

    /**
     *
     * @param $dataExibicao
     */
    public function setDataExibicao($dataExibicao) {
        $this->dataExibicao = $dataExibicao;
    }

    public function getData() {
        return $this->getDataExibicao();

    }

    /**
     *
     * @return string
     */
    public function getOrdem() {
        return $this->ordem;
    }

    /**
     *
     * @param $ordem
     */
    public function setOrdem($ordem) {
        $this->ordem = $ordem;
    }

    /**
     * @return string
     */
    public function getSubTitulo() {
        return $this->subTitulo;
    }
    /**
     * @return string
     */
    public function setSubTitulo($subTitulo) {
        $this->subTitulo = $subTitulo;
    }

    public function getArquivosArray() {
        return $this->arquivosArray;
    }

    public function setArquivosArray($arquivosArray) {
        $this->arquivosArray = $arquivosArray;
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


    public function getPaginaPai($returnObject=false) {
        if($returnObject) {
            return new Pag( (int) $this->paginaPai);
        } else {
            return $this->paginaPai;
        }
    }

    public function setPaginaPai($paginaPai) {
        $this->paginaPai = $paginaPai;
    }

    public function getDestaque() {
        return $this->destaque;
    }

    public function setDestaque($destaque) {
        $this->destaque = $destaque;
    }

    public function setFotos($fotos) {
        $this->fotos = $fotos;
    }

    public function getExtras() {
        return $this->extras;
    }

    public function setExtras($extras) {
        $this->extras = $extras;
    }

    public function getConteudoSite() {
        return $this->conteudoSite;
    }

    public function setConteudoSite(ConteudoSite $conteudoSite) {
        $this->conteudoSite = $conteudoSite;
    }





}

