<?php
require_once 'CRUD/CRUDException.php';
require_once 'CRUD/MethodSintaxe.php';

/**
 * CRUD - Peristência para PHP
 *
 *
 * Esta classe faz a persistência de objetos no MySql para atributos
 * corretamente encapsulados. As classes que fizerem uso da CRUD devem seguir
 * um padrão rígido de Sintaxe seguindo o padrão Camel Case (primeira letra
 * maiuscula para cada separação de palavra) ou Underscores (underlines para
 * cada separação de palavra)
 *
 * Basicamente esta classe varre as propriedades métodos de uma classe e apartir
 * dos dados fornecidos consegue gerar SQLs bem construidas para carregar,
 * salvar, editar e excluir entidades. Entidades são classes bem construidas que
 * tenham uma tabela no banco de dados representando físicamentes os objetos.
 *
 * Utilize a Classe TableFactory para criação de suas bases de dados
 *
 * Algumas observações importantes:
 * - É obrigatório a implementação de um ID em cada tabela para o correto
 * funcionamento da AP
 * - Prefira utilizar esta Classe a partir das classes DAO que fazem acesso
 * de forma mais amigavel aos métodos CRUD
 * - É preciso estabelecer previamente uma conexão com o banco de dados
 * - Para espacapar da analise da classe e criar atributos que não devem ser
 * refletidos no banco de dados inicie o nome da propriedade com um underline
 *
 *
 * Informações e Tutoriais: http://www.Samus.com.br/crud
 *
 * @todo Implementar o controle de profundidade de carregamento, "depth"
 * objetos aninhados devem ter um cotrole de nível de carregamento
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.1
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 */
class CRUD {

    /**
     * Nome default da classe topo da hierarquia de persistencia
     * @var string nome da classe
     */
    public static $topLevelClass = "DAO";

    /**
     * Atrobuto statico que armazena todas as propriedades do banco de dados
     * em um array identificado pelo nome da tabela
     * @var array string
     */
    public static $realDbColumns = array ();

    /**
     * Nome da classe que sera analisada
     * @var string
     */
    private $className;

    /**
     * Uma coluna chave da tabela que representa a classe
     * @var string
     */
    private $keyColumn = "id";

    /**
     * Nome da tabela que representa a classe
     * @var string
     */
    private $tableName;

    /**
     * Array com os atributos que nao devem ser indexados em uma analise
     * geralmente utilizado quando um atributo nao esta presente na tabela
     * @var string
     */
    private $noIndexAtributes = array ("dao" ); //nome dos atributos que nao serao indexados


    /**
     * Se a classe analisada tem seus atributos encapsulados
     * @var boolean
     */
    private $encapsuled = true;

    /**
     * Testa se os atributos ja foram indexados para executar algumas operações
     * @var string
     */
    private $atributesIsIndexed = false;

    /**
     * Liga ou desliga o debug da classe que exibe os codigos gerados e sqls
     * @var boolean
     */
    protected static $debug = false;

    /**
     * Define se o Debug de sql será atibado
     * @var boolean
     */
    public static $debugSql = false;

    /**
     * Atributos que tem valor default array que sao associaaaes com outras classes
     * @var string
     */
    private $attributesTypeArray = array ();

    /**
     * Prefixo para os nomes das tabelas
     * @var string
     */
    public static $tablePrefix;

    /**
     * Uso interno - atributos da classe
     * @var array
     */
    private $atributes = array ();

    /**
     * String que armazena e controla o formato XML das entidades
     * @var string
     */
    public static $xmlStr;

    /**
     * Define se os métodos usam a sintaxe camelCase ou underline
     * @var boolean
     */
    private static $camelCase;

    /**
     * Objeto de uma conexão PDO
     * @var PDO|resource
     */
    private static $conn;

    /**
     * Controla o nivel de profundidade de acesso aos objetos aninhados
     * @var int
     */
    private static $depth;

    private $colunIdentifier;

    const GET_DB_TABLE_METHOD = "getDbTable";
    const DEFAULT_COLUMN_IDENTIFIER_CONST = "DB_TABLE_ID";
    const DEFAULT_KEY_COLUNM = "id";
    const DEFAULT_NO_INDEXED_PROPERTIE_PREFIX = "_";
    const DEFAULT_MODEL_CONTROLLER_PROPERTIE = "_co";

    /**
     * Faz o cache de todos os objetos carregados, sendo a chave de identificacao
     * da classe o seu proprio nome, e a idendificação dos objetos atraves do seu
     * id,
     *
     * $__objCache['Pessoa'][2] ---> representa o objeto de ID 2 da classe Pessoa
     *
     * O objetivo é armazenar como cache os objetos ja carregados para que evite
     * a execução de querys desenecessárias
     *
     * @var array
     */
    public static $__objCache = array ();

    /**
     * Define se deve ou não ser utilizada o cache de objetos
     * @var boolean
     */
    public static $useObjCache = true;

    /**
     * @var ReflectionClass
     */
    private $ref;

    /**
     * Construtor do CRUD, seta o nome da classe que seraf analisada, caso as
     * constantes DB_TABLE e DB_TABLE_ID nao tiverem sidos especificadas, apos o construtor
     * o metodos setKeyColumn() e setDbTalbe() devem ser invocados. Se a coluna chave do Banco for
     * diferente da especificada em DEFAULT_KEY_COLUNM o metodo setKeyColunm() deve ser invocado
     * @param string $className nome da classe que sera analisada
     */
    public function __construct($className, $tableName = "") {

        //self::$realDbColumns = array();


        $this->setClassName($className);

        if (class_exists($this->className)) {
            $this->ref = new ReflectionClass($this->className);
        } else {
            throw new CRUDException("A Classe: " . $this->className . ' não existe');
        }

        $this->setTableName($tableName);
        //$this->setColumnIdentifier();
        //$this->setKeyColunm(self::DEFAULT_KEY_COLUNM);
        $this->buildRealDbColumns();
    }

    /**
     *  Define se uma variável está ou não vazia conforme as determinações do
     * framework
     *
     * @param mixed $var
     * @return boolean
     */
    public static function isEmpty($var = null) {

        if (! isset($var)) { // se ela não estive sid iniciada
            return true;
        }

        $emptyCases = array ("", null, array () ); //casos que serão tratados como vazio


        foreach ( $emptyCases as $emp ) { // loop para verificar se esta realmente vazio
            if ($var === $emp) {
                return true;
            }
        }

        return false;
    }

    /**
     * Constroi um array com o nome real das colunas do banco de dados apenas se
     * não tiver sido executada
     *
     */
    protected function buildRealDbColumns() {

        //indexa apenas se já não tiver sido criada
        if (empty(self::$realDbColumns [$this->tableName])) {

            $parentClassesArray = array ($this->ref );

            while ( $this->ref->getParentClass()->getName() != self::getTopLevelClass() ) {
                $this->ref = $this->ref->getParentClass();
                $parentClassesArray [] = $this->ref;
            }

            foreach ( $parentClassesArray as $ref2 ) {

                $ai = new ArrayIterator($ref2->getDefaultProperties());

                while ( $ai->valid() ) {

                    if (! is_array($ai->current()) && ! in_array($ai->key(), $this->noIndexAtributes) && substr($ai->key(), 0, 1) != self::DEFAULT_NO_INDEXED_PROPERTIE_PREFIX) {
                        self::$realDbColumns [$this->tableName] [] = $ai->key();
                    }

                    $ai->next();

                }
            }
        }

        // se não estiver criado o array de objetos da classe ele cria
        if (! isset(self::$__objCache [$this->getClassName()])) {
            self::$__objCache [$this->getClassName()] = array ();
        }

    }

    /**
     * Registra um objeto no cache
     * @param objetct|DAO $obj
     */
    private function registerCachedObject($obj) {
        // registra o objeto
        if (self::$useObjCache) {
            if (! self::objIsCached($this->getClassName(), $obj->getId())) {
                self::$__objCache [$this->getClassName()] [$obj->getId()] = $obj;
            }
        }
    }

