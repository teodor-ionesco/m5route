<?php

namespace M5\View;

use M5\View\Functions as ViewFunctions;
use M5\Registry\Records as RegistryRecords;

class Power
{
	public static function on($file, $vars = []) : void
	{
		if(!empty($vars) && is_array($vars))
		{
			foreach($vars as $key => $value)
			{
				${$key} = $value;
			}
		}

		require_once('Functions.php');
		require_once(M5_CONFIG_MAIN['ROOT_PATH'] . '/views/' . $file);
	}

	private static function parse_vars() : string
	{
		
	}
}