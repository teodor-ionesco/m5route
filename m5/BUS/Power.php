<?php

namespace M5\BUS;

use M5\BUS\Request as BUSRequest;
use M5\BUS\Target as BUSTarget;
use M5\BUS\Vars as BUSVars;

use M5\Registry\Records as RegistryRecords;
use M5\Registry\Target as RegistryTarget;
use M5\Registry\Request as RegistryRequest;
use M5\Errors\Make as ErrorsMake;
use M5\Errors\Push as ErrorsPush;
use M5\View\Power as pView;

class Power 
{
	private static $ROUTE = [];

	public static function on()
	{
		//print_r(RegistryRecords::routes());
		//print_r(RegistryRecords::request());

		self::$ROUTE = BUSRequest::check();

		if(empty(self::$ROUTE))
			ErrorsMake::new([__LINE__, "ERROR", "Method and/or path not allowed."], true);

		RegistryRequest::create(["VARS", BUSVars::serialize(self::$ROUTE['VARS'])]);

		//if(!BUSTarget::check())
		//	ErrorsMake::new([__LINE__, "ERROR", "Route target mismatch."], true);

		if(self::$ROUTE['TARGET']['VIEW'] !== null)
			pView::on(self::$ROUTE['TARGET']['VIEW']);

		//if(!RegistryTarget::create(self::$ROUTE['TARGET']))
		//	ErrorsMake::new([__LINE__, "WARNING", "Duplicated route target."]);

		//pView::on(self::$ROUTE['VARS']);
	}
}