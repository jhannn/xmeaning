<?php

namespace Tests\Feature;

use App\Cls;
use App\Document;
use App\Tag;
use Tests\TestCase;

class DocumentTest extends TestCase
{
	public function testInsert()
	{
		$doc = Document::createWith([
			'title' => 'Title 1'
		], [
			'type' => Cls::minute(),
			'tags' => [
				Tag::create(['name' => 'tag1']),
				Tag::create(['name' => 'tag2']),
				Tag::create(['name' => 'tag3']),
				Tag::create(['name' => 'tag4'])
			]
		]);
		$this->assertNotNull($doc);
	}
}
