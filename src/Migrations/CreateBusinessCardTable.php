<?php
namespace DigitalBusinessCard\Migrations;

use DigitalBusinessCard\Models\BusinessCard;
use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;

class CreateBusinessCardTable
{
    public function run(Migrate $migrate)
    {
        $migrate->createTable(BusinessCard::class);
    }
}
