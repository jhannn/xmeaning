<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends NeoEloquent
{
	protected $label = 'document';

	protected $fillable = [
		'id', 'title', 'date', 'temporary', 'agenda', 'attendees', 'reports', 'referrals', 'discussion', 'abstract',
		'introduction', 'conclusion', 'authors'
	];

	public function type()
	{
		return $this->hasOne('hasType');
	}
}
