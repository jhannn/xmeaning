<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
	private static $holosRegex = '/^(https?:\/\/)?www2?.ifrn.edu.br\/ojs\/index.php\/HOLOS\/article\/view\/([a-zA-Z0-9]+)$/';

	/**
	 * Insere um documento.
	 */
	public function create(Request $request)
	{
		if ($request->input('type') == 'holos')
		{
			$file = $request->input('file');
			if (preg_match(self::$holosRegex, $file, $match))
			{
				$holosId = $match[2];
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
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if ($ext != 'pdf')
			{
				return response()->json([
					'message' => 'O arquivo enviado deve ser um PDF.'
				], Response::HTTP_BAD_REQUEST);
			}
		}

		$tags = [];
		$tagsCache = [];
		$textToAnalyze = '';

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
