<?php

namespace M5\Request;

use M5\Registry\Request as RegistryRequest;
use M5\Registry\Records;

class Power
{
	public static function on() : void
	{
		$tmp = [
			'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
			'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'],
			'REQUEST_URI' => self::RealURI(),
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

		return (empty($uri)) ? '/' : $uri;
	}
}