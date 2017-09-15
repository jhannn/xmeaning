<?php

namespace Tests\Tools;

use App\Tools\Dandelion;
use Tests\TestCase;

define('SENTENSE', 'The Mona Lisa is a 16th century oil painting created by Leonardo. It\'s held at the Louvre in Paris.');

class DandelionTest extends TestCase
{
	public function testRequestOk()
	{
		$result = Dandelion::request(SENTENSE);
		$this->assertArrayHasKey('annotations', $result);
	}
}
