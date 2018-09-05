<?php

namespace M5\Routes;

use M5\Registry\Routes as RegistryRoutes;

class Route
{
	public static function get($uri, $view) : void
	{
		self::split_vars($uri);

		//if(!RegistryRoutes::create(["GET", self::parse_uri($uri)[0], $view, self::parse_uri($uri)[1]]))
		//	echo "<b>M5 Warning:</b> web route <code>GET:$uri</code> is duplicated.";
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
			die("Too many percentage marks.");

		if(count($sections) < 2)
		{
			$ret = self::parse_path_vars($sections[0]);

			if(self::are_vars_duplicated($ret["VARS"]))
				die('Duplicated route variables.');

			return $ret;
		}

		$vPath = self::parse_path_vars($sections[0]);
		$vQuery = self::parse_query_vars($sections[1]);
		$tmp = self::safely_bind_arrays([
			$vPath['VARS'], 
			$vQuery['VARS']
		]);

		if(self::are_vars_duplicated($tmp))
			die('Duplicated route variables.');

		$ret = [
			'URI' => $vPath['URI'] . '%' . $vQuery['URI'],
			'VARS' => [
				'PATH' => $vPath['VARS'],
				'QUERY' => $vQuery['VARS'],
			],
		];

		print_r($ret);

		return $ret;
	}

	private static function parse_path_vars($uri) : array
	{
		$cells = explode('/', $uri);
		$vars = [];

		foreach($cells as $key => $value)
		{
			if(preg_match('/{(\??[0-9]*[a-zA-Z_]+[0-9]*)}/', $value, $match) === 1)
			{
				$vars[$key] = $match[1];
				$cells[$key] = (strpos($match[1], '?') !== false) ? "?#$key#" : "#$key#";
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

		if((int)(preg_match_all('/{(\??[0-9]*[a-zA-Z_]+[0-9]*)}/', $uri, $match) > 0))
		{
			$vars = $match[1];

			foreach($vars as $key => $value)
			{
				$cells[$key] = preg_replace('/\??[0-9]*[a-zA-Z_]+[0-9]*/', 
								(strpos($vars[$key], '?') !== false) ? "/?#$key#" : "/#$key#", 
								$value);
			}
		}

		$uri = implode('', $cells);
		$uri = rtrim($uri, '/');

		return [
			"URI" => $uri,
			"VARS" => $vars,
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
}