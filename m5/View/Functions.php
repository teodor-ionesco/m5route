<?php

use M5\Registry\Records as RegistryRecords;

function _P($string) 
{
	if(isset(RegistryRecords::request()['VARS']['PATH'][$string]))
		return RegistryRecords::request()['VARS']['PATH'][$string];
	else
		return "Variable '$string' is not set.";
}

function _Q($string) 
{
	if(isset(RegistryRecords::request()['VARS']['QUERY'][$string]))
		return RegistryRecords::request()['VARS']['QUERY'][$string];
	else
		return "Variable '$string' is not set.";
}