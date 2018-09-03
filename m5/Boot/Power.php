<?php

namespace M5\Boot;

use M5\Request\Power as pRequest;
use M5\Routes\Power as pRoutes;
use M5\BUS\Power as pBUS;

class Power
{
	public static function on() : void
	{
		pRequest::on();	// Register requests
		pRoutes::on();	// Register routes
		pBUS::on();		// Start BUS which will put everything in its place
	}

	public static function off() : void
	{
		header('Connection: close', true, 500);

		die('M5: Aborted');
	}
}