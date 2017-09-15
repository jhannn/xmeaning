<?php

namespace App\Http\Controllers;

use App\Cls;
use App\Document;
use App\Extractors;
use App\Tag;
use App\Tools\Holos;
use App\Tools\PdfExtractor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\map;

class DocumentController extends Controller
{
	private static $holosRegex = '/^(https?:\/\/)?www2?.ifrn.edu.br\/ojs\/index.php\/HOLOS\/article\/view\/([a-zA-Z0-9]+)$/';

	/**
	 * Insere um documento.
	 */
	public function create(Request $request)
	{
		if (($type = $request->input('type')) == 'holos')
		{
			$file = $request->input('file');
			if (preg_match(self::$holosRegex, $file, $match))
			{
				$holosId = $match[2];
				$info = Holos::document($holosId);
				$textToAnalyse = $info['abstract'];
				$tags = [];
			}
			else
			{
				return response()->json([
					'message' => 'O endereço do artigo HOLOS não foi reconhecido.'
				], Response::HTTP_BAD_REQUEST);
			}
		}
		else
		{
			$file = $request->file('file');
			$ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
			if ($ext != 'pdf')
			{
				return response()->json([
					'message' => 'O arquivo enviado deve ser um PDF.'
				], Response::HTTP_BAD_REQUEST);
			}

			$info = PdfExtractor::extract($type, $file->getPathName());
			$textToAnalyse = join("\n", array_map(function ($el) {
				return $el['text'];
			}, $info['all']));
			$tags = Extractors\XMeaning::extract($textToAnalyse);
		}

		$tagsCache = [];
		foreach ($tags as $tag)
		{
			$tagsCache[mb_strtolower($tag['text'])] = $tag;
		}

		if (count($tags) < 10)
		{
			$dandelionTags = Extractors\Dandelion::request($textToAnalyse);
			foreach ($dandelionTags['annotations'] as $tag)
			{
				if (!isset($tagsCache[$tag['text']]))
				{
					array_push($tags, $tag);
					$tagsCache[mb_strtolower($tag['text'])] = $tag;
				}
				if (count($tags) >= 10)
					break;
			}
		}

		$persistedTags = Tag::where('toLower(tag.name)', 'in', array_keys($tagsCache))->get();
		$persistedTagsCache = [];
		$tagsInstance = [];
		foreach ($persistedTags as $pt)
		{
			$persistedTagsCache[mb_strtolower($pt->name)] = $pt;
			$tagsInstance[] = $pt;
		}
		foreach ($tags as $tag)
		{
			$t = mb_strtolower($tag['text']);
			if (!isset($persistedTagsCache[$t]))
			{
				$tagsInstance[] = Tag::create([
					'name' => $tag['text']
				]);
			}
		}

		switch ($type)
		{
			case 'minute':
				$docData = [
					'title' => $info['title']['text'],
					'date' => $info['date']['text'],
					'agenda' => join("\n", array_map(function ($el) {
						return $el['textObject']['text'];
					}, $info['agenda'])),
					'discussion' => join("\n", array_map(function ($el) {
						return $el['text'];
					}, $info['discussion'])),
					'referrals' => join("\n", array_map(function ($el) {
						return $el['textObject']['text'];
					}, $info['referrals'])),
					'reports' => join("\n", array_map(function ($el) {
						return $el['textObject']['text'];
					}, $info['reports']))
				];
				$document = Document::createWith($docData, [
					'type' => Cls::minute(),
					'tags' => $tagsInstance
				]);
				break;
			case 'holos':
			case 'article':
				$document = Document::createWith([
					'title' => $info['title'],
					'date' => $info['date'],
					'abstract' => $info['abstract'],
					'introduction' => $info['introduction'],
					'conclusion' => $info['conclusion']
				], [
					'type' => Cls::article(),
					'tags' => $tagsInstance
				]);
				break;
		}

		return [
			'ok' => true
		];
	}

	/**
	 * Atualiza o documento.
	 *
	 * @param null $id
	 */
	public function update($id)
	{
		//
	}

	public function pdf($id)
	{
		$doc = Document::find($id);
		if ($doc == null)
		{
			// TODO: Create an 404 page
			return response()->json([
				"notFound" => true
			], 404);
		}
		else
		{
			return response()->json($doc);
		}
	}
}
