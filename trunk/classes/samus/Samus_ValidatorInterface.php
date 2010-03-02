<?php

/**
 * Interface com as assinaturas dos mщtodos de validaчуo de uma classe
 * 
 * @author Vinicisu Fiorio - samusdev@gmail.com
 * @package samus
 */
interface Samus_ValidatorInterface {
    
    /**
     * Mensagem em caso de erro
     * @param string $msg
     * @return string
     */
    public function showErrorMsg($msg);
    
    /**
     * Mensagem em caso de sucesso
     * 
     * @param string $msg
     * @return string
     */
    public function showSucessMsg($msg);
    
    /**
     * Validacao para os mщtodos que salvam
     * 
     * $object->getDao()->save();
     * $object->getDao()->saveObjectArray();
     * $object->getDao()->saveXml();
     * 
     * @param object $object
     * @return boolean
     */
    public function saveAction($object);
    
    /**
     * Validaчуo para mщtodos que deletam
     * 
     * $object->getDao()->delete();
     * $object->getDao()->ObjectArray();
     * 
     * @param object $object
     * @return boolean
     */
    public function deleteAction($object);
    
    /**
     * Validaчуo para mщtodos de carregamento de um objeto unicos
     * 
     * $object->getDao()->load();
     * * $object->getDao()->loadLast();
     * * $object->getDao()->loadFirst();
     * 
     * @param object $object
     * @return boolean
     */
    public function loadAction($object);
    
    /**
     * Validaчуo para mщtodos que carregam arrays e arrays de objetos
     * 
     * $object->getDao()->loadArrayList();
     * $object->getDao()->loadAssociativeArrayList();
     * $object->getDao()->loadLightArray();
     * 
     * @param object $object
     * @return boolean
     */
    public function loadArrayAction($object);
    
    /**
     * Executado sempre que o __tostring щ invocado
     * 
     * $object->getDao()->__tostring();
     * 
     * @param object $object
     * @return boolean
     */    
    public function toStringAction($object);

    
}

?>