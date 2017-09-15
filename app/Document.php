<?php

namespace App;

class Document extends \NeoEloquent
{
	protected $label = 'Document';

	protected $fillable = [
		'title', 'date', 'temporary', 'agenda', 'attendees', 'reports', 'referrals', 'discussion', 'abstract',
		'introduction', 'conclusion', 'authors'
	];

	public function type()
	{
		return $this->hasOne('hasType');
	}
}
