<?php

namespace M5\Request;

use M5\Registry\Request as RegistryRequest;

class Power
{
	public static function on() : void
	{
		$tmp = [
			'IP' => $_SERVER['REMOTE_ADDR'],
			'SCHEME' => $_SERVER['REQUEST_SCHEME'],
			'METHOD' => $_SERVER['REQUEST_METHOD'],
			'URI' => self::RealURI(),
		];

		foreach($tmp as $key => $value)
		{
			if(!RegistryRequest::create([$key, $value]))
				echo "<b>Warning:</b> $key is duplicated.";
		}
	}

	private static function RealURI() : string
	{
		preg_match('/(.*)\/index\.php/', $_SERVER['SCRIPT_NAME'], $rpath);

		$uri = str_replace($rpath[1], '', $_SERVER['REQUEST_URI']);
		$uri = preg_replace('/\/+/', '/', $uri);
		$uri = rtrim($uri, '/');

		return (empty($uri)) ? '/' : $uri;
	}
}