<?php

namespace App\Tools;

class PdfExtractorException extends \Exception
{
	public function __construct($message, $code)
	{
		parent::__construct("${message}. CÓDIGO: ${code}.");
	}
}