<?php
namespace DigitalBusinessCard\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

class DigitalBusinessCardRouteServiceProvider extends RouteServiceProvider
{
    public function map(Router $router)
    {
        $router->get('visitenkarte/{slug}', 'DigitalBusinessCard\\Controllers\\BusinessCardController@show');
        $router->get('visitenkarte/{slug}/kontakt.vcf', 'DigitalBusinessCard\\Controllers\\BusinessCardController@vcard');

        // Die Verwaltungsseite ist nur unter dem in der Plugin-Konfiguration festgelegten Pfad erreichbar.
        $router->get('visitenkarten-admin/{adminPath}', 'DigitalBusinessCard\\Controllers\\AdminPageController@show');

        // Authentifizierung und geschützte CRUD-Endpunkte.
        $router->post('digital-business-card/admin/login', 'DigitalBusinessCard\\Controllers\\AdminController@login');
        $router->post('digital-business-card/admin/logout', 'DigitalBusinessCard\\Controllers\\AdminController@logout');
        $router->get('digital-business-card/admin/cards', 'DigitalBusinessCard\\Controllers\\AdminController@index');
        $router->post('digital-business-card/admin/cards', 'DigitalBusinessCard\\Controllers\\AdminController@store');
        $router->put('digital-business-card/admin/cards/{id}', 'DigitalBusinessCard\\Controllers\\AdminController@update')->where('id', '\\d+');
        $router->delete('digital-business-card/admin/cards/{id}', 'DigitalBusinessCard\\Controllers\\AdminController@destroy')->where('id', '\\d+');
    }
}
