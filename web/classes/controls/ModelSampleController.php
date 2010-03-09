<?php
require_once 'models/ModelSampleType.php';
require_once 'models/ModelSample.php';
/**
 * Exemplo de uso de classe modelo / Persistência
 */
class ModelSampleController extends Samus_Controller {

    public function index() {

        TableFactory::enableCreateTables();
        new Pais();
        new ModelSampleType();
        new ModelSample();
        TableFactory::disableCreateTables();

        $modelSample = new ModelSample();
        $modelSampleType = new ModelSampleType();


        $modelSampleType->setNome("Tipo1");
        $modelSampleType->getDao()->save();

        $modelSample->setName("Samus Framework");
        $modelSample->setDate("1987-07-06 00:00:00");
        $modelSample->setBoolean(true);
        $modelSample->setNumber(7);
        $modelSample->setModelSampleType($modelSampleType);

        $modelSample->getDao()->save();


        var_dump($modelSample->getDao()->loadArrayList());
        var_dump($modelSample->getDao()->find());
        var_dump($modelSample->getDao()->loadFirst());
        var_dump($modelSample->getDao()->loadLast());
        var_dump($modelSample->getDao()->search("samus"));
        

    }

}

?>
