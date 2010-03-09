<?php

require_once 'models/util/endereco/Estado.php';

class Cidade extends Samus_Model {

    /**
     * Nome completo da cidade
     *
     * @var string VARCHAR(120)
     */
    protected $nome;


    /**
     * Estado da cidade
     *
     * @var Estado INTEGER(5)
     */
    protected $estado;

    /**
     * Nome limpo da cidade, sem acentos nem caracteres especiais
     * @var string VARCHAR(120)
     */
    protected $cleanNome;

    /**
     * @return Estado
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @param Estado $estado
     */
    public function setEstado(Estado $estado) {
        $this->estado = $estado;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCleanNome() {
        return $this->cleanNome;
    }

    public function setCleanNome($cleanNome) {
        $this->cleanNome = $cleanNome;
    }

    /**
     * Obtem o Controlador da cidade
     * @return CidadeCO
     */
    public function getCO() {
        return parent::getCO();
    }


}


?>