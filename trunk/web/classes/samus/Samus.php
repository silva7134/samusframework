<?php

/**
 * Samus Framework
 *
 * Nota: Utilizando Ajax no framework
 * Para utilizar ajax no frame com o jquery é muito simples, após incluir a lib
 * do jquery utilize o metodo  $.ajax: como no exemplo abaixo
 *        $.ajax({
 *          type: "POST",
 *          url: "processa.ajax.php",
 *          data: "nome=John&location=Boston",
 *          success: function(msg){
 *            $("#mensagem").html( "Data Saved: " + msg );
 *          }
 *        });
 *
 * O arquivo processa.ajax.php deve estar no diretorio padrão de arquivos de processamento
 * de ajax (ver arquivo de configuração) e deve ter a extensão .ajax.php
 * A msg retornada é tudo que foi impresso no arquivo de processamento
 *
 *
 * @author Vinicius Fiorio Custódio - Samusdev@gmail.com
 * @package Samus
 * @todo
 * Criar o método de geração de Admins
 * Adaptar as classes de geração de Admins para o padrão do Samus
 * Criar uma forma para geração de classses associativas
 * Melhorar a forma que a modelo resolve atributos multivalorados
 * Criar uma rotina que atualize e crie todas as tabelas de todos os modelos que estiverem na pasta
 */
class Samus {

    /**
     * Define uma conexão com o banco, as chaves abaixo devem ser especificadas
     * <ul>
     *  <li>NAME -> nome da tabela do banco de dados</li>
     *  <li>USER    -> usupario da base</li>
     *  <li>PASSWORD   -> senha</li>
     *  <li>ENGINE    -> engine, defaut InnoDB</li>
     *  <li>CHARSET    -> CHARSET, default latin1</li>
     *  <li>TABLE_PREFIX    -> prefixo do nome das tabelasm usado para criaçao e carregamento</li>
     * <li>HOST</li> -> Host ou ip do banco
     * </ul>
     * @var array
     * @static
     */
    private static $connection = array(
            "NAME" => '',
            "USER" => '',
            "PASSWORD" => '',
            'ENGINE' => '',
            'CHARSET' => '',
            'TABLE_PREFIX' => '',
            'HOST' => '' ,
            'ADAPTER' => '');

    /**
     * Diretorio dos controladores
     * @var string
     */
    private static $controlsDirectory = "classes/controls";

    /**
     * Diretorio dos modelos
     * @var string
     */
    private static $modelsDirectory = "classes/models";

    /**
     * Diretorio das visões
     * @var string
     */
    private static $viewsDirectory = "views";

    /**
     * Diretório com as visões compiladas
     * @var string
     */
    private static $compiledViewsDirectory = "views_c";

    /**
     * Diretorio padrão com os arquivos de processamento ajax
     * @var string
     */
    private static $ajaxPhpFilesDirectory = "ajax_php";

    /**
     * Diretório padrão dos arquivos javaScript
     * @var string
     */
    private static $javaScriptDirectory = "scripts";

    /**
     * Extensão dos arquivos de visão (defalt: .tpl)
     * @var string
     */
    private static $viewFileExtension = '.tpl';

    /**
     * Extensão dos arquivos de visão CSS (default: .tpl.css)
     * @var string
     *
     */
    private static $cssFileExtension = ".tpl.css";

    /**
     * Extensão dos arquivos de visão JavaScript (default: .tpl.js)
     * @var string
     */
    private static $javaScriptFileExtension = ".tpl.js";

    /**
     * Extensão dos arquivos de Modelo (extends Samus_Model)
     * @var string
     */
    private static $modelsFilesExtension = '.php';

    /**
     * Extensão padrão dos Controladores,
     * @var string
     */
    private static $controlsFileExtension = '.php';

    /**
     * Sufixo para o nome das classes de controle
     * @var string
     */
    private static $controlsClassSufix = "Controller";

    /**
     * Extensão padrão de arquivos de processamento ajax
     * @var string
     */
    private static $ajaxPhpFileExtension = '.ajax.php';

