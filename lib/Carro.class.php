<?php
/**
* Classe do carro
*/
namespace Estadao;

class Carro extends Connection{

    protected $table_structure = [
        'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
        'brand' => 'TEXT',
        'model' => 'TEXT',
        'color' => 'TEXT',
        'plate' => 'TEXT',
        'year' => 'INTEGER',
        'updated' => 'TEXT',
        'created' => 'TEXT'
    ];

    protected $table = "carros";

    /**
     * @return integer
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id){
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getColor(){
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color){
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getPlate(){
        return $this->plate;
    }

    /**
     * @param string $plate
     */
    public function setPlate($plate){
        $this->plate = $plate;
    }

    /**
     * @return string
     */
    public function getYear(){
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($yaer){
        $this->year = $yaer;
    }

    /**
     * @return string
     */
    public function getBrand(){
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand){
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getModel(){
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model){
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getUpdated(){
        return $this->updated;
    }

    /**
     * @param string $updated
     */
    public function setUpdated($updated){
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getCreated(){
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated($created){
        $this->created = $created;
    }
}
?>