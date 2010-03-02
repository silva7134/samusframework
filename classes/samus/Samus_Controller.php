<?php
require_once 'Smarty.php';
require_once 'samus/Samus_ControllerInterface.php';

require_once 'samus/Samus_Model.php';
require_once 'samus/Samus_Object.php';


/**
 * Classes de Controle - Samus_Controller(controlador)
 * <br />
 * As modelos(classes Samus_Model) são nossos modelos queridos, mas são os controladors(classes Samus_Controller)
 * que botam pra fazer, nossos controladors também são ótimos reprodutores, gerando
 * instancias das nossas modelos para todo mundo ver. Mas nossos controladors são educados
 * e cuprem corretamente o que o Fazendeiro(Samus_Keeper) mandam ele fazer.
 * <br />
 * <br />
 * As classes Samus_Controller funcionam assim: qualquer propriedade da classe que estiver
 * ecapsulada (com seu getter e setter) poderá ser utilizado no contexo da
 * visão caso ela tenhao seu getter especificado (private $nome | getNome()),
 * caso o atributo seja publico ele também poderá ser utilizado no contexto da
 * visão. O arquivo de visão deve estar no diretório das views e deve ter o
 * mesmo nome da classe Samus_Controller, e importante a classe Samus_Controller deve implementar o método
 * index() (que faz parte da interface SamusController) este método é executado
 * sempre que a visão associada é chamada: <br />
 * Ex.:<br />
 * Controlador: <br  />
 * classes/controls/Conteudo.php <br />
 * class Conteudo extends Samus_Controller {<br />
 * 	   private $nome;<br />
 * 	   public  $valor = "um valor qualquer";
 *<br />
 *     public function index() {<br />
 *		   $this->setNome("Vinicius Fiorio Custódio");<br />
 *     }<br />
 *<br />
 *   public function getNome() {<br />
 *	    return $this->nome;<br />
 *	 }<br />
 *<br />
 *   public function setNome($nome) {<br />
 *      $this->nome = $nome;<br />
 *   }<br />
 *<br />
 * }<br />
 *
 * <br />
 * <br />
 * Visão: <br />
 * views/conteudo.tpl <br />
 * ...[[$nome]] is [[$valor]]..<br />
 * <br />
 *
 * @author Vinicius Fiorio Custódio
 * @package Samus
 */
abstract class Samus_Controller extends Samus_Object implements Samus_ControllerInterface {

    /**
     * Caso exista, o filtro do pacote
     *
     * @var mixed
     */
    protected $global;

    /**
     * Nome do template que será exibido
     *
     * @var string nome do arquivo template
     */
    private $templateFile = "";

    /**
     * Objeto smarty da página
     *
     * @var Smarty
     */
    protected $smarty;

    private $_decodeVars = true;

    private $mode; // define para qual tipo de arquivo este controlador é destinado

    const MODE_VIEW = "view";
    const MODE_CSS = "css";
    const MODE_JAVASCRIPT = "js";

    public $webDir;

    public $webURL;

    public function __construct($mode="view") {
        //parent::Smarty();
        $this->webDir = WEB_DIR;
        $this->webURL = WEB_URL;

        $this->mode = $mode;

        $this->smarty = Singleton::getInstance("Smarty");

        $this->smarty->left_delimiter = Samus::getLeftDelimiter();
        $this->smarty->right_delimiter = Samus::getRightDelimiter();
        $this->smarty->compile_dir  = Samus::getCompiledViewsDirectory();
        $this->smarty->template_dir = Samus::getViewsDirectory();
        $this->smarty->php_handling = SMARTY_PHP_ALLOW;
        $this->smarty->cache_dir = WEB_DIR . "cache";

        $this->smarty->config_dir = WEB_DIR . "configs";



        if($this->mode == self::MODE_VIEW) {

            /***********************************************************************
             *  ESPECIFICA OS VALORES DEFAULT
             *  valores default utilizados pelo `default_header`
             **********************************************************************/
            $this->smarty->assign('sf_default_doctype' , Samus::$htmlConf['doctype']);
            $this->smarty->assign("sf_default_content_type", Samus::$htmlConf['content_type']);

            $jqueryFile = WEB_URL . Samus::$htmlConf['jquery_file'];
            $this->smarty->assign("sf_jquery_file", $jqueryFile);

            $sfAjax = WEB_URL . Samus::$htmlConf['sf_ajax_js'];
            $this->smarty->assign("sf_ajax_js", $sfAjax);
        }
    }


