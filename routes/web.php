<?php

use M5\Routes\Route as Route;

Route::get('/{meme}/aa%{meme2}{}', 'index.php');
//Route::get('/{meme}/{var2}/{?var3}/meme/%{?var4}{', 'index.php');
//Route::get('/alex', 'index.php');