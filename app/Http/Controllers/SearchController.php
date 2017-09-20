<?php

namespace App\Http\Controllers;

use App\Document;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;
use Illuminate\Http\Request;

class SearchController
{
	public function search(Request $request)
	{
		$query = $request->input('query');
		$page = $request->input('page');
		$tags = $request->input('tags');

		$qry = [];

		$body = [];

		if (!empty($page))
		{
			$qry["from"] = intval($page);
			$qry["size"] = 20;
		}

		if (!empty($query))
		{
			$qry['match'] = [
				"_all" => [
					'query' => $query,
					'cutoff_frequency' => 0.001
				],
			];
		}
		if (!empty($tags))
		{
			$qry['bool'] = [
				'must' => array_map(function ($tag) {
					return [
						'term' => [
							"tags" => $tag,
						],
					];
				}, $tags),
			];
			$body['aggs']['tags'] = [
				'terms' => [
					'field' => 'tags',
					'size' => 10
				]
			];
		}
		if (empty($qry))
		{
			$qry['match_all'] = new \stdClass();
		}
		$body['query'] = $qry;

		$result = Elasticsearch::search([
			'index' => 'documents',
			'body' => $body
		]);
		$hits = $result['hits'];
		$tagsAggregations = [];
		if (isset($result['aggregations']))
		{
			$aggs = $result['aggregations'];
			if (isset($aggs['tags']))
			{
				foreach ($aggs['tags']['buckets'] as $bucket)
					$tagsAggregations[$bucket['key']] = $bucket['doc_count'];
			}
		}
		return [
			"documents" => array_map(function ($el) {
				return $el['_source'];
			}, $hits['hits']),
			"tags" => $tagsAggregations,
			"total" => $hits['total'],
		];
	}
}