    /**
     * Extensão dos arkivos de include
     * @var string
     */
    private static $includeFileExtension = ".inc.php";

    /**
     * Delimitador esquerdo para supertags da views
     * @var string
     */
    private static $leftDelimiter = '${';

    /**
     * Delimitador direito para supertags das views
     * @var string
     */
    private static $rightDelimiter = '}';

    /**
     * Prefixo para o nome das tabelas do site, todas as tabelas criadas
     * automaticamente terão este prefixo no nome
     * @var string
     */
    private static $tablePrefix = "";

    /**
     * Nome Default das classes de Filtro
     * @var string
     */
    private static $defaultFilterClass = "__Filter";

    /**
     * Define se o projeto irá ou não utilizar funções de AutoLoad
     * @var boolean
     */
    public static $useAutoloadFunction = true;

    /**
     * Define o nome do arquivo de configurações globais que deve estar dentro
     * do diretorio de configuraçõe
     * @var string
     */
    private static $globalConfiurationFile = "configs/global_configuration.ini";

    /**
     * Array de configurações do arquivo global de configurações
     * @var array
     */
    private static $configurationArray = array();

    /**
     * Diretório default dos arquivos css
     * @var string
     */
    public static $defaultCSSDir = 'media/css/';

    /**
     * Diretório raiz dos arquivos de javascript
     * @var string
     */
    public static $defaultJavaScriptDir = 'scripts/';


    /**
     * Nó raiz xml padrão
     * @var unknown_type
     */
    public static $xmlRootNode = '<?xml version="1.0" encoding="UTF-8"?><root></root>';

    /**
     * Define as linguagens para internacionalização
     * @var array
     */
    public static $langs = array("pt");

    /**
     * @var string
     */
    private static $httpHost;

    /**
     * Array de configuração do HTML que é tratado pelo Samus_Keeper na hora da inclusão
     * @var array
     */
    public static $htmlConf = array(
            "doctype" => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//PT-BR">' ,
            "content_type" => 'text/html; charset=ISO-8859-1' ,
            "sf_ajax_js" => 'scripts/samus/sf.ajax.js' ,
            "jquery_file" => 'scripts/jquery/js/jquery-1.3.2.min.js'
    );

    /**
     * Configura o modo de execução das querys
     * @var string
     */
    private static $queryMode = "pdo";
    
    /**
     * Define o sufixo dos filtros, todas as classes de filtro devem conter o nome
     * da pasta atual seguido do nome Filter
     * @var string
     */
    private static $filterSufix = "Filter";

    /**
     * Define a string que fara a separação de metodos na url
     * @var string
     */
    private static $methodUrlSeparator = '.';
    
    /**
     * Define o separador dos parametros de um metodo de URL, o conteudo desse
     * parametro NÃO pode ter strins de separação de variaveis (-)
     * @var string
     */
    private static $methodUrlParameterSeparator = "=";
    
    /**
     * Define o sufixo dos metodos acessiveis pela url, todo metodo seguido de Action
     * pode ser acessado diretamente pela url acrecentando o separador de metodos
     * seguido do nome do metod que será executado na classe ANTES do metodo 
     * index, os metodos devem ser publicos
     * @var string
     */
    private static $methodUrlSufix = "Action";

    private static $decodeUTF8Strings = true;
    
    const SESSION_PAGE_NAME = "page";


	public function __construct() {

    }



