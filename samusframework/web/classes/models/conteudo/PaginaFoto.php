<?php

/**
 * Representa as fotos de uma página
 * @name pagina_fotos
 */
class PaginaFoto extends Samus_Model {

    /**
     * Nome do arquivo da foto
     * @var string VARCHAR(125)
     */
    protected $foto;

    /**
     * Legenda da foto exibida
     * @var string VARCHAR(120)
     */
    protected $legenda;

    /**
     * Uma imagem de uma fonte externa
     * @var string VARCHAR(255)
     */
    protected $external;

    /**
     * Nome original da foto armazenada
     * @var string VARCHAR(120)
     */
    protected $nomeOriginal;

    /**
     * Página que a foto pertence
     * @var Pagina INTEGER
     */
    protected $pagina;

    /**
     * Define se a foto é a imagem principal da página
     * @var boolean BOOLEAN
     */
    protected $principal;

    /**
     * Define se a foto deve ou naum ser redimencionada
     * @var int INTEGER(2)
     */
    protected $redimensionar;

    /**
     * Define o site das fotos
     * @var ConteudoSite INTEGER
     */
    protected $conteudoSite;


    public static $_defaultImg = 'default_img.jpg';

    const REDIMENSIONAR_SIM  = 1;

    const REDIMENSIONAR_NAUM = 2;

    const RELATIVE_DIR = "files/foto_pagina/";

    public function __construct($id="") {
        parent::__construct($id);
    }

    /**
     * Obtem o caminho da imagem
     * @return string
     */
    public function getImg() {

        if(empty($this->external)) {
            return WEB_DIR . self::RELATIVE_DIR . $this->getFoto();
        } else {
            return $this->getExternal();
        }

    }

    /**
     *
     * @return string
     */
    public function getImgWeb() {
        if(empty($this->external)) {
            return WEB_URL . self::RELATIVE_DIR . $this->getFoto();
        } else {
            return $this->getExternal();
        }
    }

    /**
     * Endereço da imagem default que deve ser carregada
     * @return string
     */
    public static function getDefaultImg() {
        return WEB_DIR . self::RELATIVE_DIR . self::$_defaultImg;
    }

    /**
     *
     * @return string
     */
    public static function getDefaultImgArray() {
        return array(
        "img" => self::getDefaultImg() ,
        "foto" => self::$_defaultImg ,
        "legenda" => "" ,
        "id" => "0"
        );
    }

    /**
     * @return string
     */
    public function getFoto() {
        return $this->foto;
    }

    /**
     * @return string
     */
    public function getLegenda() {
        return $this->legenda;
    }

    /**
     * @return string
     */
    public function getNomeOriginal() {
        return $this->nomeOriginal;
    }


    /**
     * @param string $foto
     */
    public function setFoto($foto) {
        $this->foto = $foto;
    }

    /**
     * @param string $legenda
     */
    public function setLegenda($legenda) {
        $this->legenda = $legenda;
    }

    /**
     * @param string $nomeOriginal
     */
    public function setNomeOriginal($nomeOriginal) {
        $this->nomeOriginal = $nomeOriginal;
    }

    public static function getDir() {
        return WEB_DIR."files/foto_pagina/";
    }

    /**
     * Obtem a pagina da foto
     * @return Pagina
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * Especifica a página
     * @param Pagina $pagina
     */
    public function setPagina(Pagina $pagina) {
        $this->pagina = $pagina;
    }

    public function getExternal() {
        return $this->external;
    }

    public function setExternal($external) {
        $this->external = $external;
    }

    public function getRelativeDir() {
        return self::RELATIVE_DIR;
    }

    public function getPrincipal() {
        return $this->principal;
    }

    public function setPrincipal($principal) {
        $this->principal = $principal;
    }
    
    /**
     * Define se a foto eh ou naum a principal 
     * @return boolean
     */
    public function isPrincipal() {
        return $this->getPrincipal();
    }


    /**
     * Obtem a imagem defaut
     * @return string
     */
    public static function get_defaultImg() {
        return self::$_defaultImg;
    }

    /**
     * Especifica a imagem default
     * @param string $defaultImg
     */
    public static function set_defaultImg($defaultImg) {
        self::$_defaultImg = $defaultImg;
    }
    
    public function getRedimensionar() {
        return $this->redimensionar;
    }

    public function setRedimensionar($redimensionar) {
        $this->redimensionar = $redimensionar;
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


    


}


?>