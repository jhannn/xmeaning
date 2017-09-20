<?php

namespace App\Observers;

use App\Document;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;

class DocumentObserver
{
	public function buildDoc(Document $document)
	{
		$doc = $document->toArray();
		$doc['neo4jId'] = $doc['id'];
		unset($doc['id']);
		$doc['type'] = mb_strtolower($document->type()->first()->name);
		$doc['tags'] = array_map(function ($el) {
			return mb_strtolower($el['name']);
		}, $document->tags()->getResults()->toArray());
		return $doc;
	}

	public function created(Document $document)
	{
		$doc = $this->buildDoc($document);
		$type = $doc['type'];
		unset($doc['type']);
		Elasticsearch::index([
			'index' => 'documents',
			'type' => $type,
			'body' => $doc
		]);
	}
}