    /**
     * Método que dispara o projeto
     * - Fax a conexão como banco utilizando o array $connection
     * - Especifica o TablePrefix
     * - Especifica a topLevelClass
     * - Faz o tratamento da URL
     * - Exibe o tratamento chamando a URL
     *
     * @return void
     */
    public static function init() {
        
        define("PROTOCOLO" , 'http://');
        
        self::$configurationArray = parse_ini_file(self::$globalConfiurationFile , true);

        if (!defined('WEB_DIR'))
            define('WEB_DIR', self::$configurationArray['project']['directory']);


        if (!defined("WEB_URL"))
            define("WEB_URL" , self::$configurationArray['project']['url']);



        if(!defined("SMARTY_DIR"))
            define("SMARTY_DIR", WEB_DIR . "classes/smarty/");


        set_include_path(
                WEB_DIR . 'classes/smarty/' . PATH_SEPARATOR .
                WEB_DIR . 'classes/' . PATH_SEPARATOR .
                "."
        );



        /*******************************************************************************
         * AUTO_LOAD
         * define se o método mágico para autoload de classes esta ativo
         ******************************************************************************/
        if (Samus::$useAutoloadFunction) {
            require_once 'util/AutoRequire.php';

            function __autoload($className) {
                AutoRequire::showSugests();
                AutoRequire::requireClass($className);
            }

        }

        
        include_once 'samus/Samus_Keeper.php';
        require_once 'samus/Samus_Exception.php';
        require_once 'samus/Samus_Model.php';
        require_once 'samus/Samus_Filter.php';
        require_once 'CRUD/Properties.php';
        require_once 'CRUD/TableFactory.php';
        require_once 'samus/Samus_Object.php';
        require_once 'database/Connection.php';
        require_once 'CRUD/CRUDQuery.php';
        require_once 'database/ConnectionMySqli.php';


        ////////////////////////////////////////////////////////////////////////
        // CONFIGURAÇÃO DA CONEXÃO
        // faz a configuração da conexão a partir do global_configuration.ini
        ////////////////////////////////////////////////////////////////////////

        self::setConnection(self::$configurationArray['connection']); // lê arquivo de conexão e realiza a conexão

        CRUDQuery::setModo(self::$connection["queryMode"]);

        if(self::$connection["queryMode"] == CRUDQuery::QUERY_MODE_MYSQLI) {

            ConnectionMySqli::connect(
                    self::$connection['name'] ,
                    self::$connection['user'] ,
                    self::$connection['password'],
                    self::$connection['host'],
                    self::$connection['charset'] ,
                    self::$connection['engine']
            );


            CRUDQuery::setConn(ConnectionMySqli::getConn()); // essalinha ta meio redundante verificar depois
            CRUD::setConn(ConnectionMySqli::getConn());

        } else {

            Connection::connect(
                    self::$connection['adapter']
                    , self::$connection['host']
                    , self::$connection['name']
                    , self::$connection['user']
                    , self::$connection['password']);

            CRUDQuery::setConn(Connection::getConn());
            CRUD::setConn(Connection::getPdo());
        }





        self::$tablePrefix = self::$connection["table_prefix"];



        CRUD::$tablePrefix = self::getTablePrefix();
        CRUD::$topLevelClass = "Samus_Model";

        ////////////////////////////////////////////////////////////////////////
        // EXIBINDO AS PÁGINAS
        // aqui o fazendeiro exibe as páginas
        ////////////////////////////////////////////////////////////////////////

        self::setHttpHost($_GET['httpHost']); 
        $samus_keeper = new Samus_Keeper($_GET['__cod'] , $_GET['httpHost']);

       

        $samus_keeper->displayPage();

    }

    /**
     * Encerra o projeto e a conexão
     * @return void
     */
    public static function close() {
        if(CRUDQuery::isPDOMode()) {
            Connection::close();
        } elseif(CRUDQuery::isMySqliMode()) {
            ConnectionMySqli::close();
        }
    }

    /**
     * Obtem o nome defaul das classes de filtro (Samus_Filter)
     * @return string
     */
    public static function getDefaultFilterClass() {
        return (string)self::$defaultFilterClass;
    }

    /**
     * Obtem o caminho do diretorio dos controladores
     *
     * @return string
     */
    public static function getControlsDirectory() {
        return self::$controlsDirectory;
    }

    /**
     * Obtem o caminho para o diretorio dos modelos
     *
     * @return string
     */
    public static function getModelsDirectory() {
        return self::$modelsDirectory;
    }

    /**
     * Obtém o caminho para os diretórios de Visão
     * @return string
     */
    public static function getViewsDirectory() {
        return self::$viewsDirectory;
    }

