<?php
namespace DigitalBusinessCard\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

class DigitalBusinessCardRouteServiceProvider extends RouteServiceProvider
{
    public function map(Router $router)
    {
        // Bewusst mit Prefix, um Kollisionen mit Kategorien und anderen Shop-Routen zu vermeiden.
        $router->get('visitenkarte/{slug}', 'DigitalBusinessCard\\Controllers\\BusinessCardController@show');
        $router->get('visitenkarte/{slug}/kontakt.vcf', 'DigitalBusinessCard\\Controllers\\BusinessCardController@vcard');

        // Backend-CRUD-Endpunkte. Die Pflegeoberflaeche ruft diese Endpunkte auf.
        $router->get('digital-business-card/admin/cards', 'DigitalBusinessCard\\Controllers\\AdminController@index');
        $router->post('digital-business-card/admin/cards', 'DigitalBusinessCard\\Controllers\\AdminController@store');
        $router->put('digital-business-card/admin/cards/{id}', 'DigitalBusinessCard\\Controllers\\AdminController@update')->where('id', '\\d+');
        $router->delete('digital-business-card/admin/cards/{id}', 'DigitalBusinessCard\\Controllers\\AdminController@destroy')->where('id', '\\d+');
    }
}
