<?php

namespace M5\Registry;

class Records
{
	/*
	*
	*	Records of web routes.
	*
	*/
	protected static $M5_ROUTES = [
		/*
		*
		*	Array schema
		*
		*/

		/*
		0 => [
			'METHOD' => null,
			'URI' => null,
			'TARGET' => [
				'VIEW' => null,
				'CONTROLLER' => null,
			],
			'VARS' => [
				"PATH" => [

				],

				"QUERY" => [
					"REQUIRED" => [],
					"OPTIONAL" => [],
				],
			],
		]
		*/
	];

	/*
	*
	*	Records of controllers
	*
	*/

	protected static $M5_CONTROLLERS = [];

	/*
	*
	*	Records of client request.
	*
	*/
	protected static $M5_REQUEST = [
		'IP' => null,
		'SCHEME' => null,
		'METHOD' => null,
		'URI' => null,
		'VARS' => [
			'PATH' => [],
			'QUERY' => [],
		],
	];

	/*
	*
	*	Not in use yet.
	*
	*/
	protected static $M5_TARGET = null;

	/*
	*
	*	Records of framework errors.
	*
	*/
	protected static $M5_ERRORS = [];

	public static function routes() : array { return self::$M5_ROUTES; }
	public static function controllers() : array { return self::$M5_CONTROLLERS; }
	public static function request() : array { return self::$M5_REQUEST; }
	public static function target() : string { return (string)(self::$M5_TARGET); }
	public static function errors() : array { return (array)(self::$M5_ERRORS); }
}
