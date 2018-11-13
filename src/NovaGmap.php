<?php

namespace Acm\NovaGmap;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaGmap extends Field
{
	public $component = 'nova-gmap';
	public $textAlign = 'center';


	public function __construct($name, $attribute = null, $resolveCallback = null)
	{
		parent::__construct($name, $attribute, $resolveCallback);

		$this->latitude(env('DEFAULT_LATITUDE'))
			 ->longitude(env('DEFAULT_LONGITUDE'))
			 ->zoom(env('DEFAULT_ZOOM'));
	}


	public function latitude($latitude)
	{
		return $this->withMeta(['latitude' => $latitude]);
	}


	public function longitude($longitude)
	{
		return $this->withMeta(['longitude' => $longitude]);
	}

	
	public function zoom($zoom)
	{
		return $this->withMeta(['zoom' => intval($zoom)]);
	}


	protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
	{
		$model->latitude  = $request['latitude'];
		$model->longitude = $request['longitude'];
	}
}
