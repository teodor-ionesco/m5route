<?php

namespace M5\View;

use M5\Registry\Records as RegistryRecords;

class Power
{
	public static function on() : void
	{
		require_once(M5_CONFIG_MAIN['ROOT_PATH'] . '/views/' . RegistryRecords::target());
	}

	private static function target() : string
	{
		
	}
}