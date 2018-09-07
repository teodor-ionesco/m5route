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
		'VARS' => [
			'PATH' => [],
			'QUERY' => [],
		],
	];

	public static function on()
	{
		print_r(RegistryRecords::routes());
		print_r(RegistryRecords::request());

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

			if(!self::check_route_uri($array["URI"]))
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

	private static function check_route_uri($route_uri)
	{
		$request_uri = explode('?', RegistryRecords::request()['URI']);
		$route_uri = explode('%', $route_uri);

		if(count($request_uri) > 2)
			die('Corrupted request.');

		if(count($request_uri) < 2)
		{
			if(count($route_uri) === 2) {
				if(self::are_there_any_required_vars($route_uri[1]))
					die('Only part of request was specified.');
			}

			self::parse_path_uri($route_uri[0], $request_uri[0]);

			return;
		}
		else
		{
			//self::parse_path_uri($route_uri[0], $request_uri[0]);
			//self::parse_query_uri($route_uri[1], $request_uri[1]);
		}
	}

	public static function parse_path_uri($route_uri, $request_uri)
	{
		$route_uri = explode('/', $route_uri);
		$request_uri = explode('/', $request_uri);
		$cursor = 0;

		foreach($route_uri as $key => $value)
		{
			if($cursor > $key)
				die('Request not allowed.1');

			//if($key === count($route_uri)-1 && self::are_there_any_cells_left($request_uri, $cursor))
			//	die('Request not allowed.');

			if(empty($request_uri[$cursor]) && $key !== 0) {
				if(self::are_there_any_required_vars_left($route_uri, $key))
					die('Request parameter does not match variable.1');

				if(self::are_there_any_optional_vars_left($route_uri, $key))
				{
					continue;
				}

				if(self::are_there_any_cells_left($route_uri, $key))
					die('Request parameter does not match variable.2');

				break;
			}

			if(strpos($value, '#?') !== false)
				continue;

			if(strpos($value, '#') !== false) {
				if(empty($request_uri[$cursor]))
					die('Request parameter does not match variable.');

				$cursor++;
				continue;
			}

			if($request_uri[$cursor] === $value) {
				$cursor++;
				continue;
			}
print_r("cursor: $cursor; key: $key; value: $value\r\n");			
			die('Request not allowed.2');
		}
	}

	private static function parse_query_uri($route_uri, $request_uri)
	{
		$route_uri = explode('/', $route_uri);
		$request_uri = explode('&', $request_uri);
		$cursor = 0;

		$request_uri = self::array_increment($request_uri);
		$request_uri[0] = '';

		foreach($request_uri as $key => $value)
		{
			if(preg_match('/([0-9]*[a-zA-Z_]+[0-9]*)=?/', $value, $matches) === 1)
				$request_uri[$key] = $matches[1];
		}

		foreach($route_uri as $key => $value)
		{
print_r($value);
			if($cursor > $key)
				die('Request query not allowed.');

			if(strpos($value, '?#') !== false)
				continue;

			if(strpos($value, '#') !== false)
			{
				$cursor++;
				continue;
			}

			if($request_uri[$cursor] === $value)
			{
				$cursor++;
				continue;
			}

			die('Request query not allowed.');
		}
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

	private static function are_there_any_required_vars($uri = null) : bool
	{
		switch(true)
		{
			case is_array($uri):
			{
				foreach($array as $value)
				{
					if(preg_match('/#[0-9]+#/', $value) === 1)
						return true;
				}

				return false;
			}

			case is_string($uri):
			{
				if(preg_match('/#[0-9]+#/', $uri) === 1)
					return true;
				else
					return false;
			}
		}
	}

	private static function are_there_any_required_vars_left($array, $offset) : bool
	{
		for($offset; $offset < count($array); $offset++)
		{
			if(preg_match('/#[0-9]+#/', $array[$offset]) === 1)
				return true;
		}

		return false;
	}

	private static function are_there_any_cells_left($array, $offset) : bool
	{
		if(count($array) > $offset)
			return true;

		return false;
	}

	private static function are_there_any_optional_vars_left($array, $offset) : bool
	{
		for($offset; $offset < count($array); $offset++)
		{
			if(preg_match('/#?[0-9]+#/', $array[$offset]) === 1)
				return true;
		}

		return false;
	}

	private static function array_increment($array, $int = 1) : array
	{
		$tmp = [];

		for($i = self::array_starts_at($array); $i < count($array); $i++)
		{
			$tmp[$i+1] = $array[$i];
		}

		return $tmp;
	}

	private static function array_starts_at($array) : int 
	{
		for($i = -count($array); $i < count($array); $i++)
		{
			if(isset($array[$i]))
				return $i;
		}
	}
}