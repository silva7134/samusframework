<?php
require_once 'samus/Samus_ModelController.php';


/**
 * Controlador de Modelo ConteudoSiteCO.php
 *
 * @author Vinicius Fiorio Custodio - samus@samus.com.br
 */
class ConteudoSiteCO extends Samus_ModelController {

    /**
     * Obtem o Config do site
     * @return Config
     */
    public function getConfig() {
        $conf = new Config();
        $conf->getDao()->load("conteudoSite=".$this->object->getId());
        return $conf;

    }
    
    /**
     * Carrega um ConteudoSite pela cidade e a entidade
     * @param $cidadeId
     * @param $entidadeId
     * @return ConteudoSite|boolean
     */
    public function getConteudoSiteFromCidadeAndEntidade($cidadeId , $entidadeId) {
        $cs = new ConteudoSite();
        $cs->getDao()->load("cidade=$cidadeId AND entidade=$entidadeId ");
        $id = $cs->getId();
        if(empty($id)) {
            return false;
        } else {
            return $cs;    
        }
        
    }

    /**
     * @return ConteudoSite
     */
    public function getObject() {
        return $this->object;
    }




}
?>