<?php

namespace M5\Registry;

use Exception;

class Request extends Records
{
	public static function create($records) : bool
	{
		if(empty($records) || !is_array($records))
			throw new Exception("'\$records' must be of type 'array'.");

		//if(self :: exists($records))
		//	return false;
		
		parent :: $M5_REQUEST[$records[0]] = $records[1];

		return true;
	}

	// Not useful now.
	/*private static function exists($records) : bool
	{
		if(empty(parent :: $M5_REQUEST))
			return false;

		foreach(parent :: $M5_REQUEST as $key => $var)
		{
			if(parent :: $M5_REQUEST[$key] !== null && $key === $records[0])
				return true;
		}

		return false;
	}*/
}