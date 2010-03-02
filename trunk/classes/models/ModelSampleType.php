<?php

/**
 * Sample de associação de tipo
 *
 * @author samus
 */
class ModelSampleType extends Samus_Model {

    /**
     * Nome do atributo
     * @var string VARCHAR(45)
     */
    private $nome;

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    
}
?>
