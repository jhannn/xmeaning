<?php
/**
 * Created by PhpStorm.
 * User: jsantos
 * Date: 14/09/17
 * Time: 20:58
 */

namespace App\Tools;


class HolosCannotDownloadFile extends \Exception
{
	public function __construct()
	{
		parent::__construct("Não foi possível baixar o documento do website do HOLOS.");
	}
}