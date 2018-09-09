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

		$uri = explode('?', $_SERVER['REQUEST_URI'], 2);

		$ret = str_replace($rpath[1], '', $uri[0]);
		$ret = preg_replace('/\/+/', '/', $ret);
		$ret = rtrim($ret, '/');

		if(count($uri) === 2)
		{
			// params here
			$ret .= '?'. $uri[1];
		}

		return (empty($ret)) ? '/' : $ret;
	}
}