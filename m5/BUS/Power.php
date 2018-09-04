<?php

namespace M5\BUS;

use M5\View\Power as pView;
use M5\Registry\Records as RegistryRecords;
use M5\Registry\Target as RegistryTarget;
use M5\Errors\Make as ErrorsMake;
use M5\Errors\Push as ErrorsPush;

class Power 
{
	private static $ROUTE = [
		'URI' => null,
		'METHOD' => null,
		'TARGET' => null,
		'VARS' => [],
	];

	public static function on()
	{
		//print_r(RegistryRecords::routes());
		//print_r(RegistryRecords::request());

		if(!self::check_route())
			ErrorsMake::new([__LINE__, "ERROR", "Method and/or path not allowed."], true);

		if(!self::check_target())
			ErrorsMake::new([__LINE__, "ERROR", "Route target mismatch."], true);

		if(!RegistryTarget::create(self::$ROUTE['TARGET']))
			ErrorsMake::new([__LINE__, "WARNING", "Duplicated route target."]);

		pView::on(self::$ROUTE['VARS']);
	}

	private static function check_route() : bool
	{
		foreach(RegistryRecords::routes() as $key => $array)
		{
			if($array["METHOD"] !== RegistryRecords::request()['METHOD'])
				continue;

			if(!self::check_uri($array["URI"]))
				continue;

			self::$ROUTE['URI'] = $array['URI'];
			self::$ROUTE['METHOD'] = $array['METHOD'];
			self::$ROUTE['TARGET'] = $array['TARGET'];
			self::assign_vars($array["VARS"]);

			break;
		}

		return (empty(self::$ROUTE['URI']) 	 || 
				empty(self::$ROUTE['METHOD']) || 
				empty(self::$ROUTE['TARGET'])) ? false : true;
	}

	private static function check_uri($uri) : bool
	{
		$uri = explode('/', $uri);	 // Route URI (###)
		$req = explode('/', RegistryRecords::request()['URI']); // Request URI (xyz)

		if(count($uri) === count($req))
		{
			foreach($uri as $key => $value)
			{
				if($value === "###")
					continue;

				if($value !== $req[$key])
					return false;
			}
		}
		else
			return false;


		return true;
	}

	private static function assign_vars($vars) : void
	{
		$uri = explode('/', self::$ROUTE['URI']); // Route URI (###)
		$req = explode('/', RegistryRecords::request()['URI']); // Request URI (xyz)
		$c = 0;

		foreach ($uri as $key => $value)
		{
			if($value !== "###")
				continue;

			self::$ROUTE['VARS'][$vars[$c]] = $req[$key];
			$c++;
		}
	}

	private static function check_target() : bool
	{
		if(!file_exists(M5_CONFIG_MAIN['ROOT_PATH'] . '/views/' . self::$ROUTE['TARGET']))
			return false;
		else
			return true;
	}
}