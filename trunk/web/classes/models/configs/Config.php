<?php 

/**
 * Classe Config - configurações do site
 * @author Vinicius Fiorio - samusdev@gmail.com
 * @package config
 * @name site_configs
 */
class Config extends Samus_Model {

    /**
     * Descrição do site para sistema de busca
     * @var string TEXT
     */
    protected $googleDescricao;

    /**
     * Palavras chaves
     * @var string MEDIUMTEXT
     */
    protected $googleKeywords;

    /**
     * Título do site
     * @var string VARCHAR(120)
     */
    protected $siteTitulo;

    /**
     * Email principal que recebera as mensagens
     * @var string VARCHAR(75)
     */
    protected $emailAdmin;

    /**
     * Host smtp para envio das mensagens
     *
     * @var string VARCHAR(60)
     */
    protected $emailHost;

    /**
     * Url do site sem o http
     *
     * @var string VARCHAR(60)
     */
    protected $siteUrl;

    /**
     * Nome da empresa, nome fantasia
     *
     * @var string VARCHAR(75)
     */
    protected $empresaNome;

    /**
     * Define para qual site esta configuração esta setada
     * @var ConteudoSite INTEGER
     */
    protected $conteudoSite;


    const EMAIL_HOST = "mail.samus.com.br";

    const DB_TABLE = "configs";

    /**
     * Descrição com as tags META do html
     *
     * @return string
     */
    public function exibirDescricao() {
        return '<META name="description" content="' . $this->getGoogleDescricao() . '" >';
    }

    /**
     * Keywords com as tags metas <meta>
     *
     * @return string <meta>
     */
    public function exibirKeywords() {
        return '<META name="keywords" content="' . $this->getGoogleKeywords() . ', samus , samusdev" >';
    }

    /**
     * @return string
     */
    public function getGoogleDescricao() {
        return $this->googleDescricao;
    }

    public function setGoogleDescricao($googleDescricao) {
        $this->googleDescricao = $googleDescricao;
    }

    /**
     * @return string
     */
    public function getGoogleKeywords() {
        return $this->googleKeywords;
    }

    public function setGoogleKeywords($googleKeywords) {
        $this->googleKeywords = $googleKeywords;
    }

    /**
     * @return string
     */
    public function getSiteTitulo() {
        return $this->siteTitulo;
    }

    public function setSiteTitulo($siteTitulo) {
        $this->siteTitulo = $siteTitulo;
    }

    /**
     * @return string
     */
    public function getEmailAdmin() {
        return $this->emailAdmin;
    }

    public function setEmailAdmin($emailAdmin) {
        $this->emailAdmin = $emailAdmin;
    }

    /**
     * @return string
     */
    public function getEmailHost() {
        return $this->emailHost;
    }

    /**
     * @param string $emailHost
     */
    public function setEmailHost($emailHost) {
        $this->emailHost = $emailHost;
    }

    /**
     * @param Config $config
     * @return Config
     */
    public static function cast(Config $config) {
        return $config;
    }

    /**
     * @return string
     */
    public function getEmpresaNome() {
        return $this->empresaNome;
    }

    /**
     * @param string $empresaNome
     */
    public function setEmpresaNome($empresaNome) {
        $this->empresaNome = $empresaNome;
    }

    /**
     * @return string
     */
    public function getSiteUrl() {
        return $this->siteUrl;
    }

    /**
     * @param string $siteUrl
     */
    public function setSiteUrl($siteUrl) {
        $this->siteUrl = $siteUrl;
    }

    public function getConteudoSite() {
        return $this->conteudoSite;
    }

    public function setConteudoSite(ConteudoSite $conteudoSite) {
        $this->conteudoSite = $conteudoSite;
    }


}
?>