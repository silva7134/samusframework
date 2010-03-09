<?php
require_once 'samus/Samus_ModelController.php';

/**
 * Controlador de Modelo AdminLoginCO.php
 *
 * @author Vinicius Fiorio Custodio - samus@samus.com.br
 */
class AdminLoginCO extends Samus_ModelController {

    /**
     * Testa se o usuсrio esta logado no admin
     *
     * @return boolean
     */
    public function estaLogado() {
        //busca pela sessуo
        if(isset($_SESSION[AdminLogin::SESSION_NAME]) && $_SESSION[AdminLogin::SESSION_NAME][0] == "ok" && !empty($_SESSION[AdminLogin::SESSION_CONTEUDO_SITE]) ) {
            $this->object->loadAdminLoginBySessionId();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica a disponibilidade de um login, se um ID for informado ele serс
     * excluido da verificaчуo, isso щ usado para atualizaчуo de usuсrios
     *
     * @param string $login
     * @param int|boolean $id
     * @return boolean
     */
    public function verificarDisponibilidadeDoLogin($login , $id=false) {
        if($id===false) {
            $r = $this->getDao()->find("login='$login'");
            if(empty($r)) {
                return true;
            } else {
                return false;
            }
        } else {
            $r = $this->getDao()->find("login='$login' AND id!=$id");
            if(empty($r)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Obtem o ConteudoSite atual do Admin
     * @return ConteudoSite
     */
    public function getAdminConteudoSite() {
        return new ConteudoSite($_SESSION[AdminLogin::SESSION_CONTEUDO_SITE]);
    }

    /**
     * Obtem o adminLogin do usuсrio logado
     * @return AdminLogin
     */
    public function getAdminLoginLogado() {
        return new AdminLogin((int) $_SESSION[AdminLogin::SESSION_NAME][1]);
    }


}
?>