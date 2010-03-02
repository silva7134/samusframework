<?php
require_once 'CRUD/DAO.php';
require_once 'util/UtilString.php';
require_once 'CRUD/TableFactory.php';

/**
 * A Classe Samus_Model é responsável por analizar a classe e ligar ela com a camada de
 * persistência de dados, ela é capaz de criar as tabelas que representam as
 * modelos ("modelo" ou "Samus_Model" representa uma classe de Modelo do Curral. Todas as
 * classes de modelo devem extender a Samus_Model "class Modelo extends Samus_Model()".
 * <br />\n
 * <br />\n
 * Os PHPDoc dos atributos são parte do código, a sintaxe é a mesma do PHPDoc
 * normal utilizado, a diferenã é que depois da declaração do tipo da variável
 * deve ser especificado o tipo de dado da coluna na tabela (simples não) e o
 * nome do atributo será o nome da coluna. O nome da tabela criada é o nome do
 * atributo 'name' do PHPDoc, espaços e caracteres especiais são removidos.
 * <br />\n
 * Diferente de outros framework que usam convenções obscuras, a idéia é manter
 * claro as coisas que o framework esta fazendo por trás do código
 * <br />
 * <br />
 * Associações 1 para 1 são feitas seguindo o Padrão CRUD (veja na documentação)
 *
 *
 *
 * \@var string varchar(120)
 * private $variavel;
 *
 *
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @package Samus
 */
abstract class Samus_Model extends DAO {

    public $_co = null;

    public function __construct($id="") {
        parent::__construct($id);
    }

    /**
     *
     * @return DAO_CRUD
     */
    public function getDao() {
        return  parent::getDao();
    }


    /**
     * @return Samus_CO
     */
    public function getCO() {
        require_once 'samus/Samus_ModelController.php';

        $className = $this->getDao()->getClassName().Samus_ModelController::DEFAULT_CO_SUFIX;

        if($this->_co instanceof $className) {
            return $this->_co;
        } else {

            if(class_exists($className, true)) {
                
                $evalStr = '$this->_co = new $className($this);';
                eval ($evalStr);
                return $this->_co;
            } else {
                throw new CRUDException("A classe $className não existe, verifique se o nome esta correto ou se ela existe", "");
            }

        }

    }

    /**
     * Define se o objeto ja esta carregado,
     * Retorna verdadeiro caso o atributo $id possua algum valor
     *
     * @return boolean
     */
    public function isEmpty() {
        return empty($this->id);
    }

    public function get_co() {
        return $this->_co;
    }
    
    public function set_co($_co) {
        $this->_co = $_co;
    }



}
?>