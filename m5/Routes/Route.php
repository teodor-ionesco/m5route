<?php

namespace M5\Routes;

use M5\Registry\Routes as RegistryRoutes;

class Route
{
	/*
	*
	*	HTTP: GET/POST/PATCH/DELETE handlers. 
		They are loading all other methods which are checking the route and serialize it when necessary.
	*
	*/
	public static function get($uri, $target) : void
	{
		$splet = self::split_vars($uri);

		if(!RegistryRoutes::create(["GET", $splet['URI'], self::split_target($target), $splet['VARS']]))
			echo "<b>M5 Warning:</b> web route <code>GET:$uri</code> is duplicated.";
	}

	public static function post($uri, $target) : void
	{
		if(!RegistryRoutes::create(["POST", $uri, $target, self::parse_uri($uri)]))
			echo "<b>M5 Warning:</b> web route <code>POST:$uri</code> is duplicated.";
	}

	public static function patch($uri, $target) : void
	{
		if(!RegistryRoutes::create(["PATCH", $uri, $target, self::parse_uri($uri)]))
			echo "<b>M5 Warning:</b> web route <code>PATCH:$uri</code> is duplicated.";
	}

	public static function delete($uri, $target) : void
	{
		if(!RegistryRoutes::create(["DELETE", $uri, $target, self::parse_uri($uri)]))
			echo "<b>M5 Warning:</b> web route <code>DELETE:$uri</code> is duplicated.";
	}

	/*
	*
	*	Separate target. Currently only view is supported.
	*
	*/
	private static function split_target($target)
	{
		$sections = explode(':', $target);
		$ret = [
			'VIEW' => null,
			'CONTROLLER' => null,
		];

		if(count($sections) < 2 || count($sections) > 2)
			die("Corrupted route target.");

		switch($sections[0])
		{
			case "view" :
			{
				if(!file_exists(M5_CONFIG_MAIN['ROOT_PATH'] . '/views/' . $sections[1]))
					die("File '$sections[1]' does not exist.");
				
				$ret['VIEW'] = $sections[1];

				break;
			}

			case "controller" : {}

			default: die('Invalid route target.');
		}

		return $ret;
	}

	/*
	*
	*	Separate variables from actual URI.
	*
	*/
	private static function split_vars($uri) : array
	{
		$sections = explode('%', $uri);

		if(count($sections) > 2)
			die("Too many percentage marks in route URL.");

		if(count($sections) < 2)
		{
			$ret = self::parse_path_vars($sections[0]);

			if(!self::is_valid_uri($ret['URI']))
				die('Corrupted route variables.');

			if(self::are_vars_duplicated($ret["VARS"]))
				die('Duplicated route variables.');

			$ret = [
				'URI' => $ret['URI'],
				'VARS' => [
					'PATH' => $ret['VARS'],
					'QUERY' => [
						'REQUIRED' => [],
						'OPTIONAL' => [],
					],
				],
			];

			return $ret;
		}

		$vPath = self::parse_path_vars($sections[0]);
		$vQuery = self::parse_query_vars($sections[1]);

		$uri = $vPath['URI'] . '%' . $vQuery['URI'];
		$tmp = self::safely_bind_arrays([$vPath['VARS'], $vQuery['VARS']]);

		if(!self::is_valid_uri($uri))
			die('Corrupted route variables.');

		if(self::are_vars_duplicated($tmp))
			die('Duplicated route variables.');

		$ret = [
			'URI' => $uri,
			'VARS' => [
				'PATH' => $vPath['VARS'],
				'QUERY' => $vQuery['VARS'],
			],
		];

		return $ret;
	}

	/*
	*
	*	Parse path variables.
	*
	*/
	private static function parse_path_vars($uri) : array
	{
		$cells = explode('/', $uri);
		$vars = [];

		foreach($cells as $key => $value)
		{
			if(!self::is_valid_cell($value))
				die('Currupted route variable.');

			if(preg_match('/{([0-9]*[a-zA-Z_]+[0-9]*)}/', $value, $match) === 1)
			{
				$vars[$key] = $match[1];
				$cells[$key] = "#$key#";
			}
		}

		$uri = implode('/', $cells);
		$uri = rtrim($uri, '/');

		return [
			"URI" => $uri,
			"VARS" => $vars,
		];
	}

	/*
	*
	*	Parse query variables.
	*
	*/
	private static function parse_query_vars($uri) : array
	{
		$cells = [];
		$vars = [];
		$required = [];
		$optional = [];

		if(preg_match('/{[0-9]*[a-zA-Z_]+[0-9]*\?/', $uri) === 1)
			die('Corrupted query variables.');

		if((int)(preg_match_all('/{(\??[0-9]*[a-zA-Z_]+[0-9]*)}/', $uri, $match) > 0))
		{
			$vars = $match[1];

			foreach($vars as $key => $value)
			{
				$cells[$key] = preg_replace('/\??[0-9]*[a-zA-Z_]+[0-9]*/', "/#$key#", $value);

				if(strpos($value, '?') !== false)
					$optional[$key] = str_replace('?', '', $value);
				else
					$required[$key] = $value;

			}
		}

		$uri = implode('', $cells);
		$uri = rtrim($uri, '/');

		return [
			"URI" => $uri,
			"VARS" => [
				'REQUIRED' => $required,
				'OPTIONAL' => $optional,
			],
		];		
	}

	/*
	*
	*	Check if vars are duplicated in a certain context.
	*
	*/
	private static function are_vars_duplicated(&$array) : bool
	{
		$array = self::clear_vars($array);

		foreach($array as $key => $value)
		{
			if(count(array_keys($array, $value, true)) > 1)
				return true;
		}

		return false;
	}

	/*
	*
	*	Clean array values from all useless characters
	*
	*/
	private static function clear_vars($array) : array
	{
		foreach($array as $key => $value)
		{
			$array[$key] = str_replace('{', '', $value);
			$array[$key] = str_replace('}', '', $value);
			$array[$key] = str_replace('?', '', $value);
		}

		return $array;
	}

	/*
	*
	*	Concatenate custom arrays
	*
	*/
	private static function safely_bind_arrays($data = []) : array
	{
		$count = 0;
		$array = [];

		foreach($data as $key => $arr)
		{
			foreach($arr as $k => $v)
			{
				$array[$count] = $v;
				$count++;
			}
		}

		return $array;
	}

	/*
	*
	*	Check if an URI is valid (whether it contains forbidden chars or not)
	*
	*/
	private static function is_valid_uri($uri) : bool
	{
	//	print_r($uri);
		if(strpos($uri, '{') !== false)
			return false;

		if(strpos($uri, '}') !== false)
			return false;

		return true;
	}

	/*
	*
	*	Check if a cell is valid.

		A HTTP request = GET:/X/Y/Z?query=whatever
		X, Y, Z are cells
	*
	*/
	private static function is_valid_cell($cell) : bool
	{
		if(substr_count($cell, '{') === 1 && substr_count($cell, '}') === 1)
		{
			return true;
		}
		else if(substr_count($cell, '{') === 0 && substr_count($cell, '}') === 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}