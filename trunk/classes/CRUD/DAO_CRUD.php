<?php
require_once "CRUD/Persistent.php";
require_once "CRUD/CRUD.php";
require_once "CRUD/Properties.php";
require_once 'util/UtilString.php';
require_once 'CRUD/Singleton.php';

/**
 * Classe DAO_CRUD
 *
 * Através dessa classe é possível acessar os métodos da classe CRUD de forma
 * mais amigável e correta. As entidades devem extender a classe abstrata DAO
 * já que esta gerencia as instancias da DAO_CRUD e faz um acesso mais leve
 * aos métodos abaixo.
 *
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.1.1
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 */
class DAO_CRUD extends Properties implements Persistent {

    /**
     * Instancia unica para CRUD
     * @var CRUD
     */
    private $crud = null;

    /**
     * Objeto analizado
     * @var object
     */
    protected $object;

    /**
     * Nome da tabela
     * @var string
     */
    public static $dbTable;

    /**
     * Obtem o nome da tabela assocaida
     * @return string
     */
    public static function getDbTable() {
        return self::$dbTable;
    }

    /**
     * Construtor, passando o id carrega o elemento
     * @param int $id
     * @return void
     */
    public function __construct( $id = "") {
        if (! empty($id)) {
            $this->load($id);
        }
    }

    /**
     * Carrega os atributos multivalorados
     * @param string $order
     * @param string $limit
     * @param boolean $loadInternalObjects se os objetos internos devem ser carregados
     * @return mixed
     */
    public function loadMultivaluedProperties( $order = "",  $limit = "",  $loadInternalObjects = false) {
        $crud = $this->myCRUD();
        $crud->loadMultivaluedProperties($this->object , $order , $limit , $loadInternalObjects);
        return $this->object;
    }

