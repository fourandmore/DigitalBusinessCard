<?php
namespace DigitalBusinessCard\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * @property int $id
 * @property string $slug
 * @property string $firstName
 * @property string $lastName
 * @property string $displayName
 * @property string $position
 * @property string $company
 * @property string $phone
 * @property string $email
 * @property string $website
 * @property string $street
 * @property string $postalCode
 * @property string $city
 * @property string $country
 * @property string $logoUrl
 * @property string $brandsJson
 * @property string $imprintUrl
 * @property string $privacyUrl
 * @property string $footerClaim
 * @property boolean $active
 * @property int $createdAt
 * @property int $updatedAt
 */
class BusinessCard extends Model
{
    public $id = 0;
    public $slug = '';
    public $firstName = '';
    public $lastName = '';
    public $displayName = '';
    public $position = '';
    public $company = '';
    public $phone = '';
    public $email = '';
    public $website = '';
    public $street = '';
    public $postalCode = '';
    public $city = '';
    public $country = '';
    public $logoUrl = '';
    public $brandsJson = '[]';
    public $imprintUrl = '';
    public $privacyUrl = '';
    public $footerClaim = 'QUALITÄT FÜR GENERATIONEN';
    public $active = true;
    public $createdAt = 0;
    public $updatedAt = 0;

    public function getTableName(): string
    {
        return 'DigitalBusinessCard::BusinessCard';
    }
}
