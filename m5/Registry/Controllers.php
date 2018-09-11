<?php

namespace M5\Registry;

class Controllers extends Records
{
	public static function create(&$str) : bool
	{
		if(self::exists($str))
			return false;

		parent::$M5_CONTROLLERS[count(parent::$M5_CONTROLLERS)+1] = $value;

		return true;
	}

	private static function exists(&$str) : bool
	{
		foreach(parent::$M5_CONTROLLERS as $value)
		{
			if($str === $value)
				return true;
		}

		return false;
	}
}