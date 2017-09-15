<?php

namespace App\Tools;


use Illuminate\Contracts\Filesystem\FileNotFoundException;

class PdfExtractorException extends \Exception
{
	public function __construct($message, $code)
	{
		parent::__construct("${message}. CÓDIGO: ${code}.");
	}
}

class PdfExtractor
{
	public static function jarPath()
	{
		$jar = env('PDFEXTRACTOR_JAR');
		return ($jar{0} == '/') ? $jar : __DIR__ . DIRECTORY_SEPARATOR . $jar;
	}

	public static function extract($type, $pdf)
	{
		if (!file_exists($pdf))
			throw new FileNotFoundException("O arquivo ${pdf} não foi encontrado.");

		$jarPath = self::jarPath();
		if (!file_exists($jarPath))
			throw new FileNotFoundException("O arquivo ${jarPath} não foi encontrado.");

		$descriptorspec = [
			1 => ['pipe', 'w'],
			2 => ['pipe', 'w'],
		];

		$cmd = join(' ', ['java', '-jar', $jarPath, '--json', '-t', $type, $pdf]);
		$proc = proc_open($cmd, $descriptorspec, $pipes, __DIR__ . DIRECTORY_SEPARATOR);
		if (!is_resource($proc))
			throw new \Exception("Failed to start background process by command: ${cmd}");

		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);

		fclose($pipes[1]);
		fclose($pipes[2]);

		if (($code = proc_close($proc)) == 0)
		{
			return json_decode($stdout, true);
		}
		else
			throw new PdfExtractorException($stderr, $code);
	}
}