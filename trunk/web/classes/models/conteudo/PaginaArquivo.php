<?php
require_once 'samus/Samus_Model.php';
/**
 * Arquivos para download
 *
 * @name arquivos
 */
class PaginaArquivo extends Samus_Model {

    /**
     * Nome do arquivo armazenado no banco
     * @var string VARCHAR(25)
     */
    protected $arquivo;

    /**
     * Nome original do arquivo submetido pelo usuсrio
     * @var string VARCHAR(120)
     */
    protected $nomeOriginal;

    /**
     * Descriчуo do arquivo postado
     * @var string VARCHAR(250)
     */
    protected $descricao;

    /**
     * Url para linkar o anexo
     * @var string VARCHAR(120)
     */
    protected $url;

    /**
     * Pсgina que o arquivo pertence
     * @var Pagina INTEGER(10)
     */
    protected $pagina;


    public function __construct($id="") {
        parent::__construct($id);
    }

    public function getFile() {
        return  WEB_URL .'files/anexos/' . $this->arquivo;
    }

    /**
     * @return string
     */
    public function getArquivo() {
        return $this->arquivo;
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
    public function getNomeOriginal() {
        return $this->nomeOriginal;
    }

    /**
     * @param string $arquivo
     */
    public function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @param string $nomeOriginal
     */
    public function setNomeOriginal($nomeOriginal) {
        $this->nomeOriginal = $nomeOriginal;
    }

    public static function getDir() {
        return WEB_DIR . 'files/anexos/';
    }

    /**
     * @return Pagina
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @param Pagina $pagina
     */
    public function setPagina(Pagina $pagina) {
        $this->pagina = $pagina;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

}


?>