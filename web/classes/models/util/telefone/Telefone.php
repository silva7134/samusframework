<?php
require_once 'models/util/pessoa/Pessoa.php';
require_once 'models/util/telefone/DDD.php';
require_once 'models/util/telefone/TipoTelefone.php';


/**
 * Um telefone é formado por um DDD (previamente cadastrado), é de um Tipo
 * também previamente cadastrado (TipoTelefone), e pertence à uma pessoa
 *
 */
class Telefone extends Samus_Model {

    /**
     * Numero do telefone sem o DDD
     *
     * @var string VARCHAR(25)
     */
    protected $numero;

    /**
     * Código DDD do telefone
     *
     * @var int INTEGER(4)
     */
    protected $ddd;


    /**
     * Codigo da pessoa que possui o telefone
     *
     * @var Pessoa INTEGER(10)
     */
    protected $pessoa;

    /**
     * @return DDD
     */
    public function getDdd() {
        return $this->ddd;
    }

    /**
     * @param DDD $ddd
     */
    public function setDdd(DDD $ddd) {
        $this->ddd = $ddd;
    }

    /**
     * @return string
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * @param string $numero
     */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /**
     * @return Pessoa
     */
    public function getPessoa() {
        return $this->pessoa;
    }

    /**
     * @param Pessoa $pessoa
     */
    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
    }




    /**
     *
     * @return TipoTelefone
     */
    public function getTipoTelefone() {
        return $this->tipoTelefone;
    }

    /**
     *
     * @param $tipoTelefone
     */
    public function setTipoTelefone(TipoTelefone $tipoTelefone)	{
        $this->tipoTelefone = $tipoTelefone;
    }
}


?>