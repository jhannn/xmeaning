<?php

namespace App\Tools;

class HolosInvalidDocument extends \Exception
{
	public function __construct()
	{
		parent::__construct('O documento informado não é válido.');
	}
}
