<?php
namespace DigitalBusinessCard\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Templates\Twig;

class AdminPageController extends Controller
{
    public function show(Twig $twig)
    {
        return $twig->render('DigitalBusinessCard::admin');
    }
}
