<?php
include_once 'samus/Samus_Controller.php';
require_once 'samus/Samus_Model.php';
require_once 'util/CleanString.php';
require_once 'models/util/endereco/Estado.php';
/**
 * Classe Samus_Keeper -
 * Seja um bom fazendeiro e tenha um Rebanho gordo e produtivo, o Fazendeiro vai
 * cuidar dos seus controladors de uma forma bem simples, mas ele trabalha bastante.
 * <br />
 * Ele é responsável por fazer o tratamento das URL (mod_rewrite do apache deve
 * estar habilitado pra tudo funcionar), tratando as URL ele sabe exatamente o
 * que deve executar, conforme os nome passadados ele vai incluir e mandar os
 * controladors executarem corretamente suas tarefas.
 * <br />
 * <br />
 * controladors são as classes "Samus_Controller", que são as classes de Controle <br />
 *
 * @author Vinicius Fiorio Custódio - Samusdev@gmail.com
 * @package Samus
 */
class Samus_Keeper extends Samus_Object {
	
	/**
	 * Array com as urls do site
	 *
	 * @var string[]
	 */
	private static $url;
	
	/**
	 * Um array de variaveis definidas na url Ex.:
	 *
	 * pagina.com/downloads-cod=3-pag=2
	 *
	 * @var array
	 */
	private static $urlVars = array ();
	
	/**
	 * Separador de valores na url
	 *
	 * @var string
	 */
	private $urlSeparator = "-";
	
	/**
	 * Separador de variaveis na url
	 * @var string
	 */
	private $urlVarSeparator = "=";
	
	/**
	 * Separador alternativo de variaveis na URL
	 * @var string
	 */
	private $urlAlternativeVarSeparator = ":";
	
	/**
	 * Página default que será usada em caso de erro
	 *
	 * @var string
	 */
	private $defaultPage = "index";
	
	/**
	 * Página que é exibida em caso de erro
	 *
	 * @var string
	 */
	private $errorPage = "index";
	
	/**
	 * Filtro do pacote atual
	 * @var __Filter
	 */
	private $filter;
	
	/**
	 * Nome do controlador atual
	 * @var string
	 */
	private static $controllerName;
	
	/**
	 * Construtor da classe, indica a regra de exibição da página
	 */
	function __construct($urlString) {
		$this->urlRule($urlString);
		$this->setErrorPage(WEB_URL . "index");
	}
	
	/**
	 * Regra de tratamento da url
	 *
	 */
	public function urlRule($urlString = '') {
		
		if (empty($urlString)) {
			$urlString = $_GET ['__cod'];
		}
		
		self::setUrl(explode($this->getUrlSeparator(), $urlString));
		
		foreach ( self::getUrl() as $u ) {
			$uArray = explode("=", $u);
			
			if (count($uArray) > 1) {
				self::$urlVars [$uArray [0]] = $uArray [1];
			}
			
			$uArray = explode(":", $u);
			
			if (count($uArray) > 1) {
				self::$urlVars [$uArray [0]] = $uArray [1];
			}
		
		}
		
		if (Samus::isDecodeUTF8Strings()) {
			foreach ( self::$urlVars as $k => $u ) {
				self::$urlVars [$k] = utf8_decode($u);
			}
		}
		
		$_GET = self::$urlVars;
	
	}
	
