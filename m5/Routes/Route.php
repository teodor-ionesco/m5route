<?php

namespace M5\Routes;

use M5\Registry\Routes as RegistryRoutes;

class Route
{
	public static function get($uri, $view) : void
	{
		$splet = self::split_vars($uri);

		//print_r($splet);

		if(!RegistryRoutes::create(["GET", $splet['URI'], $view, $splet['VARS']]))
			echo "<b>M5 Warning:</b> web route <code>GET:$uri</code> is duplicated.";
	}

	public static function post($uri, $view) : void
	{
		if(!RegistryRoutes::create(["POST", $uri, $view, self::parse_uri($uri)]))
			echo "<b>M5 Warning:</b> web route <code>POST:$uri</code> is duplicated.";
	}

	public static function patch($uri, $view) : void
	{
		if(!RegistryRoutes::create(["PATCH", $uri, $view, self::parse_uri($uri)]))
			echo "<b>M5 Warning:</b> web route <code>PATCH:$uri</code> is duplicated.";
	}

	public static function delete($uri, $view) : void
	{
		if(!RegistryRoutes::create(["DELETE", $uri, $view, self::parse_uri($uri)]))
			echo "<b>M5 Warning:</b> web route <code>DELETE:$uri</code> is duplicated.";
	}

	private static function split_vars($uri)
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

	private static function is_valid_uri($uri) : bool
	{
	//	print_r($uri);
		if(strpos($uri, '{') !== false)
			return false;

		if(strpos($uri, '}') !== false)
			return false;

		return true;
	}

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