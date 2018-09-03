<?php

namespace M5\Registry;

use Exception;

class Routes extends Records
{
	/*
		Register a new route within system.
		Accepts the following parameters:

		1. ["method", "path", "view"]
		2. [
				0 => ["method", "path", "view"], 
				1 => ["method", "path", "view"], 
				2 => ["method", "path", "view"], 
				...
			]
	*/
	public static function create($routes) : bool
	{
		if(empty($routes) || !is_array($routes))
			throw new Exception("\$routes must be of type 'array'");

		if(self::exists($routes))
			return false;

		$k = count(parent::$M5_ROUTES) + 1;

		foreach($routes as $key => $var)
		{
			if(empty($key) || empty($var)) // Corrupted route
				continue;

			if(is_array($var))
			{
				parent::$M5_ROUTES[$key]["method"] = $var[0];
				parent::$M5_ROUTES[$key]["path"] = $var[1];
				parent::$M5_ROUTES[$key]["view"] = $var[2];
			}
			else
			{
				parent::$M5_ROUTES[$k]["method"] = $routes[0];
				parent::$M5_ROUTES[$k]["path"] = $routes[1];
				parent::$M5_ROUTES[$k]["view"] = $routes[2];

				break;
			}
		}

		return true;
	}

	public static function modify()
	{

	}

	public static function delete()
	{

	}

	private static function exists($routes) : bool
	{
		if(empty(parent::$M5_ROUTES))
			return false;

		foreach($routes as $key => $var)
		{
			if(empty($key) || empty($var)) // Corrupted route
				continue;

			if(is_array($var))
			{
				foreach(parent::$M5_ROUTES as $a)
				{
					if($var[0] === $a['method'] && $var[1] === $a['path'])
						return true;
				}
			}
			else
			{
				foreach(parent::$M5_ROUTES as $a)
				{
					if($routes[0] === $a['method'] && $routes[1] === $a['path'])
						return true;
				}

				break;
			}
		}

		return false;
	}
}