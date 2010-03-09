<?php
/**
 * Description of PaginaPodcast
 *
 * @author samus
 */
class PaginaPodcast extends Samus_Model {

    /**
     * Título do poadcast
     * @var string VARCHAR(120)
     */
    protected $titulo;

    /**
     * Descrição livre sobre o item
     * @var string TEXT
     */
    protected $descricao;

    /**
     * Subtítulo com mais explicações sobre o audio
     * @var string VARCHAR(200)
     */
    protected $subtitulo;

    /**
     * Data de postagem
     * @var string DATETIME
     */
    protected $data;

    /**
     * Tipos de dado permitido
     * @var string VARCHAR(30)
     */
    protected $type;

    /**
     * Autor do audio
     * @var string VARCHAR(120)
     */
    protected $author;

    /**
     * Palavras Chave separadas por virgula
     * @var string VARCHAR(120)
     */
    protected $keywords;

    /**
     * Nome do arquivo mo3
     * @var string VARCHAR(120)
     */
    protected $arquivo;

    /**
     * Nome original do arquivo
     * @var string VARCHAR(120)
     */
    protected $arquivoNomeOriginal;

    /**
     * Categoria do post
     * @var string VARCHAR(45)
     */
    protected $categoria;

    /**
     * Duração em segundos
     * @var string VARCHAR(60)
     */
    protected $duracao;

    /**
     * Duração em microsegundos
     * @var string VARCHAR(45)
     */
    protected $length;

    /**
     * Página do podcast
     * @var Pagina integer
     */
    protected $pagina;


    const DIR = 'files/podcast';

    /**
     * Array com os tipos de dado permitidos no momento audio/mpeg
     * @var array
     */
    protected $_typesArray = array(
        'mp3' => 'audio/mpeg'
    );

    /**
     * Diretório dos arquivos .mp3
     * @return string
     */
    public static function getDir() {
        return WEB_DIR . self::DIR . '/';
    }


    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getSubtitulo() {
        return $this->subtitulo;
    }

    public function setSubtitulo($subtitulo) {
        $this->subtitulo = $subtitulo;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function getArquivo() {
        return $this->arquivo;
    }

    public function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    public function getArquivoNomeOriginal() {
        return $this->arquivoNomeOriginal;
    }

    public function setArquivoNomeOriginal($arquivoNomeOriginal) {
        $this->arquivoNomeOriginal = $arquivoNomeOriginal;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function getDuracao() {
        return $this->duracao;
    }

    public function setDuracao($duracao) {
        $this->duracao = $duracao;
    }

    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function get_typesArray() {
        return $this->_typesArray;
    }

    public function set_typesArray($_typesArray) {
        $this->_typesArray = $_typesArray;
    }

    public function getPagina() {
        return $this->pagina;
    }

    public function setPagina(Pagina $pagina) {
        $this->pagina = $pagina;
    }



}
?>
