<?php
namespace DigitalBusinessCard\Controllers;

use DigitalBusinessCard\Config\DigitalBusinessCardConfig;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;

class AdminPageController extends Controller
{
    public function show(string $adminPath, Twig $twig, DigitalBusinessCardConfig $config, Response $response): Response
    {
        $configuredPath = trim((string)$config->adminPath, '/ ');
        $requestedPath = trim((string)$adminPath, '/ ');

        if (strlen($configuredPath) < 12 || $requestedPath !== $configuredPath) {
            return $response->make('Not Found', 404, $this->securityHeaders());
        }

        $html = $twig->render('DigitalBusinessCard::admin');
        return $response->make($html, 200, $this->securityHeaders());
    }

    private function securityHeaders(): array
    {
        return [
            'Content-Type' => 'text/html; charset=utf-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Robots-Tag' => 'noindex, nofollow, noarchive, nosnippet',
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'no-referrer',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy' => "default-src 'self'; base-uri 'none'; frame-ancestors 'none'; form-action 'self'; object-src 'none'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; connect-src 'self'"
        ];
    }
}
