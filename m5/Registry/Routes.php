<?php

namespace M5\Registry;

use Exception;

class Routes extends Records
{
	/*
		Register a new route within system.
		Accepts the following parameter: ["method", "path", "view", "vars"]
	*/
	public static function create($route) : bool
	{
		if(empty($route) || !is_array($route))
			throw new Exception("\$routes must be of type 'array'");

		if(self::exists($route))
			return false;

		$key = count(parent::$M5_ROUTES) + 1;

		parent::$M5_ROUTES[$key]["METHOD"] = $route[0];
		parent::$M5_ROUTES[$key]["URI"] = $route[1];
		parent::$M5_ROUTES[$key]["TARGET"] = $route[2];
		parent::$M5_ROUTES[$key]["VARS"] = $route[3];

		return true;
	}

	public static function modify()
	{

	}

	public static function delete()
	{

	}

	private static function exists(&$route) : bool
	{
		if(empty(parent::$M5_ROUTES))
			return false;

		foreach(parent::$M5_ROUTES as $key => $array)
		{
			if($route[0] === $array['METHOD'] && $route[1] === $array['URI'] && $route[3] === $array['VARS'])
				return true;
		}

		return false;
	}
}