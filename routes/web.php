<?php

use M5\Routes\Route as Route;

Route::get('/test/{aaa+}?{id-}', 'index.php');
//Route::get('/alex', 'index.php');