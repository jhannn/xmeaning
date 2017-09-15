<?php

namespace App\Tools;

use Ixudra\Curl\Facades\Curl;

class Dandelion
{
	public static function request($text)
	{
		$response = Curl::to('http://api.dandelion.eu/datatxt/nex/v1')
			->withData([
				'text' => $text,
				'token' => env('DANDELION_TOKEN')
			])->returnResponseObject()->post();

		if ($response->status == 200)
		{
			$result = ['annotations' => []];
			$data = json_decode($response->content, true);
			foreach ($data['annotations'] as $annotation)
			{
				array_push($result['annotations'], [
					'text' => $annotation['spot'],
					'relevance' => $annotation['confidence']
				]);
			}
			return $result;
		}
		return false;
	}
}