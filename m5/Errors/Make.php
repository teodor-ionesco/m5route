<?php

namespace M5\Errors;

use M5\Boot\Power as pBoot;
use M5\Registry\Errors as RegistryErrors;

class Make
{
	public static function new($array, $halt = false) : void
	{
		RegistryErrors::create($array);

		if($halt)
		{
			Push::all();
			pBoot::off();
		}
	}
}