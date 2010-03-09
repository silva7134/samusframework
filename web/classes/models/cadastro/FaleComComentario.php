<?php
require_once 'samus/Samus_Model.php';
require_once 'util/Util.php';

/**
 * @author Vinicius Fiorio - samusdev@gmail.com
 */
class FaleComComentario extends Samus_Model {

    /**
     * Nome
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Email
     * @var string VARCHAR(120)
     */
    protected $email;

    /**
     * Comentário
     * @var string TEXT
     */
    protected $comentario;

    /**
     * Data e hora do cadastro
     * @var string DATETIME
     */
    protected $data;


    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        if(empty($data)) {
            $data = Util::dateTime();
        }
        $this->data = $data;
    }


    


}
?>
