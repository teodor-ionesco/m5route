<?php

namespace M5\BUS;

use M5\Registry\Records as RegistryRecords;

class Vars
{
	/*
	*
	*	Serialize variables. Loader for all child methods.
	*
	*/
	public static function serialize($route_vars) : array
	{
		$sections = explode('?', RegistryRecords::request()['URI'], 2);
		$ret['PATH'] = self::parse_path_vars($sections[0], $route_vars['PATH']);

		if(!empty($sections[1]))
			$ret['QUERY'] = self::parse_query_vars($sections[1], $route_vars['QUERY']);

		return $ret;
	}

	/*
	*
	*	Assign path variables.
	*
	*/
	private static function parse_path_vars($section, $vars) : array
	{
		$cells = explode('/', $section);
		$ret = [];

		foreach($vars as $key => $value)
		{
			$ret[$value] = $cells[$key];
		}

		return $ret;
	}

	/*
	*
	*	Assign query variables.
	*
	*/
	private static function parse_query_vars($section, $vars) : array
	{
		$ret = [];

		foreach(self::safely_merge_arrays($vars) as $key => $value)
		{
			$ret[$value] = $_GET[$value];
		}

		return $ret;
	}

	/*
	*
	*	Safe way to merge arrays. Keys are re-indexed.
	*
	*/
	private static function safely_merge_arrays($pack = []) : array
	{
		$c = 0;
		$ret = [];

		foreach($pack as $key => $array)
		{
			foreach($array as $k => $v)
			{
				$ret[$c] = $v;
				$c++;
			}
		}

		return $ret;
	}
}