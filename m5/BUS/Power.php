<?php

namespace M5\BUS;

use M5\Registry\Records as RegistryRecords;

class Power 
{
	private static $M5_ROUTE = null;
	private static $M5_METHOD = null;
	private static $M5_TARGET = null;

	public static function on()
	{

		print_r(RegistryRecords::routes());
		print_r(RegistryRecords::request());

		if(!self::check_route())
			die('<b>M5 Error:</b> Method and/or path not allowed.');


	}

	private static function check_route() : bool
	{
		foreach(RegistryRecords::routes() as $key => $array)
		{
			if($array["METHOD"] !== RegistryRecords::request()['METHOD'])
				continue;

			if($array["PATH"] !== RegistryRecords::request()['URI'])
				continue;

			self::$M5_ROUTE = $array['PATH'];
			self::$M5_METHOD = $array['METHOD'];

			break;
		}

		return (empty(self::$M5_ROUTE) || empty(self::$M5_METHOD)) ? false : true;
	}


}