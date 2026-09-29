<?php
namespace DigitalBusinessCard\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * @property int $id
 * @property string $tokenHash
 * @property string $clientHash
 * @property int $createdAt
 * @property int $lastSeenAt
 * @property int $expiresAt
 */
class AdminSession extends Model
{
    public $id = 0;
    public $tokenHash = '';
    public $clientHash = '';
    public $createdAt = 0;
    public $lastSeenAt = 0;
    public $expiresAt = 0;

    public function getTableName(): string
    {
        return 'DigitalBusinessCard::AdminSession';
    }
}
