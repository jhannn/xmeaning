<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Cls extends NeoEloquent
{
	private static $classes = null;

	protected $label = 'Class';

	protected $fillable = [
		'name'
	];

	private static function get($className)
	{
		if (self::$classes == null) {
			self::$classes = [];
			self::all()->each(function ($cls) {
				self::$classes[$cls->name] = $cls;
			});

		}
		return self::$classes[$className];
	}

	public static function document()
	{
		return self::get('Document');
	}

	public static function minute()
	{
		return self::get('Minute');
	}

	public static function article()
	{
		return self::get('Article');
	}
}
