<?php
require_once 'samus/Samus_ModelController.php';
/**
 * Description of SuperLinkCO
 *
 * @author samus
 */
class SuperLinkCO extends Samus_ModelController {

    public function duplicarSuperLink(SuperLink $sl) {
        $sl->setId(null);
        $sl->setNome($sl->getNome()."2");
        $sl->setCleanNome($sl->getCleanNome()."2");
        return $sl->getDao()->save();
    }

    public function showConstants() {
        
        foreach($this->getObject()->getDao()->loadArrayList() as $c) {
            echo "define('SL_".str_replace(" ", "_", strtoupper( CleanString::clean( $c->getNome()) ))."' , ".$c->getId().");<br />";
        }
        
        
        
    }
    
    /**
     * Ontem as categorias desse superLink
     * @return array
     */
    public function getCategorias() {
        $cat = new Categoria();
        return $cat->getDao()->loadArrayList("superLink=".$this->getObject()->getId());
    }
    

    /**
     *
     * @return SuperLink
     */
    public function getObject() {
        return $this->object;
    }

}

?>