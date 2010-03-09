<?php


class ModelSample extends Samus_Model {

    /**
     * Tipo seguido do tipo no banco de dados
     * @var string VARCHAR(90)
     */
    private $name;

    /**
     * Numeros
     * @var int INTEGER
     */
    private $number;

    /**
     * Datas
     * @var string DATETIME
     */
    private $date;

    /**
     * Define um booleano
     * @var boolean BOOLEAN
     */
    private $boolean;

    /**
     * Uma associação de objetos
     * @var ModelSampleType INTEGER
     */
    private $modelSampleType;
    

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getBoolean() {
        return (boolean) $this->boolean;  // tipagem de booleanos (opcional) facilita algumas operações
    }

    public function setBoolean($boolean) {
        $this->boolean = (boolean) $boolean;
    }

    /**
     * @return ModelSampleType
     */
    public function getModelSampleType() {
        return $this->modelSampleType;
    }

    public function setModelSampleType(ModelSampleType $modelSampleType) { // tipagem de parametro OBRIGATORIO para gerar as associações de objetos
        $this->modelSampleType = $modelSampleType;
    }


    
}


?>
