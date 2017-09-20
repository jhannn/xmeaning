<?php

namespace Tests\Http\Controllers;

use App\Document;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
	public function testSearch()
	{
		$docs = Document::all();
		$d = $docs[0];
		$type = mb_strtolower($d->type()->first()->name);
		$tags = array_map(function ($el) {
			return mb_strtolower($el['name']);
		}, $d->tags()->getResults()->toArray());
		$this->assertTrue(true);
	}
}
