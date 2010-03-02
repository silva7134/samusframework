<?php
require_once 'samus/Samus_Model.php';

/**
 * SuperClasse do cadastro que finaliza o cadastro
 */
require_once 'models/util/pessoa/Pessoa.php';

/**
 * @author Vinicius Fiorio - samusdev@gmail.com
 */
class CadastroGeral extends Pessoa {

    /**
     * Especifica o sexo
     * @var string CHAR(1)
     */
    protected $sexo;

    /**
     * Observação qualquer sobre o cadastro
     * @var string VARCHAR(255)
     */
    protected $observacao;

    /**
     * Senha do usuário para acesso e comentários
     * @var string VARCHAR(55)
     */
    protected $senha;

    const MASCULINO  = "m";

    const OUTRO = "o";

    const NAUM_ESPECIFICADO = "n";

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        if($sexo==self::MASCULINO || $sexo==self::FEMININO || $sexo==self::OUTRO) {
            $this->sexo = $sexo;
        } else {
            $this->sexo = self::NAUM_ESPECIFICADO;
        }
        
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }


}
?>
