<?php

use M5\Routes\Route as Route;

Route::get('/user/', 'view:index.php');
Route::get('/meme', 'controller:Lovely@index');