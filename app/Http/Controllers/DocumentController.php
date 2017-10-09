<?php

namespace App\Http\Controllers;

use App\Cls;
use App\Document;
use App\Extractors;
use App\Tag;
use App\Tools\Holos;
use App\Tools\PdfExtractor;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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
				$pdfInfo = PdfExtractor::extract('holos', $info['pdfTmp']);
				$info['introduction'] = join("\n", array_map(function ($el) {
					return $el['text'];
				}, $pdfInfo['introduction']));
				$info['conclusion'] = join("\n", array_map(function ($el) {
					return $el['text'];
				}, $pdfInfo['conclusion']));
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

		$tagsInstances = Tag::tags(array_map(function ($tag) {
			return mb_strtolower($tag['text']);
		}, $tags));

		switch ($type)
		{
			case 'minute':
				$date = $info['date']['text'];
				if (empty($date))
					$date = null;
				$docData = [
					'type' => 'minute',
					'title' => $info['title']['text'],
					'date' => $date,
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
					'tags' => $tagsInstances
				]);
				$file->storeAs(join(DIRECTORY_SEPARATOR, [floor($document->id / 5000)]), $document->id . '.pdf', 'pdfs');
				break;
			case 'holos':
			case 'article':
				$date = $info['date'];
				if (empty($date))
					$date = null;
				else {
					$info['date'] .= ' 00:00:00';
				}
				$document = Document::createWith([
					'type' => 'article',
					'title' => $info['title'],
					'date' => $date,
					'abstract' => $info['abstract'],
					'introduction' => $info['introduction'],
					'conclusion' => $info['conclusion']
				], [
					'type' => Cls::article(),
					'tags' => $tagsInstances
				]);
				Storage::disk('pdfs')->putFileAs(floor($document->id / 5000), new File($info['pdfTmp']), $document->id . '.pdf');
				break;
		}

		return [
			'ok' => true,
			'document' => $document['attributes'],
			'id' => $document->id
		];
	}

	/**
	 * Atualiza o documento.
	 *
	 * @param null $id
	 */
	public function update(Request $request, $id)
	{
		$document = Document::find($id);
		if ($document instanceof Document)
		{
			$document->title = $request->input('title');
			$document->date = $request->input('date');

			foreach ($document->tags as $tag)
				$document->tags()->detach($tag);

			$tags = Tag::tags($request->input('tag'));
			foreach ($tags as $tag)
				$document->tags()->attach($tag);

			return response()->json([
				'ok' => true
			]);
		}
		else
		{
			return response()->json([
				'notFound' => true
			], 404);
		}
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
