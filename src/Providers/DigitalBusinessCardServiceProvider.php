<?php
namespace DigitalBusinessCard\Providers;

use Plenty\Plugin\ServiceProvider;
use DigitalBusinessCard\Contracts\BusinessCardRepositoryContract;
use DigitalBusinessCard\Repositories\BusinessCardRepository;

class DigitalBusinessCardServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->getApplication()->register(DigitalBusinessCardRouteServiceProvider::class);
        $this->getApplication()->bind(BusinessCardRepositoryContract::class, BusinessCardRepository::class);
    }
}
