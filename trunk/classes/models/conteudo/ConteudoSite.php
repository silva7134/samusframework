<?php
require_once 'models/util/endereco/Cidade.php';
require_once 'models/dio/dio/Entidade.php';
require_once 'models/conteudo/ConteudoSiteCO.php';

/**
 * Description of ConteudoSite
 *
 * @author SamusDev - samus@samus.com.br
 */
class ConteudoSite extends Samus_Model  {

    /**
     * Nome do site
     * @var string VARCHAR(60)
     */
    protected $nome;

    /**
     * URL do site
     * @var string VARCHAR(140)
     */
    protected $url;

    /**
     * Cidade do site
     * @var Cidade INTEGER
     */
    protected $cidade;

    /**
     * Entidade do site
     * @var Entidade INTEGER
     */
    protected $entidade;

    /**
     * Define o arquivo da logo do site
     * @var string VARCHAR(120)
     */
    protected $logo;

    /**
     * Define se os superLinks globais devem ser utilizados
     * @var boolean BOOLEAN
     */
    protected $useGlobalSuperLinks;

    const DIR = "files/diario_logo/";
    
    public function getImg() {
        return self::getDir().$this->getLogo();
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return Cidade
     */
    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade(Cidade $cidade) {
        $this->cidade = $cidade;
    }

    /**
     * @return Entidade
     */
    public function getEntidade() {
        return $this->entidade;
    }

    public function setEntidade(Entidade $entidade) {
        $this->entidade = $entidade;
    }

    public static function getDir() {
        return WEB_DIR . self::DIR;
    }

    /**
     * @return ConteudoSiteCO
     */
    public function getCO() {
        return parent::getCO();
    }

    public function getLogo() {
        return $this->logo;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function getUseGlobalSuperLinks() {
        return (boolean) $this->useGlobalSuperLinks;
    }

    public function setUseGlobalSuperLinks($useGlobalSuperLinks) {
        $this->useGlobalSuperLinks = (boolean) $useGlobalSuperLinks;
    }

    

}
?>