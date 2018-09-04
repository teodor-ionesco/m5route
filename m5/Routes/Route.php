<?php

namespace M5\Routes;

use M5\Registry\Routes as RegistryRoutes;

class Route
{
	public static function get($uri, $view) : void
	{
		if(!RegistryRoutes::create(["GET", self::parse_uri($uri)[0], $view, self::parse_uri($uri)[1]]))
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

	private static function parse_uri($uri) : array
	{
		preg_match_all("/\/{(.*?)}/", $uri, $m);

		if(!empty($m[1]))
		{
			foreach($m[1] as $key => $value)
			{
				if(preg_match('/^[a-zA-Z_]+$/', $value) === 0)
					die('Corrupted web route variable.');

				unset($m[0][$key]);

				if(array_search('/{'.$value.'}', $m[0], true))
					die('Duplicated web route variable.');

				$uri = str_replace('{'.$value.'}', "###", $uri);
				$uri = rtrim($uri, '/');
			}
		}

		if(strpos($uri, '{') !== false || strpos($uri, '}') !== false)
			die('Corrupted web route variable.');

		return [$uri, $m[1]];
	}
}