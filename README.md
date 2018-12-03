##  Nova Gmap Field

A Nova field control for using Google Maps to update a latitude/longitude positon.

### Installation

Install the package via composer with:

	composer require adamcmoore/nova-gmap

Next publish the config file with:

	php artisan vendor:publish --provider="Acm\NovaGmap\FieldServiceProvider.php" --tag="config"

This will create a config file at `config/nova-gmaps.php` where you will need to enter your Google Maps API key, default position and zoom level.


### Usage

To add this field to a Nova resource:

```php
NovaGmap::make('Location')
```

The field expects data to be provided as an object with keys for `latitude` and `longitude`, and will return data from the request in the same format. Use Accessors and Mutators to handle this.

An example model:

```php
class Shop extends Model
{

	$fillable = [
		'title',
		'description',
		'address',
		'location_lat',
		'location_lng',
	];


	/*
	Provide the Location value to the Nova field
	*/
	public function getLocationAttribute()
	{
		return (object) [
			'latitude' => $this->location_lat,
			'longitude' => $this->location_lng,
		];
	}


	/*
	Transform the returned value from the Nova field
	*/
	public function setLocationAttribute($value)
	{
		$location_lat = round(object_get($value, 'latitude'), 7);
		$location_lng = round(object_get($value, 'longitude'), 7);
		$this->attributes['location_lat'] = $location_lat;
		$this->attributes['location_lng'] = $location_lng;
	}
}
```