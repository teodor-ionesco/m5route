<?php

namespace M5\Controllers;

use M5\Registry\Controllers as RegistryControllers;

class Power
{
	public static function on() : void
	{
		$files = scandir(M5_CONFIG_MAIN['ROOT_PATH'] . '/app/Controllers');

		foreach($files as $value)
		{
			if(strpos($value, '.php') === false)
				continue;

			if(!RegistryControllers::create($value))
				die('Duplicated controller.');
		}
	}
}