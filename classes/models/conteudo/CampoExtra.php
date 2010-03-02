<?php
require_once 'models/conteudo/CampoExtraGrupo.php';

/**
 * Campos exstras de conteudo
 *
 */
class CampoExtra extends Samus_Model {

    /**
     * Conteudo do campo
     *
     * @var string TEXT
     */
    protected $texto;


    /**
     * Especifica o grupo que o campoExtra pertence
     *
     * @var CampoExtraGrupo INTEGER
     */
    protected $campoExtraGrupo;


    /**
     * Especifica a pagina deste CampoExtra
     * @var Pagina INTEGER
     */
    protected $pagina;

    /**
     * Obtem a Pagina deste CampoExtra
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


    /**
     * @return CampoExtraGrupo
     */
    public function getCampoExtraGrupo() {
        return $this->campoExtraGrupo;
    }

    /**
     * @return string
     */
    public function getTexto() {
        return $this->texto;
    }

    /**
     * @param CampoExtraGrupo $campoExtraGrupo
     */
    public function setCampoExtraGrupo(CampoExtraGrupo $campoExtraGrupo) {
        $this->campoExtraGrupo = $campoExtraGrupo;
    }

    /**
     * @param string $texto
     */
    public function setTexto($texto) {
        $this->texto = $texto;
    }




}


?>