    /**
     * Método responsável pela exibição da página, por aqui é feita a mágica de
     * colocar todas as propriedades encapsuladas na visão
     *
     * @param string $directory diretorio do template
     * @param string $metodo método de filtro
     * @param array $args argumentos do método de filtro
     */
    public function assignClass($directory="", $metodo="" ,array $args = array()) {

        //coloca na sessão a página atual
        $_SESSION[Samus::SESSION_PAGE_NAME] = "";
        foreach (Samus_Keeper::getUrl() as $url) {
            $_SESSION[Samus::SESSION_PAGE_NAME] .= $url."-";
        }
        $_SESSION[Samus::SESSION_PAGE_NAME] = substr($_SESSION[Samus::SESSION_PAGE_NAME],0,-1);
        ;


        $ref = new ReflectionClass($this);

        if(!empty($metodo)) {
            /*@var $refMetodo ReflectionMethod*/
            $refMetodo = $ref->getMethod($metodo);
            $refMetodo->invoke($this , $args);
        }

        $ref = new ReflectionObject($this);
        $met = $ref->getMethods();
        $ai = new ArrayIterator($met);
        $minhaArray = array();
        while ($ai->valid()) {

            $metName = $ai->current()->getName();

            if(substr($metName, 0, 3) == "get" && $metName{3} != "_") {
                $prop = substr($metName, 3);
                $prop = strtolower(substr($prop, 0, 1)) . substr($prop,1);

                $minhaArray[$prop] = $ai->current()->invoke($this);

                /**
                 * Atribui para a classe de visão todas as propriedades do
                 * objeto depois de executar o método especificado (aki é a
                 * chave do negócio)
                 */
                $this->smarty->assign($prop , $ai->current()->invoke($this));

            }

            $ai->next();
        }

        $properties = $ref->getProperties();

        foreach($properties as $prop) {
            /*@var $prop ReflectionProperty */
            if($prop->isPublic()) {
                $this->smarty->assign($prop->getName() , $prop->getValue($this));
            }
        }

        $this->assignGlobals();


        if(empty($this->templateFile) && $this->templateFile !== false) {

            /**
             * Exibe um template com o mesmo nome da classe especificada com a primeira
             * letra como minuscula
             */
            $templateName = UtilString::upperToUnderline(substr($ref->getName(),0,strlen(Samus::getControlsClassSufix())*-1));





            if($this->mode == self::MODE_VIEW) {

                /*******************************************************************
                 * DEFAULT CSS e JS
                 * sf_current_js
                 * sf_current_css
                 ******************************************************************/
                $cssFile = WEB_DIR . Samus::$defaultCSSDir. $directory . $templateName . '.css';


                /**
                 * @todo verificar se esta linha esta errada ta parecendo que na versão
                 * online não esta inserindo este arkivo
                 */
                if(file_exists($cssFile)) {
                    $this->smarty->assign("sf_current_css", WEB_URL . Samus::$defaultCSSDir. $directory . $templateName . '.css');
                }

                $jsFile =   WEB_DIR . Samus::$defaultJavaScriptDir. $directory . $templateName . '.js';
                if(is_file($jsFile)) {
                    $this->smarty->assign("sf_current_js", WEB_URL . Samus::$defaultJavaScriptDir. $directory . $templateName . '.js');
                }

                $realTemplateFile =  $directory	. $templateName	. Samus::getViewFileExtension();

            } elseif($this->mode == self::MODE_JAVASCRIPT) {
                $realTemplateFile =  WEB_DIR.$directory . $templateName . Samus::getJavaScriptFileExtension();
            } elseif($this->mode==self::MODE_CSS) {


                $realTemplateFile = WEB_DIR. Samus::getDefaultCSSDir(). $directory . $templateName . Samus::getCssFileExtension();




            } else {
                $realTemplateFile =  $directory . $templateName . Samus::getViewFileExtension();
            }



            $this->smarty->display($realTemplateFile);

        } elseif($this->templateFile===false) {

            $cssFile = WEB_DIR . Samus::$defaultCSSDir. $this->templateFile . '.css';
            if(is_file($cssFile)) {
                $this->smarty->assign("sf_current_css", $cssFile);
            }

            $jsFile =   WEB_DIR . Samus::$defaultJavaScriptDir. $this->templateFile . '.js';
            if(is_file($jsFile)) {
                $this->smarty->assign("sf_current_js", WEB_URL.Samus::$defaultJavaScriptDir. $this->templateFile . '.js');
            }


            $this->smarty->display("sf/empty.tpl");

        } else {
             /*******************************************************************
             * DEFAULT CSS e JS CONFORME O TEMPLATE ESPECIFICADO
             * sf_current_js
             * sf_current_css
             ******************************************************************/
            $cssFile = WEB_DIR . Samus::$defaultCSSDir. $this->templateFile . '.css';
            if(is_file($cssFile)) {
                $this->smarty->assign("sf_current_css", $cssFile);
            }

            $jsFile =   WEB_DIR . Samus::$defaultJavaScriptDir. $this->templateFile . '.js';
            if(is_file($jsFile)) {
                $this->smarty->assign("sf_current_js", WEB_URL .Samus::$defaultJavaScriptDir. $this->templateFile . '.js');
            }


            $this->smarty->display($this->templateFile);
        }



    }

    /**
     * Envia para o template tudo que for global
     */
    private function assignGlobals() {

        $constantes = get_defined_constants(true);

        if($this->_decodeVars) {
            $_GET = UtilString::utf8ArrayDecode($_GET);
        }



        $varsArray = array(
                "post" => $_POST ,
                "session" => $_SESSION ,
                "get" =>  $_GET ,
                "const" => $constantes['user'] ,
                "url" => Samus_Keeper::getURL() ,
                "urlVar" => Samus_Keeper::getURLVar()
        );


        $this->smarty->assign("samus", $varsArray);

    }




    /**
     * @return string
     */
    public function getTemplateFile() {
        return $this->templateFile;
    }

    /**
     * Especifica o arquivo que servira como visão
     * @param string $templateFile
     */
    public function setTemplateFile($templateFile) {
        if(substr($templateFile,count(Samus::getViewFileExtension())*-1,count(Samus::getViewFileExtension())) != Samus::getViewFileExtension()) {
            $templateFile .= Samus::getViewFileExtension();
        }

        $this->templateFile = $templateFile;
    }


    /**
     * @return mixed
     */
    public function getGlobal() {
        return $this->global;
    }

    /**
     * @param mixed $global
     */
    public function setGlobal($global) {
        $this->global = $global;
    }

    /**
     * Apelido para getGlobal() obtem a instancia do filtro do pacote
     * @return __Filter
     */
    public function getFilter() {
        return $this->getGlobal();
    }

    /**
     * @return Smarty
     */
    public function getSmarty() {
        return $this->smarty;
    }

    /**
     * @param Smarty $smarty
     */
    public function setSmarty($smarty) {
        $this->smarty = $smarty;
    }



}


?>