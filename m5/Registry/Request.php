<?php

namespace M5\Registry;

use Exception;

class Request extends Records
{
	public static function create($records) : bool
	{
		if(empty($records) || !is_array($records))
			throw new Exception("'\$records' must be of type 'array'.");

		if(self :: exists($records))
			return false;

		parent :: $M5_REQUEST[$records[0]] = $records[1];

		return true;
	}

	private static function exists($records) : bool
	{
		if(empty(parent :: $M5_REQUEST))
			return false;

		if(empty($records[0]) || empty($records[1])) // Corrupted record
			return false;

		foreach(parent :: $M5_REQUEST as $key => $var)
		{
			if($records[0] === $key)
				return true;
		}

		return false;
	}
}