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
}
