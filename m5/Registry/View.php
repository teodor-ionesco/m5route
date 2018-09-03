<?php

namespace M5\Registry;

use Exception;

class View extends Records
{
	public static function create($path) : bool
	{
		if(empty($path) || is_bool($path) || is_array($path))
			throw new Exception("'\$path' must be of stype 'string'");

		if(self::exists())
			return false;

		parent::$M5_VIEW = $path;

		return true;
	}

	public static function exists() : bool
	{
		return (parent::$M5_VIEW === null) ? false : true;
	}
}