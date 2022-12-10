<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// AUTH
$router->POST('/auth/register', ['uses' => 'MahasiswaController@Register']);
$router->POST('/auth/login', ['uses' => 'MahasiswaController@Login']);

// Mahasiswa
$router->get('/mahasiswa', ['uses' => 'MahasiswaController@GetUsers']);
$router->get('/mahasiswa/profile', ['uses' => 'MahasiswaController@GetUsers']);
$router->get('/mahasiswa/{nim}', ['uses' => 'MahasiswaController@GetMahasiswaByNim']);
$router->post('/mahasiswa/{nim}/matakuliah/{mkId}', ['uses' => 'MahasiswaController@AddMatkulMahasiswa']);
$router->put('/mahasiswa/{nim}/matakuliah/{mkId}', ['uses' => 'MahasiswaController@DeleteMatkulMahasiswa']);
// $router->get('/mahasiswa/profile', ['uses' => 'MahasiswaController@GetUsersByToken']);

// Mata Kuliah
$router->POST('/matakuliah/add', ['uses' => 'MahasiswaController@AddMatkul']);
$router->get('/matakuliah', ['uses' => 'MahasiswaController@GetMatkul']);