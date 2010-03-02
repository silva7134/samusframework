<?php
require_once 'samus/Samus_ModelController.php';

/**
 * Controlador de Modelo MailNomeCO.php
 *
 * @author Vinicius Fiorio Custodio - samus@samus.com.br
 */
class MailNomeCO extends Samus_ModelController {

    
    public function salvarEmail($email , $nome='') {
        $mn = new MailNome();
        
        $mn->getDao()->load("email='$email'");
        $mn->setEmail($email);
        $mn->setNome($nome);
        return $mn->getDao()->save();
    }

    /**
     * Define se um e-mail existe no banxo
     * @return boolean
     */
    public function emailExiste($email) {
        $mn = new MailNome();
        $mn->getDao()->load("email='$email'");

        $e = $mn->getEmail();

        if(empty($e)) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * @return MailNome
     */
    public function getObject() {
        return $this->object;
    }

}
?>