    /**
     * Define seu um determinado objeto esta ou nao em cache
     * @param string $className Nome da classe
     * @param int $id
     * @return boolean
     */
    public static function objIsCached($className, $id) {
        if (isset(self::$__objCache [$className] [$id])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtem um objeto que esta em cache a partir do nome da sua classe do seu id
     * @param string $className
     * @param int $id
     */
    public static function getCachedObject($className, $id) {
        if (self::objIsCached($className, $id)) {
            return clone self::$__objCache [$className] [$id];
        } else {
            return false;
        }

    }

    /**
     * Constroi o nome da classe dentro do padrão CRUD
     * letras maiusculas)
     *
     * @param string $className nome da tabela
     */
    public function buildTableName($className) {
        require_once 'util/UtilString.php';
        return CRUD::$tablePrefix . UtilString::upperToUnderline($className);
    }

    /**
     * Valida se um atributo realmente existe no banco de dados, sendo assim uma
     * propriedade
     *
     * @param string $attributeName
     * @return boolean
     */
    public function validateAttributeColunm($attributeName) {
        $attExists = false;
        foreach ( self::$realDbColumns [$this->tableName] as $dbColunm ) {
            if ($attributeName == $dbColunm) {
                $attExists = true;
                break;
            }
        }

        if (! self::isEmpty($this->atributes)) {
            $attExists = false;
            foreach ( $this->atributes as $atr ) {
                if ($attributeName == $atr) {
                    $attExists = true;
                    break;
                }
            }
        }

        return $attExists;

    }

    /**
     * Retorna um array de Atributos da classe especificada, atributos especificados em noIndexAtributes
     * serao ignorados
     * @param string $className opcional de uma classe Nome da classe
     * @return string Array de atributos da classe especificada
     */
    public function getAtributes() {
        if (self::isEmpty($this->atributes)) {
            $this->setAtributes();
        }
        return $this->atributes;
    }

    public function clearAtributes() {
        $this->atributes = array ();
    }

    /**
     * Obtem os atributos de uma classe (propriedades) caso seja especificado
     * atributes array, será ignorado a analise da classe e os atributos
     * indexados serão os atributos especificados
     *
     * @param string $atributesArray nome dos aributos da classe
     */
    public function setAtributes($atributesArray = array()) {
        if (func_num_args() < 1)
            $className = $this->getClassName();

        if (self::isEmpty($atributesArray) || $atributesArray == array ()) {
            $atr = new ReflectionClass($className);
            $prop = $atr->getProperties();
            $defaultProp = $atr->getDefaultProperties();
            $ai = new ArrayIterator($prop);
            $atributes = array ();

            while ( $ai->valid() ) {

                $index = true;

                if (! self::isEmpty($this->noIndexAtributes)) {
                    $ai2 = new ArrayIterator($this->noIndexAtributes);
                    $index = true;

                    while ( $ai2->valid() ) { //testa os atributos que nao devem ser indexados
                        if ($ai2->current() == $ai->current()->name) {
                            $index = false;
                            break;
                        }
                        $ai2->next();
                    }
                }

                if (! self::isEmpty($defaultProp)) {
                    $ai3 = new ArrayIterator($defaultProp);
                    while ( $ai3->valid() ) {
                        if (is_array($ai3->current()) && $ai->current()->name == $ai3->key()) { //se a propriedade default de um atributo for um ARRAY ignora
                            $this->attributesTypeArray [] = $ai3->key();
                            $index = false;
                            break;
                        }
                        $ai3->next();
                    }
                }

                /**
                 * @todo adicionei essa linha para retirar os atributos não
                 * indexados da lista mas não sei os impactos ainda nos codigos antigos
                 */
                if(substr($ai->current()->name,0,1) == self::DEFAULT_NO_INDEXED_PROPERTIE_PREFIX) {
                    $index = false;
                }

                if ($index)
                    $atributes [] = $ai->current()->name;

                $ai->next();

            }
            $this->atributesIsIndexed = true;

        } else { //caso tenha sido passado o array de atributos
            $atributes = $atributesArray;
            $this->atributesIsIndexed = true;
        }

        $this->atributes = $atributes;
    }

    /**
     * Adiciona atributos que nao devem ser indexados nas operaaaes
     * @param <type> $atribute
     */
    public function addNoIndexAtribute($atribute) {
        $this->noIndexAtributes [] = $atribute;
    }

    /**
     * Monta um objeto do padrao CRUD com os dados da tabela do banco que representa a entidade.
     * Se whereCondition for um INTEIRO o matodo analisara automaticamente o campo chave (id) e retornara o seu
     * objeto preechido. <br/>
     * Caso a classe tenha objetos  como atributos (uma associaaao comum), o nome deste atributo devera ser IGUAL
     * ao nome da classe que ele representa (iniciado com letra minuscula), e o setter do parametro DEVERa ser tipado com o objeto que
     * deve ser montado, (se quiser implementar a intancia do objeto na prapria classe, basta nao tipar)
     * <br/>
     * Ex.:<br/>
     * <br/>
     * class Produto {<br/>
     *  private $id;<br/>
     *  private $nome;<br/>
     *  private $categoria; //categoria a um objeto<br/>
     *  .<br/>
     *  .//constantes geters e setters<br/>
     * <br/>
     *  public function setCategoria(Categoria $categoria) { //tipagem de parametro<br/>
     *    $this->categoria = $categoria;<br/>
     *  }<br/>
     * }<br/>
     * <br/>
     * IMPORTANTE: a classe Categoria DEVERa implementar as constantes com os nomes da tabela e identificador (DB_TABLE_ID , DB_TABLE , DEFAULT_KEY_COLUNM)<br/>
     *
     *
     * @param string|int $whereCondition
     * @param string $additionalParameters optional parametros adicionais para seleaao
     * @return mixed Objeto da classe especificada
     */
    public function mountObject($whereCondition, $additionalParameters = "", $object = null, $loadInternalObjects = true, $buildXML = false) {
        //s$atr = $this->getAtributes();\
        $v1 = array ();
        $whereJoin = "";
        $realWhereCondition = $whereCondition;

        $topLevelClass = $this->getTopLevelClass();

        if ($buildXML) {
            self::$xmlStr .= "<" . $this->className . ">";
        }

        //	try {
        //	    $this->ref = new ReflectionClass($this->getClassName());
        //	} catch ( ReflectionException $ex ) {
        //	    echo "A Classe especificada não existe: " . $ex->getMessage();
        //	}


        if ($object == null)
            $object = $this->ref->newInstance();

        /*************************************************************************
         * FAZ AS INTERAÇÕES COM AS SUPER CLASSES
         * varre as superClasses e carrega as informações das suas respectivas
         * tabelas, também adiciona as colunas que serão processadas na reflexão
         * todos os atributos das superclasses
         ************************************************************************/


        
        $ref1 = new ReflectionClass($object);
        $this->ref =  $ref1;

        $parentClassesArray = array ($this->ref );

        $r = $this->ref;

        while ( $r->getParentClass()->getName() != $topLevelClass ) {
            $r = $this->ref->getParentClass();
            $parentClassesArray[] =  $r;

        }

        $parentClassesArray = array_reverse($parentClassesArray);


        $colunasArray = array ();
        $colunas = "";
        $tableName = "";

        $contParent = 0;
        foreach ( $parentClassesArray as $parentRef ) {
            /* @var $parentRef ReflectionClass */

            $contParent ++;

            //adiciona os atributos da superCLasse para serem carregados
            $properties = $parentRef->getDefaultProperties();

            $atrAtual = array ();

            $atrAi = new ArrayIterator($properties);

            while ( $atrAi->valid() ) {

                if ($this->validateAttributeColunm($atrAi->key())) {
                    $atr [] = $atrAi->key();
                    $atrAtual [] = $atrAi->key();
                }

                $atrAi->next();
            }

            /**
             * @var $tempTableName string  nome da tabela q sera abaliado abaixo
             */
            $tempTableName = self::$tablePrefix . $this->upperToUnderline($parentRef->getName());

            $ai = new ArrayIterator($atrAtual);

            while ( $ai->valid() ) {

                if ($this->validateAttributeColunm($ai->current())) {
                    $colunasArray [] = $tempTableName . "." . $ai->current() . $this->getColumnIdentifier();
                }

                $ai->next();
            }

            $tableName .= $tempTableName . ' , ';

            //se tiver pelomenos uma classe pai
            if ($contParent > 1) {
                $whereJoin = $tempTableName . ".id = " . self::$tablePrefix . $this->upperToUnderline($parentClassesArray [$contParent - 2]->getName()) . ".id AND ";
            }

        }

        $colunasArray = array_unique($colunasArray);

        foreach ( $colunasArray as $col ) {
            $colunas .= $col . " , ";
        }

        //tira a ultima virgula dos nomes
        $colunas = substr($colunas, 0, - 2);

        if (! self::isEmpty($whereJoin))
            $whereJoin = substr($whereJoin, 0, - 4);

        $tableName = substr($tableName, 0, - 2);

        if (! self::isEmpty($whereCondition) && ! is_int($whereCondition)) {
            if (substr($whereCondition, 0, 6) != " WHERE") {
                $whereCondition = " WHERE " . $whereCondition;
            }
        } elseif (is_numeric($whereCondition)) {
            $this->setKeyColunm(self::DEFAULT_KEY_COLUNM);

            $id = $whereCondition;

            // se o objeto estiver em cache busco e retorno ele no lugar de realizar a query
            if (self::isObjectsCached()) {
                $obj = self::getCachedObject($this->className, $id);

                if ($obj != false) {
                    self::copyObject($object, $obj);

                    return $object;
                }
            }

            $whereCondition = " WHERE $tempTableName." . $this->getKeyColunm() . " = " . $whereCondition;

        } else {
            $whereCondition = "";
        }

        if (! self::isEmpty($whereJoin) && ! self::isEmpty($whereCondition)) {
            $whereCondition = $whereCondition . " AND " . $whereJoin;
        } elseif (self::isEmpty($whereCondition) && ! self::isEmpty($whereJoin)) {
            $whereCondition = " WHERE " . $whereJoin;
        }

        if (self::$useObjCache) {
            $whereNumeric = " WHERE $tempTableName." . $this->getKeyColunm() . " = " . $realWhereCondition;
            if ($whereNumeric == $whereCondition) {
                if (self::objIsCached($this->className, (int) $realWhereCondition)) {
                    return self::getCachedObject($this->className, $realWhereCondition);
                }
            }
        }

        $sql = "SELECT $colunas FROM " . $tableName . " $whereCondition $additionalParameters LIMIT 1";

        $r1 = self::executeQuery($sql);

        //        if (strlen(mysqli_error(self::getConn())) > 0) {
        //            throw new CRUDException("SQL ERROR: $sql - " . mysqli_error(self::getConn()));
        //        }

        if(CRUDQuery::isPDOMode()) {
            if($r1 instanceof PDOStatement) {
                $values1 = $r1->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        if(CRUDQuery::isMySqliMode()) {

            $aAux = array();
            while($v1A = mysqli_fetch_assoc($r1)) {
                $aAux[] = $v1A;
            }
            
            $values1 = $aAux;

        }

        if (is_array($values1) && ! empty($values1)) {
            $values1 = $values1 [0];
        }

        //varre os resultados e adiciona pro array de resultados os valores da sql
        if (! self::isEmpty($values1)) {
            $av = new ArrayIterator($values1);
            while ( $av->valid() ) {
                $v1 [$av->key()] = $av->current();
                $av->next();
            }
        }

        $atr = array_unique($atr);

        $ai = new ArrayIterator($atr);

        $ai->rewind();
        while ( $ai->valid() ) {
            $isObject = false;

            $meR = new ReflectionMethod($this->getClassName(), MethodSintaxe::buildSetterName($ai->current()));

            if ($meR->getNumberOfParameters() == 1) {
                $parametro = $meR->getParameters();
                $parametro = $parametro [0];
                //$pa = new ReflectionParameter();
                $paramClass = $parametro->getClass();
                if (! self::isEmpty($paramClass)) {
                    $isObject = true;
                }
            }

            if ($isObject && $loadInternalObjects) { //se o parametro for um objeto ele instancia ele atravas da CRUD
                if(array_key_exists($ai->current() . $this->getColumnIdentifier() , $v1)) {
                    if (! self::isEmpty($v1 [$ai->current() . $this->getColumnIdentifier()])) { // se não tiver vazio
                        //$crud = new CRUD($paramClass->name);


                        //$obb = $crud->mountObject((int) $v1[$ai->current() . $this->getColumnIdentifier()], '', '', self::getTopLevelClass(), $buildXML);

                        $strEval = '$obb = new '.$paramClass->name.'('.(int) $v1[$ai->current() . $this->getColumnIdentifier()].');';
                        eval ($strEval);

                        if (self::$debug)
                            var_dump($meR);

                        $meR->invoke($object, $obb);
                    }
                }
            } else { //senão, faz uma consulta comum ]


                if (! self::isEmpty(@$v1 [$ai->current() . $this->getColumnIdentifier()])) {

                    $valor = $v1 [$ai->current() . $this->getColumnIdentifier()];

                    //adicionado addSlashes ao valor
                    /**
                     * @todo verificar se esta substituição funciona mesmo
                     */
                    $valor = str_replace("'", "\\", $valor);

                    $strCod = '$object->' . MethodSintaxe::buildSetterName($ai->current()) . '($valor);';
                    eval($strCod);

                    if ($buildXML) {
                        //a formatação é diferente para acertar a posição da tag no xml
                        self::$xmlStr .= "<" . $ai->current() . ">" . htmlentities($valor, ENT_NOQUOTES, "ISO-8859-1") . "</" . $ai->current() . ">";
                    }

                    if (self::$debug)
                        var_dump($strCod);

                }
            }
            $ai->next();
        }

        if ($buildXML) {
            self::$xmlStr .= "</" . $this->className . ">";
        }

        // faz o cache do objeto
        $this->registerCachedObject($object);

        return $object;
    }

    /**
     * Faz a copia de $objectToCopy para $object um objeto atravez dos Setters e
     * Getters do objeto
     * @param object $object
     * @param object $objectToCopy
     */
    public static function copyObject($object, $objectToCopy) {

        $ref = new ReflectionObject($object);
        $props = $ref->getProperties();

        $strEval = "";
        foreach ( $props as $k => $p ) {
            /*@var $p ReflectionProperty */
            // $p = new ReflectionProperty();
            $isObject = false;

            if(method_exists($object, MethodSintaxe::buildSetterName($p->getName()))) {

                $meR = new ReflectionMethod($object, MethodSintaxe::buildSetterName($p->getName()));
                $parametro = $meR->getParameters();
                $parametro = $parametro [0];
                $paramClass = $parametro->getClass();

                if (! self::isEmpty($paramClass)) {
                    $isObject = true;
                }

                if ($isObject) {
                    $testValue = null;
                    eval('$testValue = $objectToCopy->' . MethodSintaxe::buildGetterName($p->getName()) . '();');
                    if ($testValue instanceof $paramClass) {
                        $strEval = '$object->' . MethodSintaxe::buildSetterName($p->getName()) . '($objectToCopy->' . MethodSintaxe::buildGetterName($p->getName()) . '());';
                    }
                } else {
                    $strEval = '$object->' . MethodSintaxe::buildSetterName($p->getName()) . '($objectToCopy->' . MethodSintaxe::buildGetterName($p->getName()) . '());';
                }


                eval($strEval);
            }

        }

    }

    /**
     * Pega as anotações de um var "@var" de um phpDoc
     *
     * @param string $doc
     * @return array
     */
    protected function getVarAnnotation($doc) {
        $parametrosArray = array ();
        if (! self::isEmpty($doc)) {
            $doc = strstr($doc, "@var");
            if (! self::isEmpty($doc)) {

                $doc = str_replace("/", "", $doc);
                $doc = str_replace("*", "", $doc);
                $doc = str_replace("@var", "", $doc);
                $doc = trim($doc);

                $parametrosArray = split(" ", $doc);
            }
        }
        return $parametrosArray;
    }

    /**
     * Alias para o metodo mountObject()
     * @param string $whereCondition
     * @return object
     */
    public function loadObject($whereCondition) {
        return $this->mountObject($whereCondition);
    }

    /**
     * Carrega o ultimo (ou primeiro caso first seja setado como true) objeto
     * adicionado no banco a patir do seu campo chave
     * @return array[]
     */
    public function loadLastObject($whereCondition = "", $object = null, $first = false) {

        if ($first) {
            $descAsc = "ASC";
        } else {
            $descAsc = "DESC";
        }

        $last = $this->loadLightArray($whereCondition, $this->getKeyColunm() . " $descAsc", 1);

        $last = $last [0];

        if (! self::isEmpty($last)) {
            if ($object != null) {
                $this->mountObject((int) $last->getId(), "", $object);
            }
        }
        return $last;

    }

    /**
     * Alias para o metodo mountObjectArrayAttributes() <br />
     * Carrega os valores dos atributos que sao array() em associaaaos de 0..* ou 1..*.
     * O objeto passado como parametro sera preenchido
     * @param mixed $object
     * @deprecated
     */
    public function loadArrayAttributes($object) {
        $this->mountObjectArrayAttributes($object);
    }

    /**
     * Carrega os valores das propriadades que sao arrays, se a classe analisada tiver atributos que foram
     * iniciados como "array()" e este matodo foi instanciado passando como parametro o objeto preenchido
     * (instancia de className), ele retornara o objeto com as propriedades devidamente preenchidas<br />
     * <br />
     * Ex.:<br />
     * <br />
     * class Categoria {<br />
     *    private $id;<br />
     *    private $nome;<br />
     *    private $produtos = array();<br />
     *    . <br />
     *    .<br />
     *    ... //getter e setters        <br />
     * }<br />
     * <br />
     * $crud = new CRUD("Categoria");<br />
     * $categoria = $crud->mountObject(1);<br />
     * $crud->mountObjectArrayAttributes($categoria); //preenche o $objeto categoria com todos os produtos dela<br />
     *
     *
     *
     * @param object $objeto objeto instancia de className
     * @return object $objeto objeto com as propridades array() prenchidas
     * @deprecated
     */
    public function mountObjectArrayAttributes($objeto) {
        if (! $this->atributesIsIndexed)
            $this->getAtributes();

        //$refClass = new ReflectionClass($this->getClassName());
        // $objeto = $refClass->newInstance();
        $ai = new ArrayIterator($this->attributesTypeArray);
        while ( $ai->valid() ) {
            $thisClass = ucfirst($ai->current());

            $thisClass = substr($thisClass, 0, - 1);

            $crud = new CRUD($thisClass);

            if ($this->encapsuled) {
                $chaveName = $this->getKeyColunm();
                if (strlen($this->getColumnIdentifier()) > 0) {
                    $identTamanho = strlen($this->getColumnIdentifier());
                    $chaveName = substr($this->getKeyColunm(), 0, $identTamanho * - 1);
                }
                $metodoChave = "get" . ucfirst($chaveName); //normalmente getId();
            } else {
                $metodoChave = $this->getKeyColunm();
            }

            $refMet = new ReflectionMethod($this->className, $metodoChave);

            $chave = $refMet->invoke($objeto);

            $arrayObj = $crud->loadLightArray(strtolower($this->getClassName()) . $crud->getColumnIdentifier() . "=" . $chave, "", "", "", true);

            $metodo = new ReflectionMethod($this->getClassName(), MethodSintaxe::buildSetterName($ai->current()));
            $metodo->invoke($objeto, $arrayObj);
            $ai->next();
        }
    }

    /**
     * Realiza o carregamento das associações n~n e 1~n de forma simplificada,
     * primeiramente é preciso escrever corretamente a classe de relacionamento
     * a nomenclatura da classe associativa deve seguir o padrão:
     * "Classe1Classe2" e ter como atributos, as classes que serão associadas.
     * A classe que possuir o atributo multivalorado
     * deve possuir um atributo iniciado com o nome da classe multivalorada
     * iniciada com o valor array() <br />
     *      *
     * <br />
     * Ex.: <br />
     * <br />
     * class Produto extends DAO { <br />
     *  private $nome; <br />
     *  private $item = array(); <br />
     * <br />
     *  [...] setters e getters <br />
     * <br />
     * } <br />
     * <br />
     * class Item extends DAO { <br />
     *  private $value; <br />
     *  private $qtd; <br />
     * <br />
     *  [...] <br />
     * } <br />
     * <br />
     * class ProdutoItem extends DAO { <br />
     * <br />
     *  private $produto; <br />
     *  private $item; <br />
     * <br />
     * <br />
     *  [...] <br />
     * <br />
     * } <br />
     *  <br />
     * $p = new Produto(); <br />
     * $p->dao->load(1); <br />
     * $p->dao->loadNtoN(); <br />
     * <br />
     * O método também carrega associações 1~n, elas dispensam a classe associativa
     * basta o atributo com o nome da classe multivalorada inicada com array.
     *
     * Ex.:
     *
     * class Produto extends DAO {
     *
     *  private $nome;
     *  private $foto = array();
     *
     *  [...] setters e getters
     *
     * }
     *
     * class Foto extends DAO {
     *
     *  private $produto;
     *  private $fotoCaminho;
     *
     *  [...]
     *
     * }
     *
     * $p = new Produto(1);
     * $p->loadArray
     *
     *
     * OBS.: O comportamento da classe primeiramente busca uma classe associativa
     * para os atributos iniciados como  "array()" caso não exisita esta classe
     * ele assume que a associação é de 1~n
     *
     *
     * @param mixed $object objeto que sera carregado
     * @param string $order
     * @param int|string $limit
     */
    public function loadMultivaluedProperties($object, $order = "", $limit = "", $loadInternalObjectAtributes = false) {
        $values = array ();
        //$this->ref = new ReflectionClass($object);


        $met = $this->ref->getMethod(MethodSintaxe::buildGetterName(self::DEFAULT_KEY_COLUNM));

        $thisId = $met->invoke($object);

        /***********************************************************************
         * FAZ AS INTERAÇÕES COM AS SUPER CLASSES
         * varre as superClasses e carrega as informações das suas respectivas
         * tabelas, também adiciona as colunas que serão processadas na reflexão
         * todos os atributos das superclasses
         **********************************************************************/

        $parentClassesArray = array ($this->ref );

        while ( $this->ref->getParentClass()->getName() != self::getTopLevelClass() ) {
            $this->ref = $this->ref->getParentClass();
            $parentClassesArray [] = $this->ref;
        }

        $parentClassesArray = array_reverse($parentClassesArray);

        $atr = array ();

        $contParent = 0;
        foreach ( $parentClassesArray as $parentRef ) {
            /* @var $parentRef ReflectionClass */

            $contParent ++;

            //adiciona os atributos da superCLasse para serem carregados
            $properties = $parentRef->getDefaultProperties();

            $atrAtual = array ();

            $atrAi = new ArrayIterator($properties);

            while ( $atrAi->valid() ) {

                if (is_array($atrAi->current())) {

                    $atrName = $atrAi->key();

                    $classes = array ($parentRef->getName(), ucfirst($atrName) );

                    $associativeClass = $parentRef->getName() . ucfirst($atrName);

                    if (class_exists($associativeClass)) { //se existir uma classe associativa então a associação é N para N
                        $c = new CRUD($associativeClass);

                        $atr1 = strtolower(substr($classes [0], 0, 1)) . substr($classes [0], 1); //transforma em minusculo a primeira letra
                        $atr2 = strtolower(substr($classes [1], 0, 1)) . substr($classes [1], 1); //transforma em minusculo a primeira letra


                        $values = $c->loadLightArray($atr1 . "=" . $thisId . "", $order, $limit, false, $loadInternalObjectAtributes);

                        $realValues = array ();
                        foreach ( $values as $value ) {
                            $strEval = '$realValues[] = $value->get' . $classes [1] . '();';
                            eval($strEval);
                        }

                        $strEval = '$object->' . MethodSintaxe::buildSetterName($atrName) . '($realValues);';

                        eval($strEval);

                    } else {
                        $c = new CRUD($classes [1]);

                        $atr1 = strtolower(substr($classes [0], 0, 1)) . substr($classes [0], 1); //transforma em minusculo a primeira letra
                        $atr2 = strtolower(substr($classes [1], 0, 1)) . substr($classes [1], 1); //transforma em minusculo a primeira letra


                        $values = $c->loadLightArray($atr1 . "=" . $thisId . "", $order, $limit, false, $loadInternalObjectAtributes);

                        $strEval = '$object->' . MethodSintaxe::buildSetterName($atrName) . '($values);';

                        eval($strEval);
                    }

                }

                $atrAi->next();
            }

        }

        return $values;
    }

    /**
     * Carrega uma propriedade multivalorada do Objeto, este método resolve
     * relações N**1, para isso é preciso que na classe que carregará este
     * atributo multivalorado tenha um atributo do tipo array() com o mesmo
     * nome da classe multivalorada.
     *
     * Ex.:
     * Uma notícia possuí várias fotos
     *
     * class Noticia {
     *	    [...]
     *
     *	    private $foto = array();
     *
     *	    [...]
     * }
     *
     * class Foto {}
     *
     * $noticia = new Noticia(1);
     * $noticia->getDao()->load_N_2_1_Propertie("foto);
     * var_dump($noticia->getFoto());
     *
     *
     * @param object $object
     * @param string $propertieName
     * @param string $order
     * @param int|string $limit
     * @param boolean $loadInternalObjectAtributes
     */
    public function load_N_2_1_Propertie($object, $propertieName, $multivaloredClassName = "", $order = "", $limit = "", $loadInternalObjectAtributes = false) {
        $prop = $this->ref->getProperty($propertieName); //pega a propriedade especificada


        //$met = $this->ref->getMethod(MethodSintaxe::buildSetterName($propertieName)); // pega o setter da propriedade


        if (self::isEmpty($multivaloredClassName)) {
            $multivaloredClassName = ucfirst($propertieName);
        }

        $c = new CRUD($multivaloredClassName);

        $atr1 = strtolower(substr($this->ref->getName(), 0, 1)) . substr($this->ref->getName(), 1);

        $array = $c->loadLightArray($atr1 . "=" . $object->getId(), $order, $limit, false, $loadInternalObjectAtributes);

        $strEval = '$object->' . MethodSintaxe::buildSetterName($propertieName) . '($array);';

        eval($strEval);
    }

    /**
     * Carrega os valores de  uma relação N_2_N entre 2 tabelas onde uma classe
     * asssociativa faz associação dos itens. Para carregar um atributo multivalorado
     * é preciso que ele seja uma propriedade
     *
     * A classe associativa "default" é a junção do nome das duas tabelas da
     * relação.
     * Ex.:
     * class Item {}
     * class Pedido {}
     *
     * Um item pode estar em vários Pedidos, e um pedido pode ter vários itens,
     * portanto é necessário uma classe associativa entre elas, sendo assim o
     * nome default dessa classe é ItemPedido
     *
     * class ItemPedido{}
     *
     * $pedido = new Pedido(1);
     * $pedido->getDao()->load_N_2_N_Propertie("item");
     * var_dump($pedido->getItem());
     *
     *
     * @param string $object objeto que será usado
     * @param string $propertieName nome da propriedade do tipo array() que será carrregada
     * @param string $associativeClassName  nome da classe associativa
     */
    public function load_N_2_N_Propertie($object, $propertieName, $associativeClassName = "", $order = "", $limit = "", $loadInternalObjectAtributes = true) {

        $prop = $this->ref->getProperty($propertieName); //pega a propriedade especificada


        //$met = $this->ref->getMethod(MethodSintaxe::buildSetterName($propertieName)); // pega o setter da propriedade


        //define o nome da classe associativa se default
        if (self::isEmpty($associativeClassName)) {
            $associativeClassName = ucfirst($propertieName) . $this->ref->getName();
        }

        $c = new CRUD($associativeClassName);

        $atr1 = strtolower(substr($this->ref->getName(), 0, 1)) . substr($this->ref->getName(), 1);
        $array = $c->loadLightArray($atr1 . "=" . $object->getId(), $order, $limit, false, $loadInternalObjectAtributes);

        $arrayValoresCertos = array ();
        foreach ( $array as $a ) {
            $strEval = '$arrayValoresCertos[] = $a->get' . ucfirst($propertieName) . '();';
            eval($strEval);
        }
        $strEval = '$object->' . $propertieName . '=$arrayValoresCertos;';
        eval($strEval);
    }

    /**
     * Carrega um objeto a partir de um array
     * Caso exista em cache traz o objeto do cache
     *
     * @param array $formVars
     * @param object $object
     * @param boolean $loadObjectAtributes
     * @param boolean $useXml
     */
    public function loadObjectFromAssociativeArray(array $formVars = array(), $object = null, $loadObjectAtributes = false, $useXml = false) {
        return $this->mountAssociativeObject($formVars, $object, $loadObjectAtributes, $useXml, true);
    }

    /**
     * Monta um Objeto do tipo especificado em className a patir do array associativo
     * fornecido em $formVars, que geralmente é um $_POST ou $_GET
     * @param array $formVars array associativo com os valores do objeto
     * @param $object objeto para ser analisado
     * @param boolean $loadObjectAtributes se os atributos que são objetos serão também carregados
     *
     * @return mixed objeto do tipo especificado
     * @todo adicionar metodos para melhorar o desempenho desse metodo
     */
    public function mountAssociativeObject(array $formVars = array(), $object = null, $loadObjectAtributes = false, $useXml = false, $useCache = false) {

        if (self::isEmpty($formVars)) {
            $formVars = $this->getFormArray();
        }

        $isObject = false;

        if (isset($formVars [$this->getKeyColunm()])) {
            if (self::$useObjCache && $useCache) {
                if (self::objIsCached($this->className, $formVars [$this->getKeyColunm()])) {

                    //return self::getCachedObject($this->className, $formVars[$this->getKeyColunm()]);


                    $obj = self::getCachedObject($this->className, $formVars [$this->getKeyColunm()]);

                    if ($obj != false && $object != null) {
                        self::copyObject($object, $obj);

                        return $object;
                    } else {
                        return $obj;
                    }

                }
            }
        }

        $rc = new ReflectionClass($this->getClassName());

        if ($object == null) {
            $object = $rc->newInstance();
        }

        $ai = new ArrayIterator($formVars);

        while ( $ai->valid() ) {
            $atual = $ai->current();

            if (! self::isEmpty($atual)) {

                $chaveM = ucfirst($ai->key());

                try {
                    $meR = new ReflectionMethod($this->getClassName(), MethodSintaxe::buildSetterName($ai->key()));

                } catch ( ReflectionException $refEx ) {
                    $ai->next();
                    continue;
                }

                if ($meR->getNumberOfParameters() == 1) {
                    $parametro = $meR->getParameters();
                    $parametro = $parametro [0];
                    $paramClass = $parametro->getClass();

                    if (! self::isEmpty($paramClass)) {
                        $isObject = true;
                    }
                }

                $paramTest = null;
                if (is_object($paramClass)) {
                    $paramTest = $paramClass->name;
                }

                if ($isObject && $loadObjectAtributes && ! empty($paramTest)) { //se o parametro for um objeto ele instancia ele atravas da CRUD


                    $crud = new CRUD($paramClass->name);
                    //$obb = $crud->mountObject((int) $ai->current(), '', '', $this->getTopLevelClass(), $useXml);

                    $strEval = '$obb = new '.$paramClass->name.'('.(int) $ai->current().');';
                    eval ($strEval);

                    $meR->invoke($object, $obb);

                } else { //senao, faz uma consulta comum ]


                    /**
                     * @todo o erro ta aki no carregamento do loadn
                     */

                    if ($isObject && ! $loadObjectAtributes) {
                        $isObject = false;

                    } else {

                        $value = $ai->current();

                        if ($value == "1" || $value == "0") {
                            $value = (boolean) $value;
                        }

                        $meR->invoke($object, $value);

                        if ($useXml) {
                            self::$xmlStr .= "<" . $ai->key() . ">" . htmlentities($ai->current(), ENT_NOQUOTES, "ISO-8859-1") . "</" . $ai->key() . ">";
                        }

                    }

                }
            }

            $ai->next();
        }

        $this->registerCachedObject($object);

        return $object;
    }

    /**
     * Lista de instancias da classe especificada em className, com os dados recuperados da tabela className
     *
     * @param string $keyColumn
     * @param string $whereCondition
     * @param string $additionalParameters optional parametros adicionais para seleaao
     * @return mixed
     */
    public function listObjects($whereCondition = "", $keyColumn = "") {
        if (func_num_args() > 2)
            $additionalParameter = func_get_arg(2);
        else
            $additionalParameter = "";

        if (self::isEmpty($keyColumn)) {
            $this->setKeyColunm(self::DEFAULT_KEY_COLUNM);
            $keyColumn = $this->keyColumn;
        } elseif (! self::isEmpty($keyColumn) && self::isEmpty($this->keyColumn)) {
            $this->setKeyColunm($keyColumn);
            $keyColumn = $this->keyColumn;
        }

        //	try {
        //	    $this->ref = new ReflectionClass($this->getClassName());
        //	} catch ( ReflectionException $ex ) {
        //	    echo "A Classe especificada não existe: " . $ex->getMessage();
        //	}


        /*************************************************************************
         * FAZ AS INTERAÇÕES COM AS SUPER CLASSES
         * varre as superClasses e carrega as informações das suas respectivas
         * tabelas, também adiciona as colunas que serão processadas na reflexão
         * todos os atributos das superclasses
         ************************************************************************/

        $this->ref = $refClass;

        $parentClassesArray = array ($this->ref );

        while ( $this->ref->getParentClass()->getName() != self::getTopLevelClass() ) {
            $this->ref = $this->ref->getParentClass();
            $parentClassesArray [] = $this->ref;
        }

        $parentClassesArray = array_reverse($parentClassesArray);

        $tableName = "";

        $contParent = 0;
        foreach ( $parentClassesArray as $parentRef ) {
            /* @var $parentRef ReflectionClass */

            $contParent ++;

            /**
             * @var $tempTableName string  nome da tabela q sera abaliado abaixo
             */
            $tempTableName = self::$tablePrefix . $this->upperToUnderline($parentRef->getName());

            $tableName .= $tempTableName . ' , ';

            //se tiver pelomenos uma classe pai
            if ($contParent > 1) {
                $whereJoin = $tempTableName . ".id = " . self::$tablePrefix . $this->upperToUnderline($parentClassesArray [$contParent - 2]->getName()) . ".id AND ";
            }

        }

        if (! self::isEmpty($whereJoin))
            $whereJoin = substr($whereJoin, 0, - 4);

        $tableName = substr($tableName, 0, - 2);

        if (! self::isEmpty($whereCondition) && ! is_int($whereCondition)) {
            if (substr($whereCondition, 0, 6) != " WHERE") {
                $whereCondition = " WHERE " . $whereCondition;
            }
        } elseif (is_int($whereCondition)) {
            $this->setKeyColunm(self::DEFAULT_KEY_COLUNM);
            $whereCondition = " WHERE $tempTableName." . $this->getKeyColunm() . " = " . $whereCondition;
        } else {
            $whereCondition = "";
        }

        if (! self::isEmpty($whereJoin) && ! self::isEmpty($whereCondition)) {
            $whereCondition = $whereCondition . " AND " . $whereJoin;
        } elseif (self::isEmpty($whereCondition) && ! self::isEmpty($whereJoin)) {
            $whereCondition = " WHERE " . $whereJoin;
        }

        $q1 = "SELECT $tempTableName.$keyColumn FROM " . $tableName . " $whereCondition $additionalParameter";

        $r1 = self::executeQuery($q1);

        //        if (strlen(mysqli_error(self::getConn())) > 0) {
        //            throw new CRUDException("SQL ERROR: $q1 - " . mysqli_error(self::getConn()));
        //        }


        $objetos = array ();

        if(CRUDQuery::isPDOMode()) {
            while ( $v1 = $r1->fetchAll(PDO::FETCH_NUM) ) {
                $objetos[] = $this->mountObject((int) $v1 [0]);
            }
        } elseif(CRUDQuery::isMySqliMode()) {
            while ( $v1 = mysqli_fetch_row($r1) ) {
                $objetos[] = $this->mountObject((int) $v1 [0]);
            }
        }

        return $objetos;
    }

    /**
     * Alias para listObjects com outros parametros para facilitar o carregamento
     * de listas de Objetos
     * @param string $whereCondition condiaao para a listagem
     * @param string $order ordem da listagem
     * @param int|string $limit limite da listagem
     * @return array
     */
    public function loadList($whereCondition = "", $order = "", $limit = "") {
        if (self::isEmpty($order)) {
            $order = " ORDER BY " . $this->getKeyColunm() . " DESC ";
        } else {
            $order = " ORDER BY $order";
        }
        if (! self::isEmpty($limit))
            $limit = " LIMIT " . $limit;
        return $this->listObjects($whereCondition, "", $order . $limit);
    }

    /**
     * Carrega um array mais leve, faz apenas uma query e não carrega as
     * propriedades que são objetos.
     *
     *
     *
     * @param string|int $whereCondition
     * @param string $order
     * @param string|int $limit
     * @param string $returnArrayValues se true retorna apenas o array associativo fica ainda mais leve
     * @param boolean $loadObjectAtributes se os atributos do tipo objeto serão carregados
     * @return array
     */
    public function loadLightArray($whereCondition = "", $order = "", $limit = "", $returnAssociativeArray = false, $loadObjectAtributes = false, $useXml = false) {

        if (self::isEmpty($order)) {
            $order = "ORDER BY ".$this->getTableName().".". $this->getKeyColunm() . ' DESC';
        } else {
            $order = " ORDER BY $order";
        }

        if (! self::isEmpty($limit)) {
            $limit = " LIMIT " . $limit;
        }

        $whereJoin = '';

        //s$atr = $this->getAtributes();
        $v1 = array ();

        try {
            $this->ref = new ReflectionClass($this->getClassName());
        } catch ( ReflectionException $ex ) {
            echo "A Classe especificada não existe: " . $ex->getMessage();
        }


        /***********************************************************************
         * FAZ AS INTERAÇÕES COM AS SUPER CLASSES
         * ---------------------------------------------------------------------
         * varre as superClasses e carrega as informações das suas respectivas
         * tabelas, também adiciona as colunas que serão processadas na reflexão
         * todos os atributos das superclasses
         **********************************************************************/

        $parentClassesArray = array($this->ref );


        while ( $this->ref->getParentClass()->getName() != self::getTopLevelClass() ) {
            $this->ref = $this->ref->getParentClass();
            $parentClassesArray[] = $this->ref;
        }

        $parentClassesArray = array_reverse($parentClassesArray);



        //to aki
        

//        $parentClassesArray = array ($this->ref );
//
//        $r = $this->ref;
//
//        while ( $r->getParentClass()->getName() != $topLevelClass ) {
//            $r = $this->ref->getParentClass();
//            $parentClassesArray[] =  $r;
//
//        }
//
//        $parentClassesArray = array_reverse($parentClassesArray);




        $colunasArray = array ();
        $colunas = "";
        $tableName = "";
        $atr = array ();

        $contParent = 0;
        foreach ( $parentClassesArray as $parentRef ) {
            /* @var $parentRef ReflectionClass */

            $contParent ++;

            //adiciona os atributos da superCLasse para serem carregados
            $properties = $parentRef->getDefaultProperties();

            $atrAtual = array ();

            $atrAi = new ArrayIterator($properties);

            while ( $atrAi->valid() ) {
                //if($atrAi->key() != self::DEFAULT_KEY_COLUNM) {
                $atr [] = $atrAi->key();
                $atrAtual [] = $atrAi->key();
                //}
                $atrAi->next();
            }

            /**
             * @var $tempTableName string  nome da tabela q sera abaliado abaixo
             */
            $tempTableName = self::$tablePrefix . $this->upperToUnderline($parentRef->getName());

            $ai = new ArrayIterator($atrAtual);

            while ( $ai->valid() ) {

                if ($this->validateAttributeColunm($ai->current())) { //caso o atributo exista no banco de dados
                    $colunasArray [] = $tempTableName . "." . $ai->current() . $this->getColumnIdentifier();
                }
                
                $ai->next();
                
            }

            $tableName .= $tempTableName . ' , ';

            //se tiver pelomenos uma classe pai
            if ($contParent > 1) {
                $whereJoin = $tempTableName . ".id = " . self::$tablePrefix . $this->upperToUnderline($parentClassesArray [$contParent - 2]->getName()) . ".id AND ";
            }

        }

        $colunasArray = array_unique($colunasArray);

        foreach ( $colunasArray as $col ) {
            $colunas .= $col . " , ";
        }

        //tira a ultima virgula dos nomes
        $colunas = substr($colunas, 0, - 2);

        if (! self::isEmpty($whereJoin))
            $whereJoin = substr($whereJoin, 0, - 4);

        $tableName = substr($tableName, 0, - 2);

        if (! self::isEmpty($whereCondition) && ! is_int($whereCondition)) {
            if (substr($whereCondition, 0, 6) != " WHERE") {
                $whereCondition = " WHERE " . $whereCondition;
            }
        } elseif (is_int($whereCondition)) {
            $this->setKeyColunm(self::DEFAULT_KEY_COLUNM);
            $whereCondition = " WHERE $tempTableName." . $this->getKeyColunm() . " = " . $whereCondition;
        } else {
            $whereCondition = "";
        }

        if (! self::isEmpty($whereJoin) && ! self::isEmpty($whereCondition)) {
            $whereCondition = $whereCondition . " AND " . $whereJoin;
        } elseif (self::isEmpty($whereCondition) && ! self::isEmpty($whereJoin)) {
            $whereCondition = " WHERE " . $whereJoin;
        }

        $sql = "SELECT $colunas FROM " . $tableName . " $whereCondition $order $limit";

        $r1 = self::executeQuery($sql);

        $objetos = array ();

        if ($returnAssociativeArray) {


            $auxFetch = array();

            if(CRUDQuery::isPDOMode()) {

                if ($r1 instanceof PDOStatement) {
                    $auxFetch = $r1->fetchAll(PDO::FETCH_ASSOC);
                }

            } elseif(CRUDQuery::isMySqliMode()) {

            $aAux = array();
            while($v1 = mysqli_fetch_assoc($r1)) {
                $aAux[] = $v1;
            }

                $auxFetch = $aAux;
            }

            foreach ( $auxFetch as $v1 ) {
                $objetos[] = $v1;
            }


        } else {

            if ($loadObjectAtributes) {


                $auxFetch = array();

                if(CRUDQuery::isPDOMode()) {
                    if ($r1 instanceof PDOStatement) {
                        $auxFetch = $r1->fetchAll(PDO::FETCH_ASSOC);
                    }
                } elseif(CRUDQuery::isMySqliMode()) {
                    
                    $aAux = array();
                    while($v1 = mysqli_fetch_assoc($r1)) {
                        $aAux[] = $v1;
                    }

                    $auxFetch =  $aAux;
                    
                }

                if(is_array($auxFetch)) {
                 

                foreach ($auxFetch as $v1 ) {

                    if ($useXml) {
                        self::$xmlStr .= "<" . $this->getClassName() . ">";
                    }

                    $objetos [] = $this->loadObjectFromAssociativeArray($v1, null, $loadObjectAtributes, $useXml);
                    //$objetos[] = $this->mountAssociativeObject($v1 , null , $loadObjectAtributes , $useXml);


                    if ($useXml) {
                        self::$xmlStr .= "</" . $this->getClassName() . ">";
                    }


                }

                }

            } else {
                


                $auxFetch = array();

                if(CRUDQuery::isPDOMode()) {
                    if ($r1 instanceof PDOStatement) {
                        $auxFetch = $r1->fetchAll(PDO::FETCH_CLASS,$this->getClassName());
                    }
                } elseif(CRUDQuery::isMySqliMode()) {

                    $aAux = array();

                    while($v1 = mysqli_fetch_object($r1 , $this->getClassName())) {
                        $aAux[] = $v1;
                    }
                    
                    $auxFetch = $aAux;


                }


                    foreach ( $auxFetch as $v1 ) {
                        $objetos [] = $v1;
                    }


                

            }
        }

        return $objetos;
    }

    /**
     * Carrega uma lista de objetos a partir de uma sql qualquer, o caracter '?'
     * será substituido pelo nome da tabela corrente
     *
     * @param string $sql
     * @param $loadObjectAtributes se os atrivutos do tipo objeto serão carregados
     * @return array de objetos
     */
    public function loadArrayFromSql($sql, $loadObjectAtributes = true) {

        $sql = str_replace("?", $this->className, $sql);

        $r1 = self::query($sql);
        $objetos = array ();
        foreach ( $r1 as $v1 ) {
            $objetos [] = $this->loadObjectFromAssociativeArray($v1, null, $loadObjectAtributes);
        }

        return $objetos;
    }

    /**
     * Carrega um array simples da classe atual
     *
     * @param string $sql
     * @return array array associativo
     */
    public function findFromSql($sql) {

        $sql = str_replace("?", $this->className, $sql);

        $r1 = self::query($sql);
        $array = array ();
        foreach($r1 as $v1) {
            $array[] = $v1;
        }

        return $array;
    }

    /**
     * Insere um Objeto, instancia de classe className, populado na tabela especifcada por tableName
     * @param mixed $object
     * @param string $whereCondition
     * @param string $additionalParameters optional parametros adicionais para o insert
     */
    public function insertObject($object, $whereCondition = "", $topLevelClass = "") {

        if (self::isEmpty($topLevelClass)) {
            $topLevelClass = self::getTopLevelClass();
        }

        $object_is_array = false;

        if (is_array($object)) {
            $object_array = $object;
            $object = $object_array [0];
        }

        //$this->ref = new ReflectionClass($object);

        $ref1 = new ReflectionClass($object);
        $this->ref =  $ref1;

        $parentClassesArray = array ($this->ref );

        $r = $this->ref;

        while ( $r->getParentClass()->getName() != $topLevelClass ) {
            $r = $this->ref->getParentClass();
            $parentClassesArray[] =  $r;

        }

        $parentClassesArray = array_reverse($parentClassesArray);


        $fkId = "";
        foreach ( $parentClassesArray as $ref ) {
            /*@var $ref ReflectionClass*/
            $colunas = "";
            $valores = "";

            $ai = new ArrayIterator($ref->getDefaultProperties());



            while ( $ai->valid() ) {
                /*@var $propertie ReflectionProperty*/
                //verificar se as superclasse num tão pegando tudo


                $propertie = $ai->key();
                if(substr($propertie,0,1)==self::DEFAULT_NO_INDEXED_PROPERTIE_PREFIX) {
                    $ai->next();
                    continue;
                }

                $valor = null;
                $strCod = '$valor .= addslashes( $object->' . MethodSintaxe::buildGetterName($propertie) . '());';

                eval($strCod);

                if ($propertie != self::DEFAULT_KEY_COLUNM && (! self::isEmpty($valor) || $valor === false)) {

                    if ($this->validateAttributeColunm($propertie)) {
                        $colunas .= $propertie . ' , ';
                        $valores .= "'";
                        $strCod = '$valores .= addslashes( $object->' . MethodSintaxe::buildGetterName($propertie) . '());';
                        eval($strCod);
                        $valores .= "' , ";
                    }
                }

                $ai->next();

            }

            if (! self::isEmpty($fkId)) {
                $colunas .= self::DEFAULT_KEY_COLUNM . " , ";
                $valores .= $fkId . ' , ';
            }

            $colunas = substr($colunas, 0, - 2);
            $valores = substr($valores, 0, - 2);

            if (! self::isEmpty($whereCondition))
                $whereCondition = "WHERE " . $whereCondition;

            if (func_num_args() > 2)
                $additionalParameter = func_get_arg(3);
            else
                $additionalParameters = "";

            /*@var $refMet ReflectionMethod */
            $tableName = self::$tablePrefix . $this->upperToUnderline($ref->getName());

            $q1 = "INSERT INTO " . $tableName . " ($colunas) VALUES ($valores) $whereCondition $additionalParameters";

            $r1 = self::executeQuery($q1);



                $fkId = CRUDQuery::lastInsertId();


            //$fkId = $r1->lastInsertId();

            //            if (strlen(mysqli_error(self::getConn())) > 0) {
            //                throw new CRUDException("SQL ERROR: $q1 - " . mysqli_error(self::getConn()));
            //            }


        }

        $refMet = new ReflectionMethod($this->getClassName(), MethodSintaxe::buildSetterName($this->getKeyColunm()));

        $refMet->invoke($object, (int) CRUDQuery::lastInsertId());


        return $object;
    }

    /**
     * Salva um objeto, caso o ID dele ja exista no banco entao a feito um
     * updateObject caso nao exista a feito um insert
     * @param mixed $objecta
     * @return boolean
     */
    public function save($object = null) {
        //	$this->ref = new ReflectionClass($this->getClassName());
        //	try {
        //	    $this->ref = new ReflectionClass($this->getClassName());
        //	} catch ( ReflectionException $ex ) {
        //	    echo "A Classe especificada não existe: " . $ex->getMessage();
        //	}
        if ($object == null)
            $object = $this->ref->newInstance();

        $refMe = $this->ref->getMethod(MethodSintaxe::buildGetterName($this->getKeyColunm()));

        $id = $refMe->invoke($object);

        if (! self::isEmpty($id)) { //caso o id exista ele


            $q1 = "SELECT " . $this->getKeyColunm() . " FROM " . $this->getTableName() . " WHERE " . $this->getKeyColunm() . "=" . $id;

            //mysqli_query(self::getConn() , $q1);
            $r1 = self::executeQuery($q1);

            //            if (strlen(mysqli_error(self::getConn())) > 0) {
            //                throw new CRUDException("SQL ERROR: $q1 - " . mysqli_error(self::getConn()));
            //            }

            $executeUpdate = false;

            if(CRUDQuery::isPDOMode()) {
                if ( $r1 && $r1->rowCount() > 0) {
                    $executeUpdate = true;
                } else {
                    $executeUpdate = false;
                }
            } elseif(CRUDQuery::isMySqliMode()) {

                if(mysqli_num_rows($r1) > 0) {
                    $executeUpdate = true;
                } else {
                    $executeUpdate = false;
                }

            }


            if ( $executeUpdate ) {
                $object = $this->update($object); //se ja existir ele salva
                return $object;
            } else {
                $object = $this->insertObject($object); // se não existir ele insere

                return $object;
            }
        } else {
            $object = $this->insertObject($object); //caso não exista id setado ele insere
            return $object;
        }
    }

    /**
     * Atualiza no banco de dados um objeto, instancia de classe className, com os dados especificados que foram preenchidos
     * propriedades não especificadas são ignoradas
     * @param mixed $object
     * @param boolean|string $whereCondition caso seja true serã utilizado o valor de keyColumn
     * @param string $additionalParameters optional parametros adicionais para o UPDATE
     * @param int $limite=1 optional limite de objetos que serão atualizados, DEFAULT=1
     */
    public function update($object, $whereCondition = "", $topLevelClass = "") {

        if (self::isEmpty($topLevelClass)) {
            $topLevelClass = self::getTopLevelClass();
        }

        $tableName = "";
        $whereJoin = "";
        $contParent = 0;

        //$this->ref = new ReflectionClass($object);


        $ref1 = new ReflectionClass($object);
        $this->ref =  $ref1;

        $parentClassesArray = array ($this->ref );

        $r = $this->ref;

        while ( $r->getParentClass()->getName() != $topLevelClass ) {
            $r = $this->ref->getParentClass();
            $parentClassesArray[] =  $r;

        }

        $parentClassesArray = array_reverse($parentClassesArray);

        $fkId = "";
        foreach ( $parentClassesArray as $ref ) {
            /*@var $ref ReflectionClass*/
            $colunas = "";
            $valores = "";

            $ai = new ArrayIterator($ref->getDefaultProperties());

            /**
             * @var $tempTableName string  nome da tabela q sera abaliado abaixo
             */
            $tempTableName = self::$tablePrefix . $this->upperToUnderline($ref->getName());

            $tableName .= $tempTableName . ' , ';

            while ($ai->valid()) {
                /*@var $propertie ReflectionProperty*/
                //verificar se as superclasse num tão pegando tudo


                $propertie = $ai->key();

                if ($this->validateAttributeColunm($propertie) and $propertie != self::DEFAULT_KEY_COLUNM) {

                    $valor = null;
                    $val = null;
                    $strCod = '$val = addslashes($object->' . MethodSintaxe::buildGetterName($propertie) . '());';
                    eval($strCod);

                    if ($val === false || ! self::isEmpty($val) || is_int($val)) {
                            $colunas .= $tempTableName . '.' . $propertie . "= '";
                            $valor .= $val;
                            $colunas .= $valor . "' , ";
                    }

                }

                $ai->next();

            }

            $contParent ++;

            //se tiver pelomenos uma classe pai
            if ($contParent > 1) {
                $whereJoin = $tempTableName . ".id = " . self::$tablePrefix . $this->upperToUnderline($parentClassesArray [$contParent - 2]->getName()) . ".id AND ";
            }

            if (! self::isEmpty($whereJoin)) {
                $whereJoin = substr($whereJoin, 0, - 4);
            }

            $tableName = substr($tableName, 0, - 2);
            
//            if (! self::isEmpty($fkId)) { echo $fkId . '<hr>';
//                $colunas .= self::DEFAULT_KEY_COLUNM . " , ";
//                $valores .= $fkId . ' , ';
//            }

            $colunas = substr($colunas, 0, - 2);



            if (! self::isEmpty($whereCondition) && ! is_int($whereCondition)) {
                if (substr($whereCondition, 0, 6) != " WHERE") {
                    $whereCondition = " WHERE $tempTableName." . $whereCondition;
                }
            } elseif (is_int($whereCondition)) {
                $this->setKeyColunm(self::DEFAULT_KEY_COLUNM);
                $whereCondition = " WHERE ". $this->getKeyColunm() . " =" . $whereCondition;
            } else {
                $whereCondition = " WHERE ". $this->getKeyColunm() . "=(" . $object->getId() . ")";
            }

            if (func_num_args() > 2)
                $additionalParameter = func_get_arg(3);
            else
                $additionalParameters = "";

            /*@var $refMet ReflectionMethod */
            $tableName = self::$tablePrefix . $this->upperToUnderline($ref->getName());

            $q1 = "UPDATE " . $tableName . " SET $colunas $whereCondition $additionalParameters";

            //mysqli_query(self::getConn() , $q1);
            self::executeQuery($q1);

                $fkId = CRUDQuery::lastInsertId();

            //            if (strlen(mysqli_error(self::getConn())) > 0) {
            //                throw new CRUDException("SQL ERROR: $q1 - " . mysqli_error(self::getConn()));
            //            }


        }

        return $object;
    }

    /**
     * Deleta objetos objeto da tabela no banco conforme os dados fornecidos.
     * Se $whereCondition==true sera utilizado como condiãão para exclusão o campo chave da tabela ($keyColumn)
     * @param mixed $object
     * @param boolean|string $whereCondition caso seja true serã utilizado o valor de keyColumn
     * @param string $additionalParameters optional parametros adicionais para o DELETE
     */
    public function delete($object = null, $whereCondition = "") {
        if ($object == null)
            $object = $refClass->newInstance();

        if (self::isEmpty($whereCondition)) {
            $whereCondition = (int) $object->getId();
        }

        //	try {
        //	    $this->ref = new ReflectionClass($this->getClassName());
        //	} catch ( ReflectionException $ex ) {
        //	    echo "A Classe especificada não existe: " . $ex->getMessage();
        //	}


        /*************************************************************************
         * FAZ AS INTERAÇÕES COM AS SUPER CLASSES
         * varre as superClasses e carrega as informações das suas respectivas
         * tabelas, também adiciona as colunas que serão processadas na reflexão
         * todos os atributos das superclasses
         ************************************************************************/

        $parentClassesArray = array ($this->ref );

        while ( $this->ref->getParentClass()->getName() != self::getTopLevelClass() ) {
            $ref = $this->ref->getParentClass();
            $parentClassesArray [] = $ref;
        }

        $parentClassesArray = array_reverse($parentClassesArray);

        $tableName = "";

        $contParent = 0;
        foreach ( $parentClassesArray as $parentRef ) {
            /* @var $parentRef ReflectionClass */

            $contParent ++;

            /**
             * @var $tempTableName string  nome da tabela q sera abaliado abaixo
             */
            $tempTableName = self::$tablePrefix . $this->upperToUnderline($parentRef->getName());

            $tableName .= $tempTableName . ' , ';

            //se tiver pelomenos uma classe pai
            if ($contParent > 1) {
                $whereJoin = $tempTableName . ".id = " . self::$tablePrefix . $this->upperToUnderline($parentClassesArray [$contParent - 2]->getName()) . ".id AND ";
            }

            if (! self::isEmpty($whereCondition) && ! is_int($whereCondition)) {
                if (substr($whereCondition, 0, 6) != " WHERE") {
                    $whereCondition = " WHERE " . $whereCondition;
                }
            } elseif (is_int($whereCondition)) {
                $this->setKeyColunm(self::DEFAULT_KEY_COLUNM);
                $whereCondition = " WHERE " . $this->getKeyColunm() . " = " . $whereCondition;
            } else {

            }

            $q1 = "DELETE FROM " . $tempTableName . " $whereCondition ";

            $r1 = self::executeQuery($q1);
            //mysqli_query(self::getConn() , $q1);


            //            if (strlen(mysqli_error(self::getConn())) > 0) {
            //                throw new Exception("SQL ERROR: $q1 - " . mysqli_error(self::getConn()));
            //            }


        }

    }

    /**
     * Seta o nome da classe que serã analisada
     * @param string $className
     * @param boolean $encapsuled opcional - determina se as propriedades da classes estarão encapsulados
     */
    public function setClassName($className) {
        $this->className = $className;
    }

    /**
     * Nome da classe analisada
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * Seta o nome do identificador das colunas na tabela do Banco de Dados, caso exista todas
     * as colunas da tabela deverão de ter o mesmo identificador, sempre adicionado no Final do nome da coluna
     * <br />
     * <br />
     * Se o $conlunIdentifier não for especificado, o valor assumido serã da constante: <br />
     * DEFAULT_COLUMN_IDENTIFIER_CONST = "DB_TABLE_ID" - onde DB_TABLE_ID deverã ser uma constante
     * na classe analisada com o valor do identificador
     *
     * @param string $conlunIdentifier=""
     */
    public function setColumnIdentifier($conlunIdentifier = "") {
        if (self::isEmpty($conlunIdentifier)) {
            //$this->ref = new ReflectionClass($this->getClassName());
            $this->colunIdentifier = $this->ref->getConstant(self::DEFAULT_COLUMN_IDENTIFIER_CONST);
        } else {
            $this->colunIdentifier = $conlunIdentifier;
        }
    }

    /**
     * Retorna um array associativo com as propriedades de um objeto encapsulado
     * Para usos diversos, a array retornada segue o padrão <br />
     * $array["PROPRIEDADE_NAME"] = "VALOR DA PROPRIEDADE";
     * @param mixed $objeto
     * @return mixed
     */
    public function associativeArrayFromEncapsuledObject($objeto, $exeptions = array()) {

        $ref = $this->ref;

        $met = $ref->getMethods();
        $ai = new ArrayIterator($met);
        $minhaArray = array ();
        while ( $ai->valid() ) {

            self::$xmlStr .= "<" . $ref->getName() . ">";

            $metName = $ai->current()->getName();

            if (substr($metName, 0, 3) == "get") {
                $prop = substr($metName, 3);
                $prop = strtolower(substr($prop, 0, 1)) . substr($prop, 1);

                $carregar = true;
                foreach ( $exeptions as $ex ) {
                    if ($prop == $ex) {
                        $carregar = false;
                        break;
                    }
                }

                if ($carregar && ! $ai->current()->isStatic()) {

                    $minhaArray [$prop] = $ai->current()->invoke($objeto);
                }
            }

            self::$xmlStr .= "</" . $ref->getName() . ">";

            $ai->next();
        }
        return $minhaArray;
    }

    /**
     * Nome do identificador da tabela no banco de dados
     * @return string
     */
    public function getColumnIdentifier() {
        return $this->colunIdentifier;
    }

    /**
     * Nome da tabela no Banco de dados que corresponde a classe especificada
     * @param string $tableName="" Opcional Nome da tabela do Banco de Dados
     */
    public function setTableName($tableName = "") {
        if (self::isEmpty($tableName)) {
            $this->tableName = $this->buildTableName($this->className);

        } else {
            $this->tableName = $tableName;
        }
    }

    /**
     * Nome da Tabela corrente no banco de dados
     * @return string nome da tabela
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * Se os campos da classe estão encapsulados ou não
     * @return boolean
     */
    public function getEncapsuled() {
        return $this->encapsuled;
    }

    /**
     * Determina se as propriedades da classe especificada estarão encapsulados ou não
     * @param boolean $encapsuled
     */
    public function setEncapsuled($encapsuled) {
        $this->encapsuled = $encapsuled;
    }

    /**
     * Liga o Debug de cãdigos, onde exibira as SQLs dos metodos e trechos de Cãdigos que são executados
     * pela classe
     */
    public static function setDebugOn() {
        self::$debug = true;
    }

    /**
     * @see CRUD::setDebugOn
     */
    public function enableDebug() {
        self::setDebugOn();
    }

    /**
     * Desliga o Debug caso ele esteja habilitado
     */
    public static function setDebugOff() {
        self::$debug = false;
    }

    /**
     * Seta a coluna chave da tabela do banco que representa o objeto analisado
     * @param string $keyColumn
     */
    public function setKeyColunm($keyColumn) {
        if (! self::isEmpty($this->colunIdentifier)) {
            $sizeIden = strlen($this->colunIdentifier);
            if (substr($keyColumn, $sizeIden * - 1, $sizeIden) != $this->colunIdentifier)
                $this->keyColumn = $keyColumn . $this->getColumnIdentifier();
            else
                $this->keyColumn = $keyColumn;
        } else {
            $this->keyColumn = $keyColumn;
        }
    }

    /**
     * Converte os caracteres maiusculos em minusculos adicionando um underline
     * como espaço entre as palavras (o underline pode ser substituido caso o
     * divisor seja alterado)
     *
     * Copia da classe UtilString apenas por segurança
     *
     * @param string $string string que deve ser convertida
     * @param string $divisor divisor entre as palavras
     * @return string string formatada
     */
    private function upperToUnderline($string, $divisor = "_") {
        $string = strtolower($string {0}) . substr($string, 1);
        foreach ( str_split($string) as $caracter ) {
            $string = str_replace(strtoupper($caracter), $divisor . strtolower($caracter), $string);
        }

        return $string;
    }

    /**
     * Coluna chave da tabela do banco que representa o objeto analisado
     * @return string
     */
    public function getKeyColunm() {
        return ($this->keyColumn);
    }

    /**
     * Retorna o array de atributos iniciadas como array da classe analisadas
     * @return array
     */
    public function getAttributesTypeArray() {
        return $this->attributesTypeArray;
    }

    /**
     * Obtem o nome da tabela de uma tabela do banco a partir do nome de uma
     * classe
     * @param string $className
     * @return string nome da classe
     */
    public static function getTableNameFromClassName($className) {
        require_once 'util/UtilString.php';
        return self::$tablePrefix . UtilString::upperToUnderline($className);
    }

    /**
     * Obtem a classe do topo da hierarquia que deve ser analisada
     * @return string
     */
    public static function getTopLevelClass() {
        return self::$topLevelClass;
    }

    /**
     * Define o tipo de escrita dos métodos para camelCase ou não
     * camelCase usa maiusculas entre os nomes, se falso deve ser colocado um
     * underline entre as palavras
     * @param $isCamelCase
     */
    public static function setMethodSintaxe($isCamelCase = true) {
        self::$camelCase = $isCamelCase;
    }

    /**
     * Obtem a o resorce da conexõa mysqli
     * @return PDO
     */
    public static function getConn() {
        return self::$conn;
    }

    /**
     * Especifica o resource de uma conexão mysqli
     * @param resource $conn
     * @return resource
     */
    public static function setConn($conn) {
        self::$conn = $conn;
    }

    /**
     * Especifica o nível de acesso aos objetos aninhados
     * @param int $depth
     */
    public static function setDepth($depth) {
        $this->depth = $depth;
    }

    /**
     * Executa uma query qualquer
     * @param string $sqlQuery
     * @return array
     */
    public static function query($sqlQuery) {
        return CRUDQuery::query($sqlQuery);
    }

    /**
     * Unico ponto em que seão executadas as querys da crud
     * @param string $sql
     * @return PDOStatement|resource
     */
    public static function executeQuery($sql) {
        if (self::$debug || self::$debugSql) {
            echo $sql . "<hr />";
        }
        
        return CRUDQuery::executeQuery($sql);
    }

    /**
     * Testa se esta ou naum sendo utilizada o cache de objetos
     * @return boolean
     */
    public static function isObjectsCached() {
        return self::$useObjCache;
    }

    /**
     * Obtem o objeto PDo responsavel pelas querys
     * @return PDO
     */
    public static function getPDO() {
        return self::$conn;
    }


}