    /**
     * Obtém o caminho para os arquivos de views compiladas
     * @return string
     */
    public static function getCompiledViewsDirectory() {
        return self::$compiledViewsDirectory;
    }

    /**
     * Obtem a extensão dos arquivos de visão
     * @return string
     */
    public static function getViewFileExtension() {
        return self::$viewFileExtension;
    }

    /**
     * Obtem o array de confiugrações globais
     * @return array
     */
    public static function getConfigurationArray() {
        return self::$configurationArray;
    }

    /**
     * Array de configurações globais
     * @param array $configurationArray
     */
    public static function setConfigurationArray($configurationArray) {
        self::$configurationArray = $configurationArray;
    }

    /**
     * Especifica a extensão dos arquivos de visão
     * @param $viewFileExtension string
     * @return string
     */
    public static function setViewFileExtension($viewFileExtension) {
        self::$viewFileExtension = $viewFileExtension;
    }

    /**
     * Obtem o delimitador esquerdo das supertags da view
     * @return string
     */
    public static function getLeftDelimiter() {
        return (string)self::$leftDelimiter;
    }

    /**
     * Obtem o delimitador direito das supertags da view
     * @return string
     */
    public static function getRightDelimiter() {
        return (string)self::$rightDelimiter;
    }

    /**
     * Obtem a extensão padrão dos controladores (extends Samus_Controller)
     * @return string
     */
    public static function getControlsFileExtension() {
        return self::$controlsFileExtension;
    }

    /**
     * Obtem a extensão padrão dos Modelos (extends Samus_Model)
     * @return string
     */
    public static function getModelsFilesExtension() {
        return self::$modelsFilesExtension;
    }

    /**
     * @return string
     */
    public static function getAjaxPhpFilesDirectory() {
        return self::$ajaxPhpFilesDirectory;
    }

    /**
     * @return string
     */
    public static function getAjaxPhpFileExtension() {
        return self::$ajaxPhpFileExtension;
    }

    /**
     * Obtem o diretorio javaScript
     * @return string
     */
    public static function getJavaScriptDirectory() {
        return self::$javaScriptDirectory;
    }

    /**
     * Especifica o diretório padrão dos arquivos javaScript
     * @param $javaScriptDirectory
     * @return string
     */
    public static function setJavaScriptDirectory($javaScriptDirectory) {
        self::$javaScriptDirectory = $javaScriptDirectory;
    }

    /**
     * @return string
     */
    public static function getTablePrefix() {
        return self::$tablePrefix;
    }

    /**
     * Obtem o sufixo das classes de contrtroles
     * @return string
     */
    public static function getControlsClassSufix() {
        return self::$controlsClassSufix;
    }

    /**
     * Especifica o sufixo das classes de controle
     */
    public static function setControlsClassSufix($controlsClassSufix) {
        self::$controlsClassSufix = $controlsClassSufix;
    }

    /**
     * Obtem o nome do arquivo de configurações globais
     */
    public static function getGlobalConfiurationFile() {
        self::$globalConfiurationFile;
    }

    /**
     * Especifica o nome do arquivo de configuração
     * @param string $globalConfiurationFile
     */
    public static function setGlobalConfiurationFile($globalConfiurationFile) {
        self::$globalConfiurationFile = $globalConfiurationFile;
    }

    /**
     * Especifica o prefixo da tabela
     *
     * @param string $tablePrefix
     */
    public static function setTablePrefix($tablePrefix) {
        self::$tablePrefix = $tablePrefix;
    }

    /**
     * Obtem as linguagens do projeto
     * @return array
     */
    public static function getLangs() {
        return (array)self::$langs;
    }

    /**
     * Obtem um valor da url
     * @param $pos int
     * @return string
     */
    public static function getURL($pos="") {
        return Samus_Keeper::getUrl($pos);
    }

    /**
     * @see Samus_Keeper::getUrlVar
     * @param string $varName
     * @return mixed
     */
    public static function getURLVar($varName="") {
        return Samus_Keeper::getUrlVar($varName);
    }

    /**
     * Obtem a extensão dos arquivos de inclusão
     * @return string
     */
    public static function getIncludeFileExtension() {
        return self::$includeFileExtension;
    }

