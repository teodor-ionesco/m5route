<?php

namespace M5\Boot;

use M5\Request\Power as Request;
use M5\Routes\Power as Routes;
use M5\View\Power as View;

class Power
{
	public static function on() : void
	{
		Request::on();
		Routes::on();
		View::on();
	}

	public static function off() : void
	{
		exit;
	}
}