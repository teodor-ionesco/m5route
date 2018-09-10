<?php

namespace M5\Registry;

class Records
{
	protected static $M5_ROUTES = [
		/*0 => [
			'METHOD' => null,
			'URI' => null,
			'TARGET' => null,
			'VARS' => [
				"PATH" => [

				],

				"QUERY" => [
					"REQUIRED" => [],
					"OPTIONAL" => [],
				],
			],
		]*/
	];

	protected static $M5_REQUEST = [
		'IP' => null,
		'SCHEME' => null,
		'METHOD' => null,
		'URI' => null,
	];
	
	protected static $M5_TARGET = null;
	protected static $M5_ERRORS = [];

	public static function routes() : array { return self::$M5_ROUTES; }
	public static function request() : array { return self::$M5_REQUEST; }
	public static function target() : string { return (string)(self::$M5_TARGET); }
	public static function errors() : array { return (array)(self::$M5_ERRORS); }
}
