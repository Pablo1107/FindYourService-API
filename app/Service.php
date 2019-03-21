<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

	public function scopeIsWithinMaxDistance($query, $location, $radius = 25) {

		$haversine = "(6371 * acos(cos(radians($location->latitude)) 
			* cos(radians(latitude)) 
			* cos(radians(longitude) 
			- radians($location->longitude)) 
			+ sin(radians($location->latitude)) 
			* sin(radians(latitude))))";
		return $query
			->select() //pick the columns you want here.
			->selectRaw("{$haversine} AS distance")
			->whereRaw("{$haversine} < ?", [$radius]);
	}
}
