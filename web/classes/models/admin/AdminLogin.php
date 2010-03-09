<?php
require_once 'models/admin/AdminLoginCO.php';
require_once 'models/util/endereco/Cidade.php';
require_once 'models/dio/dio/Entidade.php';

/**
 * Login de usuários do Admin
 * @name admin_login
 */
class AdminLogin extends Samus_Model {

    const SESSION_NAME = "adminLoginId";
    const COOKIE_NAME  = "admin_login";
    const SESSION_CONTEUDO_SITE = "admin_conteudo_site";


    /**
     * Define o site que o Usuário pertence
     * @var ConteudoSite INTEGER
     */
    protected $conteudoSite;

    /**
     * Define um pai para cada um dos usuários
     * @var AdminLogin INTEGER
     */
    protected $adminLoginPai;

    /**
     * Representa o nome do
     * @var string VARCHAR(120)
     */
    protected $nome;

    /**
     * Email do usuário para enviar lembretes de senha
     * @var string VARCHAR(120)
     */
    protected $email;

    /**
     * Login
     * @var string VARCHAR(20)
     */
    protected $login;


    /**
     * Senha com hash
     * @var string VARCHAR(40) NOT NULL
     */
    protected $senha;

    /**
     * O sal é colocado junto com a string do login, antes de fazer ou comparar
     * hashs
     * @var string VARCHAR(30)
     */
    protected $sal;

    /**
     * Contador de acessos
     * @var int INTEGER
     */
    protected $acessos;

    /**
     * Marca a data do ultimo acesso
     * @var string DATETIME
     */
    protected $ultimoAcesso;

    /**
     * Privilégio de acesso do usuário, cada página do sistema tem um nível de
     * privilégio especificado, caso o privilégio do usuário não seja maior ou
     * igual ao privilégio necessario na página ele não tera acesso
     * @var int INTEGER
     */
    protected $privilegio;

    /**
     * Define se o usuário é master
     * @var boolean BOOLEAN
     */
    protected $isMaster;

    /**
     * Define o status do usuário
     * @var int INT(1)
     */
    protected $status;

    const STATUS_ATIVO = 1;
    
    const STATUS_EXCLUIDO = 2;


    public function estaLogado() {
        return $this->getCO()->estaLogado();
    }

    /**
     * Se o usuario é master
     * @return boolean
     */
    public function isMaster() {
        return $this->getIsMaster();
    }

    /**
     * Obtem o pribilégio
     * @return int
     */
    public function getPrivilegio() {
        return $this->privilegio;
    }

    /**
     * Especifica o privilégio
     * @param int $privilegio
     * @return int
     */
    public function setPrivilegio($privilegio) {
        $this->privilegio = (int) $privilegio;
    }


    /**
     * Gera o hash da senha
     *
     * @param string $senha
     * @return string senha com sha1
     */
    public static function hashSenha($senha) {
        return sha1($senha);
    }

    /**
     * Carrega o AdminLogin pelo valor da sessão
     *
     */
    public function loadAdminLoginBySessionId() {
        $this->getDao()->load((int) $_SESSION[self::SESSION_NAME][1]);
    }

    /**
     * Cria um novo sal
     *
     * @return string novo sal criado aleatoriamente
     */
    public function criarSal() {
        return rand(100,999) . rand(100,999) . date('d_m_y_s');
    }

    /**
     * Incrementa o numero de acessos e marca a data e hora do acesso
     *
     */
    public function contabilizarAcesso() {
        $this->setAcessos($this->getAcessos() + 1);
        $this->ultimoAcesso = Util::dateTime();
    }

    /**
     * Obem a senha salgada ja com o sal incluido
     *
     * @return sal
     */
    public function getSenhaSalgada() {
        return $this->getSal() . $this->getSenha();
    }

    /**
     * @return int
     */
    public function getAcessos() {
        return $this->acessos;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getSal() {
        return $this->sal;
    }

    /**
     * @return string
     */
    public function getSenha() {
        return $this->senha;
    }

    /**
     * @return string
     */
    public function getUltimoAcesso() {
        return $this->ultimoAcesso;
    }

    /**
     * @param int $acessos
     */
    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @param string $sal
     */
    public function setSal($sal) {
        $this->sal = $sal;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha) {
        $this->senha = $senha;
    }

    /**
     * @param string $ultimoAcesso
     */
    public function setUltimoAcesso($ultimoAcesso) {
        $this->ultimoAcesso = $ultimoAcesso;
    }

    public function getConteudoSite($forceObj=false) {
        if($forceObj) {

            if($this->conteudoSite instanceof ConteudoSite) {
                 return $this->conteudoSite;
            } else {
                return new ConteudoSite($this->conteudoSite);
            }

        } else {
            return $this->conteudoSite;
        }
        
    }

    public function setConteudoSite(ConteudoSite $conteudoSite) {
        $this->conteudoSite = $conteudoSite;
    }

    public function getAdminLoginPai() {
        return $this->adminLoginPai;
    }

    public function setAdminLoginPai(AdminLogin $adminLoginPai) {
        $this->adminLoginPai = $adminLoginPai;
    }

    public function getIsMaster() {
        return (boolean) $this->isMaster;
    }

    public function setIsMaster($isMaster) {
        $this->isMaster = (boolean) $isMaster;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Obtem o controlador de adminLogin
     * @return AdminLoginCO
     */
    public function getCO() {
        return parent::getCO();

    }
}


?>