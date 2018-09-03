<?php

namespace M5\Routes;

use M5\Registry\Routes as RegistryRoutes;

class Route
{
	public static function get($url, $view) : void
	{
		if(!RegistryRoutes::create(["GET", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>GET:$url</code> is duplicated.";
	}

	public static function post($url, $view) : void
	{
		if(!RegistryRoutes::create(["POST", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>POST:$url</code> is duplicated.";
	}

	public static function patch($url, $view) : void
	{
		if(!RegistryRoutes::create(["PATCH", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>PATCH:$url</code> is duplicated.";
	}

	public static function delete($url, $view) : void
	{
		if(!RegistryRoutes::create(["DELETE", $url, $view]))
			echo "<b>M5 Warning:</b> web route <code>DELETE:$url</code> is duplicated.";
	}
}