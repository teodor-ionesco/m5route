<?php

namespace M5\BUS;

use M5\Registry\Records as RegistryRecords;

class Request
{
	private static $REQUEST;

	public static function check() 
	{
		foreach(RegistryRecords::routes() as $key => $array)
		{
			if($array["METHOD"] !== RegistryRecords::request()['METHOD'])
				continue;

			if(!self::check_uri($array['URI']))
				continue;
		}
	}

	private static function check_uri($route_uri)
	{
		$route = explode('%', $route_uri);
		$request = explode('?', RegistryRecords::request()['URI'], 2);

		if(count($route) !== count($request))
			return false;

		if(count($route) === 1)
		{
			var_dump(self::check_path_uri($request[0], $route[0]));

			if(!self::check_path_uri($request[0], $route[0]))
				return false;
		}
		else
		{
			var_dump(self::check_path_uri($request[0], $route[0]));
			print('<br><br>');
			var_dump(self::check_query_uri($request[1], $route[1]));


		}

		return true;
	}

	private static function check_path_uri(&$request_uri, &$route_uri) 
	{
		$request = explode('/', $request_uri);
		$route = explode('/', $route_uri);

		if(count($request) !== count($route))
			return false;

		foreach ($route as $key => $value)
		{
			if(preg_match('/#[0-9]+#/', $value) === 1)
				continue;

			if($request[$key] === $value)
				continue;

			return false;
		}

		return true;
	}

	private static function check_query_uri(&$request_uri, &$route_uri)
	{
		$request = explode('&', $request_uri);
		$route = explode('/', $route_uri);

		foreach($route as $key => $value)
		{
			if(strpos($value, '?') !== false)
			{
				unset($route[$key]);
				continue;
			}
		}

		// to be continued..
	}
}