    /**
     * Especifica a extensão dos arquivos de inclusão
     * @param string $includeFileExtension
     * @return string
     */
    public static function setIncludeFileExtension($includeFileExtension) {
        return self::$includeFileExtension = $includeFileExtension;
    }

    /**
     * Retorna a configuração do BD
     * @return array
     */
    public static function getConnection() {
        return self::$connection;
    }

    /**
     * Especifica uma conexão
     * @see self::$connectino
     * @param array $connection
     */
    public static function setConnection(array $connection) {
        self::$connection = $connection;
    }

    /**
     *  Define se uma variável está ou não vazia conforme as determinações do
     * framework
     *
     * @param mixed $var
     * @return boolean
     */
    public static function isEmpty($var) {
        return CRUD::isEmpty($var);
    }


    /**
     * Retorna uma chamada para uma função javaScript incluindo as tags
     * <script>
     * @param string $functionName
     * @param array $params
     * @return string
     */
    public static function callJsFunction($functionName ,$param1="") {

        $str = '<script type="text/javascript">';
        $str .= $functionName.'(';

        foreach(func_get_args() as $key => $p) {
            if($key != 0) {
                if(is_numeric($p)) {
                    $str .= "$p,";
                } else {
                    $str .= "'$p',";
                }
            }
        }

        $str = substr($str, 0 , -1);

        $str .= ');';
        $str .= "</script>";

        return $str;
    }

    /**
     * Obtem a extensão de arquivos de template css
     * @return string
     */
    public static function getCssFileExtension() {
        return self::$cssFileExtension;
    }

    /**
     * Obtem a extensão dos arquivos de template JavaScript
     * @return string
     */
    public static function getJavaScriptFileExtension() {
        return self::$javaScriptFileExtension;
    }

    /**
     * Obtem o diretorio default de arquivos css
     * @return string
     */
    public static function getDefaultCSSDir() {
        return self::$defaultCSSDir;
    }

    /**
     * Exibe todos os erros: error_reporting(E_ALL);
     */
    public static function errorAll() {
        error_reporting(E_ALL);
    }

    /**
     * Especifica o modo de execuação das querys
     * @param string $queryMode
     */
    public static function setQueryMode($queryMode) {
        self::$queryMode = $queryMode;
    }

    /**
     * Obtem o modo de excução das querys configurado
     * @return string
     */
    public static function getQueryMode() {
        return self::$queryMode;
    }


    /**
     * Obtem o nome do controlador que esta sendo executado no momento (este método
     * so pode ser excutado dentro de controladores e filtros, fora do contexto
     * não retornará o nome da classe)
     *
     * Retorna o nome do controlador completamente qualificado:
     * Ex,: HomeController
     *
     * @return string nome do controladdor
     */
    public static function getControllerName() {
        return Samus_Keeper::getControllerName();
    }


    /**
     * Obtem o host
     * @return string
     */
    public static function getHttpHost() {
        return self::$httpHost;
    }

    /**
     * Especifica o host
     * @param string $httpHost
     */
    public static function setHttpHost($httpHost) {
        self::$httpHost = $httpHost;
    }
    
    /**
     * Obtem o sufixo das classes de filtro
     * @return string
     */
    public static function getFilterSufix() {
        return self::$filterSufix;
    }

    /**
	 * @return the $methodUrlSeparator
	 */
	public static function getMethodUrlSeparator() {
		return Samus::$methodUrlSeparator;
	}

	/**
	 * @return the $methodUrlSufix
	 */
	public static function getMethodUrlSufix() {
		return Samus::$methodUrlSufix;
	}
	
	/**
	 * Define se variaveis e strings devem ser decodificadas do UTF8
	 * @return boolean
	 */
	public static function isDecodeUTF8Strings() {
	    return self::$decodeUTF8Strings;
	}
	/**
	 * @return the $methodUrlParameterSeparator
	 */
	public static function getMethodUrlParameterSeparator() {
		return Samus::$methodUrlParameterSeparator;
	}

	
}

?>