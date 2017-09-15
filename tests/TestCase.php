<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use CreatesApplication;

	public static function resource(...$params)
	{

		return __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . (empty($params) ? '' : DIRECTORY_SEPARATOR) . join(DIRECTORY_SEPARATOR, $params);
	}
}
