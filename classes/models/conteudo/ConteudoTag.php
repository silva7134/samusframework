<?php

require_once ('samus/Samus_Model.php');

class ConteudoTag extends Samus_Model {

/**
 * Nome unico da tag
 * @var string VARCHAR(60)
 */
    protected $nome;

    /**
     * Obtem todas as tags de uma determinada página
     * @param Pagina $pagina
     * @param string $order
     * @param string|int $limit
     * @return array
     */
    public static function getTagsFromPagina($pagina , $order="id ASC" , $limit="") {
        $pt = new PaginaConteudoTag();
        $pt->getDao()->setAtributes("id" , "conteudoTag");
        $tags = array();
        foreach($pt->getDao()->loadArrayList("pagina=$pagina", $order, $limit) as $p) {
            $tags[] = $p->getConteudoTag();
        }
        return $tags;
    }

    /**
     * Pontos representam o numero
     * @return int
     */
    public function getPontos() {
        $r = CRUD::query("SELECT count(*) as cont FROM ".Samus::getTablePrefix()."pagina_conteudo_tag WHERE conteudoTag=".$this->getId());
        
        return $r[0]['cont'];
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

}




?>