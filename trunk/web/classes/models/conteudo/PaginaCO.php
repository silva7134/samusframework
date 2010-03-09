<?php
require_once 'samus/Samus_ModelController.php';

/**
 * Controlador de Modelo PaginaCO.php
 *
 * @author Vinicius Fiorio Custodio - samus@samus.com.br
 */
class PaginaCO extends Samus_ModelController {

    public function getTags() {
        $ct = new PaginaConteudoTag();

        $tags = array();

        foreach($ct->getDao()->loadArrayList("pagina=".$this->object->getId(), "id DESC") as $pct) {
            $tags[] = $pct->getConteudoTag();
        }

        return $tags;
    }

    /**
     * @return Pagina
     */
    public function getObject() {
        return $this->object;
    }


    public function getTagString() {
        $ct = new PaginaConteudoTag();

        $tags = array();
        $ct->getDao()->setAtributes('id' , 'conteudoTag');
        $conteudoTag = new ConteudoTag();
        $conteudoTag->getDao()->setAtributes('nome');
        $strTag = "";
        foreach($ct->getDao()->loadArrayList("pagina=".$this->getObject()->getId(), "id DESC") as $pct) {
            $strTag .= $pct->getConteudoTag()->getNome().',';
        }

        $strTag = substr($strTag, 0, -1);

        return $strTag;
    }

    public function getEnquetes() {
        require_once 'models/conteudo/PaginaConteudoEnquete.php';
        $pe = new PaginaConteudoEnquete();
        $pe_array = $pe->getDao()->loadArrayList("pagina=" . $this->getObject()->getId());

        $enquetes = array();

        foreach($pe_array as $p) {
            $enquetes[] = $p->getConteudoEnquete();
        }


        return $enquetes;

    }


}
?>