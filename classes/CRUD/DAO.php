<?php
require_once 'CRUD/TableFactory.php';
require_once 'CRUD/Singleton.php';
require_once 'CRUD/CRUD.php';
require_once 'CRUD/DAO_CRUD.php';
require_once 'CRUD/DAOInterface.php';

/**
 * DAO - Dynamic Acess Object
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.1
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 * @abstract
 */
abstract class DAO extends Properties implements DAOInterface {

    /**
     * Nome da coluna que contem o ID da entidade
     * @var string
     */
    const PRIMARY_KEY_NAME = "id";

    /**
     *
     * @var int INTEGER(11) auto_increment
     */
    protected $id;

    private static $dbTable;

    /**
     * Instancia do DAO responsсvel por todas as operaчѕes
     * @var DAO_CRUD
     */
    private $dao = null;

    /**
     * Construtor de um Objeto DAO, se informado ID serс carregado a instancia
     * com o ID especificado.
     *
     *  Caso TableFactory estiver ativado fara a verificaчуo da existъncia da
     *  tabela da entidade no BD.
     *
     * @param $id
     * @return unknown_type
     */
    public function __construct($id = null) {

        if(TableFactory::isCreateTablesEnabled()) {
            $tf = new TableFactory($this);
        }

        if (!empty($id)) {
            $this->getDao()->load((int) $id);
        }

    }

    /**
     * Constroi o nome da classe dentro do padrуo CRUD
     * letras maiusculas)
     *
     * @param string $className nome da tabela
     */
    public function buildTableName($className) {
        return CRUD::$tablePrefix . UtilString::upperToUnderline($className);
    }

    /**
     * @see Persistent::getDbTable()
     * @return string nome da tabela especificada
     */
    public static function getDbTable() {
        return self::$dbTable;
    }

    /**
     *
     *
     * @param string $tableName nome da tabela
     */
    protected static function setDbTable($tableName) {
        self::$dbTable = $tableName;
    }

    /**
     * Inicia o objeto DAO, qualquer mщtodo criado passa por este mщtodo
     * @return void
     */
    private function buildDAO() {
        if ($this->dao == null) {
            $this->dao = new DAO_CRUD();
            $this->dao->setObject($this);
        }
    }

    /**
     * Obtem o DAO correspondente
     * @return DAO_CRUD
     */
    public function getDao() {
        $this->buildDAO();
        return $this->dao;
    }

    /**
     * @return DAO_CRUD
     */
    public function get_dao() {
        return $this->getDao();
    }

    /**
     * Obtem a chave primсria
     * @return int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * Seta o a chave primaria
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Obtem uma instancia do objeto DAO,
     *
     * @param string $className
     * @param array $args
     * @return object
     */
    public static function getInstance($className = "", $args = array()) {
        $ref = new ReflectionClass($className);
        return $ref->newInstance($args);
    }

    /**
     * Obtem o ID
     * @return string
     */
    public function __toString() {
        return (string)$this->getId();
    }

    public function set($propertie , $value) {
        $strEval = '$this->'.$propertie.'=$value;';
        eval ($strEval);
    }


    /**
     * Ordena um array de Objetos a partir de uma propriedade qualquer, щ possэvel
     * ordenar por uma propriedade de uma propriedade que seja tambщm um objeto
     *
     * Ex.:
     * DAO::orderBy($array , 'name');
     * DAO::orderBy($array , 'property->property->property->name');
     *
     * @param array $objectArray
     * @param string $propertyName
     * @param boolean $desc se serс em ordem inversa
     * @return void;
     */
    public static function orderBy(&$objectArray, $propertyName, $desc = false) {

        $isObj = false;
        if ($objectArray instanceof Matrix) {
            $objectArray = $objectArray->getArrayCopy ();
            $isObj = true;
        }

        function _daoObjSort(&$objArray, $indexFunction, $propertyName, $desc, $sort_flags = 0) {
            $indices = array ();
            foreach ( $objArray as $obj ) {
                $indeces [] = $indexFunction ( $obj, $propertyName );
            }

            $c = array_multisort ( $indeces, $objArray, $sort_flags );

            if ($desc) {
                $objArray = array_reverse ( $objArray );
            }
        }

        function _daoGetIndex($obj, $propertyName) {
            $val = null;
            $strEval = '$val = $obj->' . $propertyName . '; ';
            eval ( $strEval );
            return $val;
        }

        _daoObjSort ( $objectArray, '_daoGetIndex', $propertyName, $desc );

        if ($isObj) {
            return new Matrix ( $objectArray );
        } else {
            return $objectArray;
        }

    }

}

?>