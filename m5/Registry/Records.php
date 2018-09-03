<?php

namespace M5\Registry;

class Records
{
	protected static $M5_ROUTES = [];
	protected static $M5_REQUEST = [];

	public static function routes() : array
	{
		return self::$M5_ROUTES;
	}

	public static function request() : array
	{
		return self::$M5_REQUEST;
	}
}
