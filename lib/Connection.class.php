<?php
/**
 * Created by PhpStorm.
 * User: Joziel
 * Date: 05/01/2018
 * Time: 14:34
 */

namespace Estadao;

use PDO;
use Exception;

define('DB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/database/estadao.db');

abstract class Connection{
    /**
     * @var string
     */
    private $primary_key = 'id';

    /**
     * @var array
     */
    protected $table_structure = array();

    /**
     * @var PDO
     */
    public $pdo = null;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $data
     */
    public function setData(Array $data){
        $this->data = $data;
    }

    /**
     * @return PDO
     */
    private function getPdo()
    {
        if(empty($this->table)){
            throw new Exception('Table structure not found');
        }

        if(empty($this->table_structure)){
            throw new Exception('Table structure empty');
        }

        if(is_null($this->pdo)){
            $this->pdo = new PDO("sqlite:".DB_PATH);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        $data_concat = [];
        foreach($this->table_structure as $name => $value){
            $data_concat[] = $name." ".$value;
        }
        $query = "CREATE TABLE IF NOT EXISTS ".$this->table." ( ".join(", ",$data_concat).");";

        $this->pdo->exec($query);
        return $this->pdo;
    }

    public function __get($name){
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __set($name, $value){
        $this->data[$name] = $value;
    }

    public function __isset($name){
        return isset($this->data[$name]);
    }

    public function select($params){
        $query = "SELECT * FROM ".$this->table;

        $where = [];
        $order = [];
        $join = [];
        $take = [];
        foreach($params as $param => $value){
            switch (strtoupper($param)){
                case "WHERE":
                    if(empty($where)){
                        $where[] = "WHERE ".$value;
                    }else{
                        $where[] = "AND ".$value;
                    }
                break;
                case "OR_WHERE":
                    if(empty($where)){
                        $where[] = "WHERE ".$value;
                    }else{
                        $where[] = "OR ".$value;
                    }
                break;
                case "ORDER":
                    $order[] = "ORDER BY ".$value;
                break;
                case "JOIN":
                    $join[] = "JOIN ".$value['table']." ".$value['ON'];
                break;
                case "TAKE":
                    $take[] = "TAKE ".$value;
                break;
            }
        }
        $query .=" ".join(" ",$join)." ".join(" ",$where)." ".join(" ",$order)." ".join(" ",$take);

        $result = $this->getPdo()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $class_name = get_class($this);
        if(empty($result)){
            return [];
        }else{
            foreach ($result as &$item){
                $new_class = new $class_name();
                $new_class->setData($item);
                $item = $new_class;
            }
        }
        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function insert(){
        $data_prepare = [
            'name' => [],
            'name_override' => [],
            'values' => []
        ];
        foreach($this->data as $name => $value){
            $data_prepare['name'][] = $name;
            $data_prepare['name_override'][] = ":".$name;
            $data_prepare['values'][":".$name] = $value;
        }
        $query = "INSERT INTO ".$this->table." (".join(", ",$data_prepare['name']).") VALUES (".join(", ",$data_prepare['name_override']).")";
        $prepare = $this->getPdo()->prepare($query);
        $return = $prepare->execute($data_prepare['values']);

        if($return){
            $this->data[$this->primary_key] = $this->getPdo()->lastInsertId();
            return ['status' => 'success', $this->primary_key => $this->data[$this->primary_key]];
        }else{
            return ['status' => 'error', 'error' => 'Not inserted successfully'];
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    private function update(){
        if(empty($this->data[$this->primary_key])){
            throw new Exception('Primary key not found!');
        }

        $data_prepare = [
            'update' => [],
            'values' => []
        ];
        foreach($this->data as $name => $value){
            if($name == $this->primary_key){
                continue;
            }
            $data_prepare['update'][] = "{$name} = :".$name;
            $data_prepare['values'][":".$name] = $value;
        }

        $query = "UPDATE ".$this->table." SET ".join(", ",$data_prepare['update'])." WHERE ".$this->primary_key." = '".$this->data[$this->primary_key]."'";
        $prepare = $this->getPdo()->prepare($query);
        $return = $prepare->execute($data_prepare['values']);

        if($return){
            return ['status' => 'success'];
        }else{
            return ['status' => 'error', 'error' => 'Not updated successfully'];
        }
    }

    public function delete(){
        if(empty($this->data[$this->primary_key])){
            throw new Exception('Primary key not found!');
        }
        $return = $this->getPdo()->exec("DELETE FROM ".$this->table." WHERE ".$this->primary_key." = ".$this->data[$this->primary_key]);

        if($return){
            return ['status' => 'success'];
        }else{
            return ['status' => 'error', 'error' => 'Not deleted successfully'];
        }
    }

    public function save(){
        if(isset($this->data[$this->primary_key])){
            return $this->update();
        }else{
            return $this->insert();
        }
    }

    public function toArray(){
        return $this->data;
    }

    public function toJson(){
        return json_encode($this->toArray());
    }

    public function __toString(){
        return $this->toJson();
    }
}