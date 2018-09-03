<?php

namespace M5\BUS;

use M5\Registry\Records as RegistryRecords;
use M5\Registry\Target as RegistryTarget;
use M5\View\Power as pView;

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
			die('<b>M5 Error:</b> Method and/or path not allowed.');

		if(!self::check_target())
			die('<b>M5 Error:</b> Route target mismatch.');

		if(!RegistryTarget::create(self::$M5_TARGET))
			echo "<b>M5 Warning:</b> Duplicated route target.";

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