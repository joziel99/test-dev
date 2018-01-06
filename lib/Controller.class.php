<?php

/**
 * Created by PhpStorm.
 * User: Joziel
 * Date: 05/01/2018
 * Time: 16:36
 */

class Controller{

    public static function getCarrosList(){
        $carro = new \Estadao\Carro();
        $carros = $carro->select(array());

        if($carros){
            foreach($carros as &$carro){
                $carro = $carro->toArray();
            }
        }

        $return = array(
            'status' => 'success',
            'return' => $carros
        );
        return die(json_encode($return));
    }

    public static function getCarro($id){
        $carro = new \Estadao\Carro();

        /** @var \Estadao\Carro $carro */
        $carro = $carro->select(array(
            'WHERE' => "id = ".$id
        ));

        $return = array(
            'status' => 'success',
            'return' => $carro->toArray()
        );

        return die(json_encode($return));
    }

    public static function updateCarro($id){
        $carro = new \Estadao\Carro();
        /** @var \Estadao\Carro $carro */
        $carro = $carro->select(array(
            'WHERE' => "id = ".$id
        ))[0];

        $data = $_REQUEST;
        $required = array(
            'brand' => 'Marca',
            'model' => 'Modelo',
            'year' => 'Ano',
            'color' => 'Cor',
            'plate' => 'Placa',
        );

        $return = ['status' => 'success'];
        foreach($required as $index => $name){
            if(empty($data[$index])){
                $return = array(
                    'status' => 'error',
                    'error' => array(
                        'field' => $index,
                        'message' => 'Atenção o campo "'.$name.'" é obrigatório!'
                    )
                );
                break;
            }
        }

        if($return['status'] == 'success'){
            $carro->setBrand($data['brand']);
            $carro->setModel($data['model']);
            $carro->setYear($data['year']);
            $carro->setColor($data['color']);
            $carro->setPlate($data['plate']);
            $carro->setUpdated(date('Y-m-d H:i:s'));
            $return = $carro->save();
        }


        if($return['status'] == 'success'){
            $return = array(
                'status' => 'success',
                'return' => $carro->toArray()
            );
        }

        return die(json_encode($return));
    }

    public static function newCarro(){

        $data = $_REQUEST;
        $required = array(
            'brand' => 'Marca',
            'model' => 'Modelo',
            'year' => 'Ano',
            'color' => 'Cor',
            'plate' => 'Placa',
        );

        $return = ['status' => 'success'];
        foreach($required as $index => $name){
            if(empty($data[$index])){
                $return = array(
                    'status' => 'error',
                    'error' => array(
                        'field' => $index,
                        'message' => 'Atenção o campo "'.$name.'" é obrigatório!'
                    )
                );
                break;
            }
        }

        if($return['status'] == 'success'){
            $carro = new \Estadao\Carro();
            $carro->setBrand($data['brand']);
            $carro->setModel($data['model']);
            $carro->setYear($data['year']);
            $carro->setColor($data['color']);
            $carro->setPlate($data['plate']);
            $carro->setUpdated(date('Y-m-d H:i:s'));
            $carro->setCreated(date('Y-m-d H:i:s'));
            $return = $carro->save();
        }


        if($return['status'] == 'success'){
            $return = array(
                'status' => 'success',
                'return' => $carro->toArray()
            );
        }

        return die(json_encode($return));
    }

    public static function deleteCarro($id){
        $carro = new \Estadao\Carro();

        /** @var \Estadao\Carro $carro */
        $carro = $carro->select(array(
            'WHERE' => "id = ".$id
        ))[0];

        if($carro){
            $return = $carro->delete();
        }else{
            $return = array(
                'status' => 'error',
                'error' => 'Item not found!'
            );
        }



        if($return['status'] == 'success'){
            $return = array(
                'status' => 'success',
            );
        }
        return die(json_encode($return));
    }

    public static function getMarcaList(){
        $data = json_decode(file_get_contents(__DIR__."/../database/marcas.json"), true);
        return die(json_encode($data));
    }

    public static function getModelosByMarca($id){
        $data = json_decode(file_get_contents(__DIR__."/../database/modelos.json"), true);

        $data_new = [];
        foreach($data as $model){
            if((int)$model['id_marca'] == (int)$id){
                $data_new[] = $model;
            }
        }
        return die(json_encode($data_new));
    }
}