	/**
	 * Inclui o arquivo correto do site
	 *
	 * @param string $pageName
	 */
	public function displayPage() {
		
		$filterClass = "";
		$url = self::getUrl();
		
		if (empty($url [0]))
			$url [0] = $this->getDefaultPage();
		
		$urlDir = explode("/", $url [0]);
		
		$directory = "";
		
		if (count($urlDir) > 1) {
			$directory = "";
			
			$className = array_pop($urlDir);
			$className = ucfirst($className);
			
			// encontro os metodos da url
			$metodos = explode(Samus::getMethodUrlSeparator(), $className);
			
			$className = $metodos [0];
			
			unset($metodos [0]);
			
			foreach ( $urlDir as $dir ) {
				$directory .= $dir . "/";
			}
			
			/*******************************************************************
			 * CLASSE FILTRO
			 * classes de filtro devem ter o mesmo nome do pacote (mas com a
			 * primeira maiuscula) seguidas do sufixo definido em Samus::$filterSufix
			 * e são sempre inseridos e executados quando qualquer classe do pacote 
			 * são inseridas
			 ******************************************************************/
			$filterClass = ucfirst($urlDir [count($urlDir) - 1]);
			$filterClass .= Samus::getFilterSufix();
		
		} else {
			$className = ucfirst($url [0]);
			// encontro os metodos da url
			$metodos = explode(Samus::getMethodUrlSeparator(), $className);
			
			$className = $metodos [0];
			
			unset($metodos [0]);
		}
		
		/**********************************************************************
		 * INCLUSÃO DO FILTRO
		 * as classes de filtro devem ter o mesmo nome do pacote e devem imple-
		 * mentar a interface Samus_Filter
		 *********************************************************************/
		$filterFile = Samus::getControlsDirectory() . '/' . $directory . $filterClass . Samus::getControlsFileExtension();
		
		/************************************************************************
		 * CLASSE FILTRO DEFAULT
		 * caso não tenha um filtro associado ele busca o filtro padrão
		 ************************************************************************/
		if (! is_file($filterFile)) {
			$filterClass = Samus::getDefaultFilterClass();
			$filterFile = Samus::getControlsDirectory() . '/' . $directory . $filterClass . Samus::getControlsFileExtension();
		}
		
		$classFile = $className; //nome do arquivo
		$className = UtilString::underlineToUpper($className); //nome da classe
		

		if (Samus::isDecodeUTF8Strings()) {
			$className = utf8_decode($className);
		}
		
		$className = CleanString::clean($className, true);
		
		$className .= Samus::getControlsClassSufix();
		
		$className = ucfirst($className);
		
		self::$controllerName = $className;
		
		$filtred = false;
		if (is_file($filterFile)) {
			
			require_once $filterFile;
			
			$ref = new ReflectionClass($filterClass);
			
			if ($ref->getParentClass()->getName() != "Samus_Filter") {
				throw new Exception("A interface Samus_Filter deve ser implementada nos filtros");
			}
			
			$filtroObj = $ref->newInstance();
			$this->filter = $filtroObj;
			
			$met = $ref->getMethod("getExceptions");
			
			/*@var $met ReflectionMethod*/
			$exceptionsPages = $met->invoke($filtroObj);
			
			$flickThisSamus_Controller = true;
			
			foreach ( $exceptionsPages as $control ) {
				if (strtolower($control) == strtolower($className)) {
					$flickThisSamus_Controller = false;
					break;
				}
			}
			
			// se a página não for uma exeção
			if ($flickThisSamus_Controller) {
				$met = $ref->getMethod("filter");
				$met->invoke($filtroObj);
			}
			
			$filtred = true;
		}
		
		$requireFile = Samus::getControlsDirectory() . '/' . $directory . $className . Samus::getControlsFileExtension();
		
		if (is_file($requireFile)) {
			require_once $requireFile;
			
			$ref = new ReflectionClass($className);
			$obj = $ref->newInstance();
			
			if ($filtred) {
				$met = $ref->getMethod("setGlobal");
				$met->invoke($obj, $this->filter);
			}
			
			if (! empty($metodos)) {
				foreach ( $metodos as $met ) {
					
					$metParametros = explode(Samus::getMethodUrlParameterSeparator(), $met);
					
					$met = $metParametros [0];
					unset($metParametros [0]);
					
					$met = UtilString::underlineToUpper($met); //nome da classe
					

					if (Samus::isDecodeUTF8Strings())
						$met = utf8_decode($met);
					
					$met = CleanString::clean($met, true);
					
					$met = $met . Samus::getMethodUrlSufix();
					if (! method_exists($obj, $met)) {
						// throw new Samus_Exception("O metodo $met não existe na classe $className");
					} else {
						$urlMet = $ref->getMethod($met);
						
						if (! empty($metParametros)) {
							
							if (Samus::isDecodeUTF8Strings()) {
								foreach ( $metParametros as $key => $m ) {
									$metParametros [$key] = utf8_decode($m);
								}
							}
							try {
								$urlMet->invokeArgs($obj, $metParametros);
							} catch ( ReflectionException $ex ) {
								throw new Samus_Exception("Você não tem permissão para acessar este metodo ou ele é invalido " . $ex->getMessage());
							}
						
						} else {
						    
							try {
								$urlMet->invoke($obj);
							} catch ( ReflectionException $ex ) {
								throw new Samus_Exception("Você não tem permissão para acessar este metodo ou ele é invalido " . $ex->getMessage());
							}
						}
					
					}
				
				}
			}
			
			/*@var $met ReflectionMethod*/
			$met = $ref->getMethod("index");
			$met->invoke($obj);
			
			$met = $ref->getMethod("assignClass");
			$met->invoke($obj, $directory);
		
		} else {
			
			/***************************************************************
			 * EXIBIÇÃO DE ARQUIVOS SEM CONTROLADORES ASSOCIADOS
			 **************************************************************/
			//$className = strtolower(substr($className, 0, 1)) . substr($className,1);
			

			//caso seja um arquivo ajax
			

			$ajaxTamanho = strlen(Samus::getAjaxPhpFileExtension());
			
			$nClassName = str_replace(Samus::getControlsClassSufix(), "", $className);
			
			if (substr($nClassName, $ajaxTamanho * - 1, $ajaxTamanho) == Samus::getAjaxPhpFileExtension()) {
				require_once Samus::getAjaxPhpFilesDirectory() . '/' . $classFile;
			
			} else {
				
				//caso seja um arquivo de template
				

				if (empty($className)) {
					$className = $this->getDefaultPage();
				}
				
				if (substr($className, - 8, 8) == '.inc.php') {
					$requireViewFile = Samus::getViewsDirectory() . '/' . strtolower($className);
					require $requireViewFile;
				} else {
					
					$requireViewFile = Samus::getViewsDirectory() . '/' . $directory . UtilString::upperToUnderline($classFile) . Samus::getViewFileExtension();
					
					if (is_file($requireViewFile)) {
						require_once 'samus/Samus_DefaultController' . Samus::getControlsFileExtension();
						
						$ref = new ReflectionClass("Samus_DefaultController");
						$obj = $ref->newInstance();
						
						/*@var $met ReflectionMethod*/
						$met = $ref->getMethod("index");
						$met->invoke($obj);
						
						if ($filtred) {
							$met = $ref->getMethod("setGlobal");
							$met->invoke($obj, $filtroObj);
						}
						
						$met = $ref->getMethod("assignClass");
						$met->invoke($obj, $requireViewFile);
					
					} else {
						
						require_once 'util/Util.php';
						//echo "<h1 align='center'>Página não Encontrada!</h1>";
						//echo "<h2 align='center'>".$_SERVER['REQUEST_URI']."</h2>";
						

						$strA = '';
						foreach ( Samus::getURL() as $st ) {
							$strA .= $st . '-';
						}
						
						$strA = substr($strA, 0, - 1);
						
						if (substr($strA, - 5) != "index") {
							
							echo "Requisição não processada";
						
						} else {
							//Util::redirect($this->errorPage.'-'.Samus::getURL(0), 0);
						}
					
					}
				}
			}
		}
	
	}
	
