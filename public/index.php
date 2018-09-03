<?php

// Invoke autoload
require_once('../autoload.php');

// Boot up the framework
\M5\Boot\Power::on();

print_r(\M5\Registry\Records::routes());

print_r($_SERVER);