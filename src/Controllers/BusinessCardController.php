<?php
namespace DigitalBusinessCard\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;
use DigitalBusinessCard\Contracts\BusinessCardRepositoryContract;

class BusinessCardController extends Controller
{
    public function show(string $slug, Twig $twig, BusinessCardRepositoryContract $repo, Response $response)
    {
        $card = $repo->findBySlug($slug);
        if (!$card || !$card->active) {
            return $response->make('Visitenkarte nicht gefunden.', 404);
        }

        $brands = json_decode($card->brandsJson ?: '[]', true);
        if (!is_array($brands)) {
            $brands = [];
        }

        $html = $twig->render('DigitalBusinessCard::card', [
            'card' => $card,
            'brands' => $brands
        ]);

        return $response->make($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function vcard(string $slug, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $card = $repo->findBySlug($slug);
        if (!$card || !$card->active) {
            return $response->make('Not found', 404);
        }

        $name = $card->displayName ?: trim($card->firstName . ' ' . $card->lastName);
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'N:' . $this->escapeVCardValue($card->lastName) . ';' . $this->escapeVCardValue($card->firstName) . ';;;',
            'FN:' . $this->escapeVCardValue($name),
            'ORG:' . $this->escapeVCardValue($card->company),
            'TITLE:' . $this->escapeVCardValue($card->position),
            'TEL;TYPE=WORK,VOICE:' . $this->escapeVCardValue($card->phone),
            'EMAIL;TYPE=INTERNET,WORK:' . $this->escapeVCardValue($card->email),
            'URL:' . $this->escapeVCardValue($card->website),
            'ADR;TYPE=WORK:;;' . $this->escapeVCardValue($card->street) . ';' . $this->escapeVCardValue($card->city) . ';;' . $this->escapeVCardValue($card->postalCode) . ';' . $this->escapeVCardValue($card->country),
            'END:VCARD'
        ];

        return $response->make(implode("\r\n", $lines) . "\r\n", 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . ($card->slug ?: 'kontakt') . '.vcf"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    private function escapeVCardValue($value): string
    {
        return str_replace(
            ["\\", ";", ",", "\r", "\n"],
            ["\\\\", "\\;", "\\,", "", "\\n"],
            (string)$value
        );
    }
}