	/**
	 * Retorna um valor da url, todas as variáveis $_GET devem ser obtidas por
	 * este método, cada valor do array fica em uma posição da URL:
	 * Ex.:  <br />
	 * http://site.com.br/produto-categoria-2<br />
	 * <br />
	 * Samus_Keeper::getUrl(0); // retorna "produto"<br />
	 * Samus_Keeper::getUrl(1); // retorna "categoria"<br />
	 * Samus_Keeper::getUrl(2); // retorna 2<br />
	 * Samus_Keeper::getUrl();  // retorna array("produto", "categoria" , 2); <br />
	 *
	 * @return string[]
	 */
	public static function getUrl($pos = "") {
		if (! empty($pos) || $pos === 0) {
			
			if (! empty(self::$url [$pos])) {
				return self::$url [$pos];
			} else {
				return NULL;
			}
		
		} else {
			
			return self::$url;
		}
	}
	
	/**
	 * Obtem uma variavel de url
	 *
	 * Ex.:
	 * pagina.com/download-cod=13-ref=14-user-2001
	 *
	 * Samus::getUrlVar('cod');
	 * Samus_Keeper::getUrlVar('ref');
	 *
	 * @param <type> $varName
	 * @return <type>
	 */
	public static function getUrlVar($varName = null) {
		if (! empty($varName) || $varName === 0) {
			
			if (! empty(self::$urlVars [$varName])) {
				return self::$urlVars [$varName];
			} else {
				return NULL;
			}
		
		} else {
			
			return self::$urlVars;
		}
	}
	
	/**
	 * @param string[] $url
	 */
	public static function setUrl($url) {
		self::$url = $url;
	}
	
	/**
	 * @return string
	 */
	public function getUrlSeparator() {
		return $this->urlSeparator;
	}
	
	/**
	 * @param string $urlSeparator
	 */
	public function setUrlSeparator($urlSeparator) {
		$this->urlSeparator = $urlSeparator;
	}
	
	/**
	 * @return string
	 */
	public function getDefaultPage() {
		return $this->defaultPage;
	}
	
	/**
	 * @param string $defaultPage
	 */
	public function setDefaultPage($defaultPage) {
		$this->defaultPage = $defaultPage;
	}
	
	/**
	 * @return string
	 */
	public function getErrorPage() {
		return $this->errorPage;
	}
	
	/**
	 * @param string $errorPage
	 */
	public function setErrorPage($errorPage) {
		$this->errorPage = $errorPage;
	}
	
	/**
	 * Obtem o filtro do pacote atual
	 * @return __Filter
	 */
	public function getFilter() {
		return $this->filter;
	}
	
	/**
	 * Obtem o nome do controlador que esta sendo executado na requisição
	 * @return string
	 */
	public function getControllerName() {
		return self::$controllerName;
	}

}

?>