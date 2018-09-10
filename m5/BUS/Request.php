<?php

namespace M5\BUS;

use M5\Registry\Records as RegistryRecords;

class Request
{
	private static $RouteURI = [];

	/*
	*
	*	Main check switch. Loader for all child methods.
	*
	*/
	public static function check() : array
	{
		foreach(RegistryRecords::routes() as $key => $array)
		{
			self::$RouteURI = $array;

			if($array["METHOD"] !== RegistryRecords::request()['METHOD'])
				continue;

			if(self::check_uri($array['URI']))
				return $array;
			else
				continue;
		}

		return [];
	}

	private static function check_uri($route_uri) : bool
	{
		$route = explode('%', $route_uri, 2);
		$request = explode('?', RegistryRecords::request()['URI'], 2);

		/*
		*
		*	If route has no '%' check only path URL.
		*
		*/
		if(count($route) === 1)
		{
			if(!self::check_path_uri($request[0], $route[0]))
				return false;
		}

		/*
		*
		*	If route has more '%' check both path and query URI.
		*
		*/
		else
		{
			/*
			*
			*	If no '?' is supplied in request check if there are any required variables in route URI.
			*
			*/
			if(empty($request[1]))
			{
				if(self::exist_required_vars())
					return false;
				else
					return self::check_path_uri($request[0], $route[0]);
			}

			if(!self::check_path_uri($request[0], $route[0]))
				return false;

			if(!self::check_query_uri($request[1], $route[1]))
				return false;
		}

		return true;
	}

	/*
	*
	*	Check path URI
	*
	*/
	private static function check_path_uri(&$request_uri, &$route_uri) : bool
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

	/*
	*
	*	Check query URI
	*
	*/
	private static function check_query_uri(&$request_uri, &$route_uri) : bool
	{
		$request = explode('&', $request_uri);
		$route = self::$RouteURI['VARS']['QUERY'];

		foreach($request as $key => $value)
		{
			$request[$key] = preg_replace('/=.*$/', '', $value);
		}

		$_count = 0;
		foreach($request as $key => $value)
		{
			foreach($route['REQUIRED'] as $v)
			{
				if($value === $v)
				{
					unset($request[$key]);
					$_count++;
				}
			}
		}

		if($_count !== count($route['REQUIRED']))
			return false;

		foreach($request as $key => $value)
		{
			if(array_search($value, $route['OPTIONAL']) === false)
				return false;
		}

		return true;
	}

	/*
	*
	*	Checks whether required query vars are present or not.
	*
	*/
	private static function exist_required_vars() : bool
	{
		if(count(self::$RouteURI['VARS']['QUERY']['REQUIRED']) > 0)
			return true;
		else
			return false;
	}
}