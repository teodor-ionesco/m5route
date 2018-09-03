<?php

namespace M5\Registry;

use Exception;

class Request extends Records
{
	public static function create($records) : bool
	{
		if(empty($records) || !is_array($records))
			throw new Exception("\$records must be an array.");

		if(self :: exists($records))
			return false;

		foreach($records as $key => $var)
		{

		}
	}

	private static function exists($records) : bool
	{
		if(empty(parent :: $M5_REQUEST))
			return false;

		foreach($records as $key => $var)
		{
			if(empty($key) || empty($var)) // Corrupted record
				continue;

			if(is_array($var))
			{

			}
			else
			{

				break;
			}
		}
	}
}