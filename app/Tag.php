<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Tag extends NeoEloquent
{
	private static $classes = null;

	protected $label = 'Tag';

	protected $fillable = [
		'name'
	];

	public static function tags($tags)
	{
		$persistedTags = self::where('toLower(tag.name)', 'in', $tags)->get();
		$persistedTagsCache = [];
		$tagsInstance = [];
		foreach ($persistedTags as $pt)
		{
			$persistedTagsCache[mb_strtolower($pt->name)] = $pt;
			$tagsInstance[] = $pt;
		}
		foreach ($tags as $tag)
		{
			$t = mb_strtolower($tag);
			if (!isset($persistedTagsCache[$t]))
			{
				var_dump($t);
				$tagsInstance[] = Tag::create([
					'name' => $tag
				]);
			}
		}
		return $tagsInstance;
	}
}
