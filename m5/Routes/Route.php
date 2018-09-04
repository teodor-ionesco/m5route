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

	private static function parse_uri($uri)
	{
		print($uri . '<br>');

		if(strpos($uri, '?') !== false)
		{
			$tmp = explode('?', $uri);

			$exploded[0]
		}

		foreach($exploded as $key => $value)
		{
			if(preg_match('/{[0-9]*[a-zA-Z_]+[0-9]*\++}/', $value))
			{
				echo "/XXX+<br>";
				continue;
			}

			if(preg_match('/{[0-9]*[a-zA-Z_]+[0-9]*\-?+}/', $value))
			{
				echo "/XXX-<br>";
				continue;
			}
			if(preg_match('/{\?+[0-9]*[a-zA-Z_]+[0-9]*\++}/', $value))
			{
				echo "?XXX+<br>";
				continue;
			}
			if(preg_match('/{\?+[0-9]*[a-zA-Z_]+[0-9]*\-+}/', $value))
			{
				echo "?XXX-<br>";
				continue;
			}
			echo "NONE<br>";
		}
	}
}