<?php

class CampoExtraGrupo extends Samus_Model {

    const TEXTO_CURTO            = 1;
    const TEXTO_LONGO_COM_EDITOR = 2;
    const TEXTO_LONGO_SEM_EDITOR = 3;
    const RADIO_SIM_NAO =          4;
    const SELECT =                 5;

    /**
     * Também é o nome do campo
     *
     * @var string VARCHAR(120)
     */
    protected $nome;


    /**
     * Superlink do grupo de extras
     *
     * @var SuperLink INTEGER
     */
    protected $superLink;

    /**
     * Tipo de dado do campo
     * @var string INTEGER
     */
    protected $tipo;

    protected $campoExtra = array();

    /**
     * Metdados em diversos formatos usados para utilização nos diferentes
     * tipos
     * @var string TEXT
     */
    protected $metaDados;

    public function getCampoExtra() {
        return $this->campoExtra;
    }

    public function setCampoExtra($campoExtra) {
        $this->campoExtra = $campoExtra;
    }


    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @return SuperLink
     */
    public function getSuperLink() {
        return $this->superLink;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @param SuperLink $superLink
     */
    public function setSuperLink(SuperLink $superLink) {
        $this->superLink = $superLink;
    }

    /**
     * Obtem o tipo de dado
     * @return int
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Especifica o tipo
     * @param int $tipo
     * @return int
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }


    public function getMetaDados() {
        return $this->metaDados;
    }

    public function setMetaDados($metaDados) {
        $this->metaDados = $metaDados;
    }
    
    public function getMetaDadosSelectOptions($selecionado='') {
        $str = '';
        foreach(explode(',',  $this->metaDados ) as $m ) {
            $check = '';
            if($m==$selecionado) {
                $check = ' selected="selected" ';
            }
            $str .= '<option value="'.$m.'" '.$check.'>'.$m.'</option>';

        }
        return $str;
    }

}


?>