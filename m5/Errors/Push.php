<?php

namespace M5\Errors;

use M5\Boot\Power as pBoot;
use M5\Registry\Records as RegistryRecords;

class Push
{
	public static function all($halt = false) : void
	{
		if(!is_bool($halt))
			throw new Exception("'\$halt' must be of type 'bool'");

		echo self::build_output();

		if($halt)
			pBoot::off();
	}

	public static function auto($halt = false) : void
	{
		if(!is_bool($halt))
			throw new Exception("'\$halt' must be of type 'bool'");

		echo self::build_output(true);

		if($halt)
			pBoot::off();
	}

	public static function build_output($halt_on_error = false) : string
	{
		$output = null;

		foreach(RegistryRecords::errors() as $key => $array)
		{
			$output .= "<span style=\"font-size:17px;\">";
			$output .= "<b>($array[LINE]) M5 $array[LEVEL]: </b>";
			$output .= "<code>$array[MESSAGE]</code></span><br><br>";

			if($array['LEVEL'] === "ERROR")
			{
				if($halt_on_error)
					pBoot::off();
			}
		}

		return $output;
	}

	public static function is_error() : bool
	{
		foreach(RegistryRecords::errors() as $key => $array)
		{
			if($array[1] === "ERROR")
				return true;
		}

		return false;
	}
}