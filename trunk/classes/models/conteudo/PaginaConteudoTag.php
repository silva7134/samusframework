<?php
require_once ('samus/Samus_Model.php');

class PaginaConteudoTag extends Samus_Model {

/**
 * Página que terá a tag
 *
 * @var Pagina INTEGER
 */
    protected $pagina;

    /**
     * Tag da págian
     *
     * @var ConteudoTag INTEGER
     */
    protected $conteudoTag;

    /**
     * @return ConteudoTag
     */
    public function getConteudoTag() {
        return $this->conteudoTag;
    }

    /**
     * @return Pagina
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * @param ConteudoTag $conteudoTag
     */
    public function setConteudoTag(ConteudoTag $conteudoTag) {
        $this->conteudoTag = $conteudoTag;
    }

    /**
     * @param Pagina $pagina
     */
    public function setPagina(Pagina $pagina) {
        $this->pagina = $pagina;
    }

    
    

}
