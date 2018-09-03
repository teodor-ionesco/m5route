<?php

namespace M5\BUS;

use M5\View\Power as pView;
use M5\Registry\Records as RegistryRecords;
use M5\Registry\Target as RegistryTarget;
use M5\Errors\Make as ErrorsMake;
use M5\Errors\Push as ErrorsPush;

class Power 
{
	private static $M5_ROUTE = null;
	private static $M5_METHOD = null;
	private static $M5_TARGET = null;

	public static function on()
	{
		//print_r(RegistryRecords::routes());
		//print_r(RegistryRecords::request());

		if(!self::check_route())
			ErrorsMake::new([__LINE__, "ERROR", "Method and/or path not allowed."], true);

		if(!self::check_target())
			ErrorsMake::new([__LINE__, "ERROR", "Route target mismatch."], true);

		if(!RegistryTarget::create(self::$M5_TARGET))
			ErrorsMake::new([__LINE__, "WARNING", "Duplicated route target."]);

		pView::on();
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
			self::$M5_TARGET = $array['TARGET'];

			break;
		}

		return (empty(self::$M5_ROUTE) || empty(self::$M5_METHOD) || empty(self::$M5_METHOD)) ? false : true;
	}

	private static function check_target() : bool
	{
		if(!file_exists(M5_CONFIG_MAIN['ROOT_PATH'] . '/views/' . self::$M5_TARGET))
			return false;
		else
			return true;
	}
}