    /**
     **
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
     * @param string $propertieName nome da propriedade
     * @param string $order ordem de carregamento dos elementos
     * @param string|int $limit
     * @param boolean $loadInternalObjectAtributes
     * @return mixed
     */
    public function load_N_2_1_Propertie( $propertieName,  $multivaloredClassName = "",  $order = "",  $limit = "",  $loadInternalObjectAtributes = false) {
        $crud = $this->myCRUD();
        $crud->load_N_2_1_Propertie($this->object , $propertieName , $multivaloredClassName , $order , $limit , $loadInternalObjectAtributes);
        return $this->object;
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
     * @param string $propertieName
     * @return mixed
     */
    public function load_N_2_N_Propertie( $propertieName,  $associativeClassName = "",  $order = "",  $limit = "",  $loadInternalObjectAtributes = true) {
        $crud = $this->myCRUD();
        $crud->load_N_2_N_Propertie($this->object , $propertieName , $associativeClassName , $order , $limit , $loadInternalObjectAtributes);
        return $this->object;
    }

    /**
     * Seta os atributos que serão carregados nas operações
     *
     * @param string $atribute1
     * @param string $atribute2 ...
     */
    public function setAtributes( $atribute1) {
        $atributes = func_get_args();
        $this->myCRUD()->setAtributes($atributes);
    }

    /**
     * Limpa os atributos caso tenham sido especificados
     */
    public function clearAtributes() {
        $this->myCRUD()->clearAtributes();
    }

    /**
     * Retorna um node XML do objeto, as tags HTML dos conteudos dos obetos são
     * codigicados por htmlentities()
     *
     * @param string $whereCondition condição de carregamento
     * @param string $order ordem dos registros
     * @param string $limit limite de registros
     * @param boolean $lightMode se serão carregados os objetos dentro dos objetos
     * @param boolean $addXmlRootTags
     * @return string node xml dos registros
     */
    public function loadXml( $whereCondition = "",  $order = "",  $limit = "",  $lightMode = false,  $addXmlRootTags = false) {

        $crud = $this->myCRUD();

        $crud->loadLightArray($whereCondition , $order , $limit , false , ! $lightMode , true);

        $str = CRUD::$xmlStr;

        if ($addXmlRootTags) {
            $str = '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>' . $str . "</root>";
        }

        CRUD::$xmlStr = "";

        return $str;

    }

    /**
     * Salva o objeto em $filename no formato xml
     *
     * @param string $filename
     * @param string|int $whereCondition
     * @param string $order
     * @param string|int $limit
     * @param boolean $lightMode
     * @param string $fopenMode w - zera o arquivo _ a - continua de onde parou
     * @return boolean caso o arquivo seja criado
     */
    public function saveXml( $filename,  $whereCondition = "",  $order = "",  $limit = "",  $lightMode = false,  $fopenMode = "w") {

        if ($this->invokeValidatorMethod(self::$validatorMethods["save"])) {
            if (! $handle = fopen($filename , $fopenMode)) {
                throw new Exception("O arquivo não pode ser aberto");
            }

            if (is_writable($filename)) {

                $strXml = $this->tSamus_Controllerml($whereCondition , $order , $limit , $lightMode , TRUE);

                if (fwrite($handle , $strXml) === FALSE) {
                    throw new Exception("Não foi possível escrever no arquivo ($filename)");
                }

                fclose($handle);

                return true;
            }
            else {
                return false;
            }
        }
    }

    /**
     * Carrega o objeto a partir do seu id ou de uma condição, o funcionamento é
     * semelhanta ao WHERE de uma consulta MySql, caso whereCondition seja um
     * inteiro o objeto será carregado pelo ID da linha da tabela que representa
     * a entidade, se a string for menor do que 4 ela será automaticamente
     * convertido para int <br />
     * <br />
     * Ex.: <br />
     * $produto = new Produto(); <br />
     * $produto->load($id); <br />
     * var_dump($produto); <br />
     * <br />
     * <br />
     * Ex. 2: <br />
     * $usuario = new Usuario(); <br />
     * $usuario->load("email=$email AND senha=$senha"); <br />
     * var_dump($usuario);
     * @param int|string $id
     */
    public function load($whereCondition,  $loadArrayAtributes = false) {

        if (is_numeric($whereCondition)) {
            $whereCondition = (int)$whereCondition;
        }

        $crud = $this->myCRUD();

        $crud->mountObject($whereCondition , "" , $this->object);
        
        if ($loadArrayAtributes) {
            $crud->loadArrayAttributes($this->object);
        }


        return $this;

    }

    /**
     * Encontra um objeto por uma de suas propriedades
     *
     * @param string $property
     * @param string $value
     * @return object
     */
    public function findBy( $property,  $value) {
        return $this->load("$property='$value'");
    }

    /**
     * Carrega as proprieidades de um objeto a partir de um array associativo,
     * apenas as propriedades especificadas serão carregadas.: <br />
     * <br />
     * Ex.:
     * $array = array( "nome" => "Vnicius Fiorio" , "email" => "Samusdev@gmail.com");
     * $pessoa = new Pessoa();
     * $pessoa->loadObjectFromAssociativeArray($array);
     *
     * @param array $associativeArray
     */
    public function loadObjectFromAssociativeArray(array $associativeArray , $loadObjectAtributes=false) {
        $crud = $this->myCRUD();
        $crud->mountAssociativeObject($associativeArray , $this->object , $loadObjectAtributes);
    }

    /**
     * Lista os objetos da classe colocando em um vetor os objetos <br />
     * Ex.: <br />
     * $produto = new Produto(); <br />
     * $produtos_array = $produto->loadArrayList("categoria = 1"); <br />
     * var_dump($produtos_array); <br />
     * <br />
     *
     * @param string $whereCondition
     * @param string $order
     * @param string|int $limit
     * @return array
     */
    public function loadArrayList( $whereCondition = "",  $order = "",  $limit = "") {
        $crud = $this->myCRUD();
        return $crud->loadLightArray($whereCondition , $order , $limit , false , true);
    }
    
    /**
     * Carrega um array de LightObjects (onde os objetos aninhados não são carregados)
     * @param string $whereCondition
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function loadArrayListLight( $whereCondition = "",  $order = "",  $limit = "") {
        $crud = $this->myCRUD();
        return $crud->loadLightArray($whereCondition , $order , $limit , false , false);
    }
    /**
     * Analise a classe e as associaçoes e retorna um array associativo com os
     * resultados, é a consulta mais rápida dos dados mas não retorna Objetos,
     * ótimo para listagens que é essencial o desempenho
     *
     * @param string $whereCondition
     * @param string $order
     * @param string|int $limit
     * @return array array associativo
     */
    public function loadAssociativeArrayList( $whereCondition = "",  $order = "",  $limit = "") {

        $crud = $this->myCRUD();
        return $crud->loadLightArray($whereCondition , $order , $limit , true);
    }

    /**
     * Alias para loadAssociativeArrayList - carrega uma matriz de arrays
     * associativas dos objetos
     *
     * @see function loadAssociativeArrayList
     * @param string $whereCondition
     * @param string $order
     * @param string|int $limit
     * @return array array associativo
     */
    public function find( $whereCondition = "",  $order = "",  $limit = "",  $associativeArray = true) {
        $crud = $this->myCRUD();
        return $crud->loadLightArray($whereCondition , $order , $limit , $associativeArray);
    }

    /**
     * Carrega um array associativo da classe
     * @param string $sql
     * @return array associativo
     */
    public function findFromSql( $sql) {
        $crud = $this->myCRUD();
        return $crud->findFromSql($sql);
    }

    /**
     * Carrega um array de objetos leves, objetos leves são objetos que não tem
     * o seus atributos do tipo OBjeto carregados, eles requerem novas querys e
     * comprometem o desempenho de carregamento de longas listas
     *
     * @param string $whereCondition
     * @param string $order
     * @param string|int $limit
     * @return array
     */
    public function loadLightArray( $whereCondition = "",  $order = "",  $limit = "") {
        $crud = $this->myCRUD();
        return $crud->loadLightArray($whereCondition , $order , $limit);
    }

    /**
     * Carreta os atributos do tipo array do objeto, são as ligações 0..*
     *
     * classe tem com suas associa??es
     */
    public function loadObjectArrayAttributes( $order = null,  $limit = null) {
        $crud = $this->myCRUD();
        $crud->loadMultivaluedProperties($this->object , $order , $limit);
    }

    /**
     * Carrega uma lista de objetos a partir de uma sql qualquer, o caracter '?'
     * será substituido pelo nome da tabela corrente
     *
     * @param string $sql
     * @param $loadObjectAtributes se os atrivutos do tipo objeto serão carregados
     * @return array de objetos
     */
    public function loadArrayObjectsFromSql( $sql,  $loadObjectAtributes = true) {
        $crud = $this->myCRUD();
        return $crud->loadArrayFromSql($sql , $loadObjectAtributes);
    }

    /**
     * Carrega o ultimo objeto registrado no banco da entidade <br />
     * <br />
     * Ex.: <Br />
     * $produto = new Produto(); <br />
     * $produto->loadLast();
     * @param string $whereCondition
     */
    public function loadLast( $whereCondition = "") {
        $crud = $this->myCRUD();
        $crud->loadLastObject($whereCondition , $this->object);
    }

    /**
     * Carrega o primeiro objeto registrado na tabela da entidade que atendem
     * a condição especificada
     * <br />
     * Ex.: <Br />
     * $produto = new Produto(); <br />
     * $produto->loadFirst();
     * @param string $whereCondition
     */
    public function loadFirst( $whereCondition = "") {
        $crud = $this->myCRUD();
        $crud->loadLastObject($whereCondition , $this->object , true);
    }

    /**
     * Salva o objeto no banco. Caso o ID do objeto ja exita no banco o
     * mesmo será atualizado (UPDATE) senão ele será inserido (INSERT) <br />
     * Ex.: <br />
     * $produto = new Produto();<br />
     * $produto->setDescricao("Descrião do produto");<br />
     * $produto->setNome("Nome");<br />
     * $produto->setPreco(45,5);<br />
     * $produto->save();<br />
     * @return int|null id do objeto inserido, caso insert
     */
    public function save($validBefore=false) {
        $crud = $this->myCRUD();

        if($validBefore) {
            if($this->valid(false)) {
                return $crud->save($this->object);
            }
            else {
                return false;
            }
        }
        else {
            return $crud->save($this->object);
        }

    }

    /**
     * Salva um array de objetos, todos os objetos do array devem ser intances
     * da mesma classe
     *
     * @param array $objectArray
     */
    public function saveObjectArray(array $objectArray) {
        $crud = $this->myCRUD();
        foreach ( $objectArray as $obj ) {
            if (is_a($obj , get_class($this->object))) {
                $obj->getDao()->save();
            }
        }

    }

    /**
     * Deleta o objeto especificado <br />
     * Ex.: <br />
     * $produto = new Produto(); <br />
     * $produto->setId(1); <br />
     * $produto->delete(); <br />
     *
     * @param mixed $object
     * @param string $whereCondition
     * @return boolean
     */
    public function delete( $whereCondition = "") {
        $crud = $this->myCRUD();
        return $crud->delete($this->object , $whereCondition);
    }

    /**
     * Deleta uma lista de objetos, todos os objetos deletados devem obedecer a
     * condição especificada
     *
     * @param object $objectArray
     * @param string $whereCondition
     */
    public function deleteObjectArray( $objectArray,  $whereCondition = "") {
        $crud = $this->myCRUD();
        foreach ( $objectArray as $obj ) {
            if (is_a($obj , get_class($this->object))) {
                $obj->getDao()->delete($whereCondition);
            }
        }
    }

    /**
     * Lista todos os elementos a partir dos termos da busca, ele varre a tabela
     * do banco procurando em qualquer coluna da tabela pelo elemento especificado
     * por 'search'. <br />
     * <br />
     * Ex.: <br />
     * $produto = new Produto(); <br />
     * $martelos = $produto->search("martelo"); <br />
     * var_dump($martelos); <br />
     * <br />
     *
     *
     * @param string $search
     * @param string$whereCondtion
     * @param string $order
     * @param string|int $limit
     * @param boolean $exactKeyword
     * @param $light se é feita uma consulta leve
     * @return mixed[]
     */
    public function search( $search,  $whereCondtion = '',  $order = '',  $limit = '',  $exactKeyword = false,  $light = false , $returnAssociativeArray=false) {
        $exact = "";
        if (! $exactKeyword)
            $exact = "%";

        $crud = $this->myCRUD();

        $atrArray = $crud->getAtributes();
        $query = "(";
        $ai = new ArrayIterator($atrArray);
        while ( $ai->valid() ) {
            $query .= " " . $crud->getTableName() . "." . $ai->current() . " LIKE '$exact$search$exact'" . " OR ";
            $ai->next();
        }
        $query = substr($query , 0 , - 3);
        $query .= ")";

        

        if (! empty($whereCondtion)) {
            $whereCondtion = trim($whereCondtion);
            if (substr($whereCondtion , 0 , 3) != "AND" && substr($whereCondtion , 0 , 2) != "OR") {
                $whereCondtion = "AND " . $whereCondtion;
            }
            $query = $query . $whereCondtion;
        }

        if($returnAssociativeArray) {
            
            return $crud->loadLightArray($query , $order , $limit,true);

        }
        else {
            if ($light)
                return $crud->loadLightArray($query , $order , $limit);
            else {

                return $this->loadArrayList($query , $order , $limit);
            }
        }

    }

    /**
     * Retorna um array associativo do objeto: <br />
     * $array["propriedade"] = "valor";
     * @return array
     */
    public function associativeArray() {
        $crud = $this->myCRUD();
        return $crud->associativeArrayFromEncapsuledObject($this->object);
    }

    /**
     * Retorna um array associativo do objeto: <br />
     * $array["propriedade"] = "valor";
     * @return array
     */
    public function toArray() {
        return $this->associativeArray();
    }

    /**
     * @return CRUD
     */
    public function myCRUD() {
        if ($this->crud == null) {
            $ref = new ReflectionClass($this->object);
            $this->crud = Singleton::getInstance("CRUD" , $ref->getName() , $ref->getName());
            //$this->crud = new CRUD($ref->getName());
            $this->crud->addNoIndexAtribute("dao");
        }
        return $this->crud;
    }

    /**
     * @param CRUD $crud
     */
    public function setCrud( $crud) {
        $this->crud = $crud;
    }

    /**
     * Obtem a instancia do CRUD do objeto
     * @return CRUD
     */
    public function getCRUD() {
        return $this->crud;
    }

    /**
     * Especifica o objeto usado nas operações
     * @param $object
     */
    public function setObject( $object) {
        $this->object = $object;
    }

    /**
     * Retorna o ID da classe,
     * @return string
     */
    public function __tostring() {
        return (string)$this->object->getId();
    }

    /**
     * Valida o objeto utilizando RequestValidator
     * @param $showErrorMessages boolean define se a
     * @return boolean
     */
    public function valid($showErrorMessages=false) {
        require_once 'CRUD/RequestValidator.php';
        $req = new RequestValidator($this->object);

        if($showErrorMessages) {
            $req->init();
            echo $req->result();
            return $req->valid();
        }
        else {
            return $req->valid();
        }
    }

    /**
     * Valida o objeto retornando a mensagem de erro
     * @return string
     */
    public function validAndGetErrorMsg() {
        require_once 'CRUD/RequestValidator.php';
        $req = new RequestValidator($this->object);
        $req->init();
        return $req->result();
    }


    /**
     * Obtem o nome da classe modelo base
     * @return string nome da classe modelo base
     */
    public function getClassName() {
        return $this->myCRUD()->getClassName();
    }
    

}
?>
