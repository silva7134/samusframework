<?php
require_once 'samus/Samus_ModelController.php';

/**
 * Description of CidadeCO.php
 *
 * @author SamusDev - samus@samus.com.br
 */
class CidadeCO extends Samus_ModelController  {

    /**
     * Obtem options para popular um select a partir de um estado, onde o ID da
     * cidade é o valor e o Label o nome da cidade
     * Ex.:
     * <option value='1'>Acrelandia</option>
     *
     * @param int $estadoId
     * @return string com os options
     */
    public function getCidadeOptionsByEstado($estadoId) {
        $str = "";
        
        foreach($this->getDao()->find("estado=$estadoId", 'nome ASC') as $c) {
            $str .= '<option value="'.$c['id'].'">'.$c['nome'].'</option>';
        }
        return $str;
    }


}
?>
