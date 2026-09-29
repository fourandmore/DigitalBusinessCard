<?php
namespace DigitalBusinessCard\Migrations;

use DigitalBusinessCard\Models\AdminAttempt;
use DigitalBusinessCard\Models\AdminSession;
use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;

class CreateSecurityTables
{
    public function run(Migrate $migrate)
    {
        $migrate->createTable(AdminAttempt::class);
        $migrate->createTable(AdminSession::class);
    }
}
