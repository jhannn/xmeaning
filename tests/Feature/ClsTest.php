<?php

namespace Tests\Feature;

use App\Cls;
use Tests\TestCase;

class ClsTest extends TestCase
{
	public function testDocument()
	{
		$this->assertNotNull(Cls::document());
		$this->assertEquals(Cls::document()->name, "Document");
	}

	public function testMinute()
	{
		$this->assertNotNull(Cls::minute());
		$this->assertEquals(Cls::minute()->name, "Minute");
	}

	public function testArticle()
	{
		$this->assertNotNull(Cls::article());
		$this->assertEquals(Cls::article()->name, "Article");
	}
}
