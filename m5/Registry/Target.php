<?php

namespace M5\Registry;

use Exception;

class Target extends Records
{
	public static function create($target) : bool
	{
		if(empty($target) || is_bool($target) || is_array($target))
			throw new Exception("'\$target' must be of stype 'string'");

		if(self::exists())
			return false;

		parent::$M5_TARGET = $target;

		return true;
	}

	public static function exists() : bool
	{
		return (parent::$M5_TARGET === null) ? false : true;
	}
}