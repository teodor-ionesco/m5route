<?php

namespace M5\Routes;

use M5\Registry\Routes as RegistryRoutes;

class Route
{
	public static function get($url, $view) : void
	{
		if(!RegistryRoutes::create(["get", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>GET:$url</code> is duplicated.";
	}

	public static function post($url, $view) : void
	{
		if(!RegistryRoutes::create(["post", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>POST:$url</code> is duplicated.";
	}

	public static function patch($url, $view) : void
	{
		if(!RegistryRoutes::create(["patch", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>PATCH:$url</code> is duplicated.";
	}

	public static function delete($url, $view) : void
	{
		if(!RegistryRoutes::create(["delete", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>DELETE:$url</code> is duplicated.";
	}
}