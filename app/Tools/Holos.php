<?php

namespace App\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class Holos
{
	public static function get($id)
	{
		$response = Curl::to('www2.ifrn.edu.br/ojs/index.php/HOLOS/article/view/' . $id)->returnResponseObject()->get();
		if ($response->status == 200)
			return $response->content;
		else
			return false;
	}

	public static function parse($html)
	{
		try
		{
			$xml = simplexml_load_string(preg_replace("/\xC2./", '', trim($html)));
		}
		catch (\Throwable $e)
		{
			throw new HolosInvalidDocument();
		}
		if ($xml && $xml->head && $xml->head->meta)
		{
			$metas = $xml->head->meta;
			$found = false;
			$result = [
				'authors' => [],
				'tags' => []
			];
			foreach ($metas as $meta)
			{
				$attrs = $meta->attributes();
				$name = trim($attrs->name);
				$content = trim($attrs->content);
				if ($name == "DC.Creator.PersonalName")
				{
					array_push($result['authors'], $content);
					$found = true;
				}
				else if ($name == "DC.Subject")
				{
					array_push($result['tags'], $content);
					$found = true;
				}
				else if ($name == "DC.Title")
				{
					$result['title'] = $content;
					$found = true;
				}
				else if ($name == "DC.Date.created")
				{
					$result['date'] = $content;
					$found = true;
				}
				else if ($name == "DC.Description")
				{
					$result['abstract'] = $content;
					$found = true;
				}
				else if ($name == "citation_pdf_url")
				{
					$result['pdfUrl'] = $content;
					$found = true;
				}
			}
			if ($found)
				return $result;
			else
				throw new HolosInvalidDocument();
		}
		else
			throw new HolosInvalidDocument();
	}

	public static function downloadPdf($url)
	{
		$tmp = tmpfile();
		$meta_data = stream_get_meta_data($tmp);
		$tmpName = $meta_data["uri"];
		fclose($tmp);

		$responseHeaders = Curl::to($url)->withOption('NOBODY', 1)->withOption('HEADER', 1)->returnResponseObject()->download($tmpName);
		if ($responseHeaders->status == 200)
		{
			if (strpos($responseHeaders->content, 'application/pdf') !== false)
			{
				$response = Curl::to($url)->returnResponseObject()->download($tmpName);
				if ($response->status == 200)
					return $tmpName;
			}
		}
		return false;
	}

	public static function document($id)
	{
		$html = self::get($id);
		if ($html !== false)
		{
			$holos = self::parse($html);
			$pdf = self::downloadPdf($holos['pdfUrl']);
			if ($pdf !== false)
			{
				$holos['pdfTmp'] = $pdf;
				return $holos;
			}
			else
				throw new HolosCannotDownloadFile();
		}
		else
			throw new HolosCannotDownloadFile();
	}
}