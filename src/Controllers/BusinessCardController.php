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
        if (!is_array($brands)) $brands = [];
        return $twig->render('DigitalBusinessCard::card', ['card' => $card, 'brands' => $brands]);
    }

    public function vcard(string $slug, BusinessCardRepositoryContract $repo, Response $response): Response
    {
        $card = $repo->findBySlug($slug);
        if (!$card || !$card->active) return $response->make('Not found', 404);

        $name = $card->displayName ?: trim($card->firstName . ' ' . $card->lastName);
        $esc = function($v) { return str_replace(["\\", ";", ",", "\r", "\n"], ["\\\\", "\\;", "\\,", "", "\\n"], (string)$v); };
        $lines = [
            'BEGIN:VCARD','VERSION:3.0',
            'N:' . $esc($card->lastName) . ';' . $esc($card->firstName) . ';;;',
            'FN:' . $esc($name),
            'ORG:' . $esc($card->company),
            'TITLE:' . $esc($card->position),
            'TEL;TYPE=WORK,VOICE:' . $esc($card->phone),
            'EMAIL;TYPE=INTERNET,WORK:' . $esc($card->email),
            'URL:' . $esc($card->website),
            'ADR;TYPE=WORK:;;' . $esc($card->street) . ';' . $esc($card->city) . ';;' . $esc($card->postalCode) . ';' . $esc($card->country),
            'END:VCARD'
        ];
        return $response->make(implode("\r\n", $lines) . "\r\n", 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . ($card->slug ?: 'kontakt') . '.vcf"'
        ]);
    }
}
