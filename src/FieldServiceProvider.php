<?php

namespace Acm\NovaGmap;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            $gmap = 'https://maps.googleapis.com/maps/api/js?key='.env('GOOGLE_MAP_API_KEY').'&libraries=places&language='.config('app.locale');
            Nova::script('nova-gmap', __DIR__.'/../dist/js/field.js');
            Nova::script('google-map', $gmap);
            Nova::style('nova-gmap', __DIR__.'/../dist/css/field.css');
            Nova::provideToScript([
                'api_key' => config('nova-gmaps.gmaps_api_key'),
                'locale' => config('app.locale')
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $config_path = dirname(__DIR__).'/publishable/config/nova-gmaps.php';

        $this->publishes(
            [$config_path => config_path('nova-gmaps.php')], 
            'config'
        );
    }
}
