<?php
session_start();

require 'lib/autoload.php';

Root::get('/api/carros', 'Controller::getCarrosList'); // [GET] Buscar lista de carros
Root::post('/api/carros', 'Controller::newCarro');  // [POST] Adicionar novo carro
Root::get('/api/carros/{id}', 'Controller::getCarro'); // [GET] Bucar um carro
Root::put('/api/carros/{id}', 'Controller::updateCarro');  // [PUT] Atualizar Carro
Root::delete('/api/carros/{id}', 'Controller::deleteCarro');  // [DELETE] Deleter Carro
Root::get('/api/marcas', 'Controller::getMarcaList');  // [GET] Buscar marcas
Root::get('/api/modelos/{id}', 'Controller::getModelosByMarca'); // [GET] Buscar Modelos

Root::run();

echo 'Not found';

