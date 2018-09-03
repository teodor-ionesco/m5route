<?php

namespace M5\Registry;

class Records
{
	protected static $M5_ROUTES = [];
	protected static $M5_REQUEST = [];
	protected static $M5_TARGET = null;

	public static function routes() : array
	{
		return self::$M5_ROUTES;
	}

	public static function request() : array
	{
		return self::$M5_REQUEST;
	}

	public static function target() : string
	{
		return (string)(self::$M5_TARGET);
	}
}
