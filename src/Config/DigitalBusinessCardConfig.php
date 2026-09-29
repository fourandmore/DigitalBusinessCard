<?php
namespace DigitalBusinessCard\Config;

use Plenty\Modules\Webshop\Helpers\PluginConfig;

class DigitalBusinessCardConfig extends PluginConfig
{
    public $adminSecret = '';
    public $adminPath = 'kartenverwaltung-secure';

    protected function getPluginName(): string
    {
        return 'DigitalBusinessCard';
    }

    protected function load()
    {
        $this->adminSecret = $this->getTextValue('admin.secret', '');
        $this->adminPath = $this->getTextValue('admin.path', 'kartenverwaltung-secure');
    }
}
