<?php
/**
 * Created by PhpStorm.
 * User: jozie
 * Date: 05/01/2018
 * Time: 14:34
 */

namespace Estadao;


class Connection
{
    const PATH_SAVE = __DIR__."../database";

    private $table;

    private function getPathSave(){
        return self::PATH_SAVE;
    }

    private function getData(){
        $content = file_exists($this->getPathSave()) ? json_encode(file_get_contents($this->getPathSave()),true) :
    }

    public function save(){

    }
}