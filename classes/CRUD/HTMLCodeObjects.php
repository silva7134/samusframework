<?php

/**
 * Agrupa métodos para geração de HTML a partir de Objetos e listas de objetos
 *
 * 
 * @author Vinicius Fiorio Custodio - Samusdev@gmail.com
 * @version v 1.0.1
 * @copyright GPL - General Public License
 * @license http://www.gnu.org
 * @link http://www.Samus.com.br
 * @category CRUD
 * @package CRUD
 */
class HTMLCodeObjects {

    /**
     * Obtem uma tabela escrita em html bem formatada de todos os dados
     * retornados, para customização da tabela é possível especificar classes
     * css prédefinidas para estilização de cores, são as seguintes
     *
     * crud-table 			(aplicado ao elemento table)
     * crud-table-tr-color1	(aplicado ao elemento tr - linhas)
     * crud-table-tr-color2	(alternando com a cor1 esta classe tbm é aplicada ao tr)
     *
     * crud-table-td-color1	(aplicado ao elemento td - colunas)
     * crud-table-td-color2	(aplicado ao elemento td - colunas)
     *
     *
     *
     * @param string|int $whereCondition
     * @param string $order
     * @param string|int $limit
     * @param string $cssClass
     * @param string $cssClassCor1
     * @param string $cssClassCor2
     * @return string
     */
    public static function htmlTableFromData($object , $whereCondition = "", $order = "", $limit = ""
    , $cssClass="crud-table" , $cssClassTRCor1="crud-table-tr-color1" , $cssClassTRCor2="crud-table-tr-color2"
    , $cssClassTDCor1="crud-table-td-color1" , $cssClassTDCor2="crud-table-td-color2") {

        $array = $object->getDao()->find($whereCondition , $order , $limit);
        $str = "\n<table class='$cssClass' cellpadding='0' cellspacing='0' ><thead>";

        $head = "";
        $head .= "<tr>";
        $ai = new ArrayIterator($array[0]);
        while($ai->valid()) {
            $head .= "<th>".UtilString::underlineToSpace(UtilString::upperToSpace($ai->key()))."</th>";
            $ai->next();
        }
        $head .= "</tr>";

        $str .= $head;

        $str .= "</thead><tbody>"; 

        $cont = 0;
        foreach($array as $a) {
            if($cont%2==0) {
                $str .= "<tr class='$cssClassTRCor1'>";
            } else {
                $str .= "<tr class='$cssClassTRCor2'>";
            }

            $cont2 = 0;
            foreach($a as $val) {
                $class="";
                if($cont2%2==0) {
                    $class = $cssClassTDCor1;
                } else {
                    $class = $cssClassTDCor2;
                }

                $str .= "<td class='$class'>";
                if(empty($val)) {
                    $val = '&nbsp';
                }
                $str .= $val;

                $str .= "</td>";            
                ++$cont2;
            }
            $str .= "</tr>";
            ++$cont;
        }

        $str .=   "</tbody><tfoot>$head</tfoot></table>\n";

        return $str;
    }


    /**
     * Obtem um formulário simples para um objeto DAO
     * 
     * @param object $object
     * @return string
     */
    public static function htmlForm($object , $objectName='obj') {
        $crud = $object->getDao()->myCRUD();
        $str = "
<fieldset>
	<legend>".ucwords(UtilString::upperToSpace($crud->getClassName()))."</legend>
        ";
        $str .= "<form method='post' action=''>
        ";

        foreach($crud->getAtributes() as $atr) {
            
            if($atr == "id") {
                $str .= "<input type='hidden' name='id' value='".Samus::getLeftDelimiter()." $". $objectName. "->$atr. ".Samus::getRightDelimiter()."' />
                ";
            } else {
                
                $str .= "<label for='$atr'>".UtilString::underlineToSpace(UtilString::upperToSpace($atr)).'</label>
                ';
                $str .= "<input type='text' name='$atr'  value='".Samus::getLeftDelimiter()." $". $objectName. "->$atr ".Samus::getRightDelimiter()."' />
		<br />
                
                ";
            }
            
        }
        
        $str .= "
        <label for='action'></label>
        <input type='submit' name='action' value='Confirmar'>
        ";
        
        $str .= "</form>
</fieldset>
        ";
        return $str;
    }
}


?>