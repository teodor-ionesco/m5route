<?php

namespace M5\Registry;

class Errors extends Records
{
	public static function create($data) : bool
	{
		if(empty($data) || !is_array($data))
			throw new Exception("'\$data' must be of type 'array'");

		$e = true; 	// Error true/false
		$k = count(parent::$M5_ERRORS)+1;

		foreach($data as $key => $var)
		{
			if(is_array($var))
			{
				if(!self::check($var))
					continue;

				parent::$M5_ERRORS[$k]['LINE'] = $var[0];
				parent::$M5_ERRORS[$k]['LEVEL'] = $var[1];
				parent::$M5_ERRORS[$k]['MESSAGE'] = $var[2];

				$e = false;
				$k++;
			}
			else
			{
				if(!self::check($data))
					break;

				parent::$M5_ERRORS[$k]['LINE'] = $data[0];
				parent::$M5_ERRORS[$k]['LEVEL'] = $data[1];
				parent::$M5_ERRORS[$k]['MESSAGE'] = $data[2];

				$e = false;
				break;
			}
		}

		return $e ? false : true;
	}

	private static function check($array) : bool
	{
		if(empty($array[0]) || !is_numeric($array[0]))
			return false;

		if(!empty($array[1]))
		{
			switch ($array[1]) 
			{
				case 'ERROR': break;
				case 'WARNING': break;
				case 'NOTICE': break;
				default: return false;
			}
		}
		else
			return false;

		if(empty($array[2]) || is_bool($array[2]) || is_array($array[2]))
			return false;

		return true;
	}

	private static function exists() : void
	{

	}
}