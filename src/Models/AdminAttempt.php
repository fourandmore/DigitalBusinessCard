<?php
namespace DigitalBusinessCard\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * @property int $id
 * @property string $clientHash
 * @property int $failedAt
 */
class AdminAttempt extends Model
{
    public $id = 0;
    public $clientHash = '';
    public $failedAt = 0;

    public function getTableName(): string
    {
        return 'DigitalBusinessCard::AdminAttempt';
    }
}
