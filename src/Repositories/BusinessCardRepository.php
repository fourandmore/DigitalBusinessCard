<?php
namespace DigitalBusinessCard\Repositories;

use DigitalBusinessCard\Contracts\BusinessCardRepositoryContract;
use DigitalBusinessCard\Models\BusinessCard;
use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;

class BusinessCardRepository implements BusinessCardRepositoryContract
{
    private $db;

    public function __construct(DataBase $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        return $this->db->query(BusinessCard::class)->get();
    }

    public function findById(int $id): ?BusinessCard
    {
        $items = $this->db->query(BusinessCard::class)->where('id', '=', $id)->get();
        return $items[0] ?? null;
    }

    public function findBySlug(string $slug): ?BusinessCard
    {
        $items = $this->db->query(BusinessCard::class)->where('slug', '=', $slug)->get();
        return $items[0] ?? null;
    }

    public function save(array $data, int $id = 0): BusinessCard
    {
        $card = $id > 0 ? $this->findById($id) : null;

        if (!$card) {
            $card = pluginApp(BusinessCard::class);
            $card->createdAt = time();
        }

        if (array_key_exists('slug', $data)) {
            $card->slug = trim((string)$data['slug']);
        }
        if (array_key_exists('firstName', $data)) {
            $card->firstName = trim((string)$data['firstName']);
        }
        if (array_key_exists('lastName', $data)) {
            $card->lastName = trim((string)$data['lastName']);
        }
        if (array_key_exists('displayName', $data)) {
            $card->displayName = trim((string)$data['displayName']);
        }
        if (array_key_exists('position', $data)) {
            $card->position = trim((string)$data['position']);
        }
        if (array_key_exists('company', $data)) {
            $card->company = trim((string)$data['company']);
        }
        if (array_key_exists('phone', $data)) {
            $card->phone = trim((string)$data['phone']);
        }
        if (array_key_exists('email', $data)) {
            $card->email = trim((string)$data['email']);
        }
        if (array_key_exists('website', $data)) {
            $card->website = trim((string)$data['website']);
        }
        if (array_key_exists('street', $data)) {
            $card->street = trim((string)$data['street']);
        }
        if (array_key_exists('postalCode', $data)) {
            $card->postalCode = trim((string)$data['postalCode']);
        }
        if (array_key_exists('city', $data)) {
            $card->city = trim((string)$data['city']);
        }
        if (array_key_exists('country', $data)) {
            $card->country = trim((string)$data['country']);
        }
        if (array_key_exists('logoUrl', $data)) {
            $card->logoUrl = trim((string)$data['logoUrl']);
        }
        if (array_key_exists('brandsJson', $data)) {
            $card->brandsJson = trim((string)$data['brandsJson']);
        }
        if (array_key_exists('imprintUrl', $data)) {
            $card->imprintUrl = trim((string)$data['imprintUrl']);
        }
        if (array_key_exists('privacyUrl', $data)) {
            $card->privacyUrl = trim((string)$data['privacyUrl']);
        }
        if (array_key_exists('footerClaim', $data)) {
            $card->footerClaim = trim((string)$data['footerClaim']);
        }
        if (array_key_exists('active', $data)) {
            $activeValue = $data['active'];
            $card->active = $activeValue === true || $activeValue === 1 || $activeValue === '1' || $activeValue === 'true';
        }

        $card->slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9\-_]/', '-', $card->slug), '-'));
        $card->updatedAt = time();

        $this->db->save($card);

        return $card;
    }

    public function delete(int $id): bool
    {
        $card = $this->findById($id);
        if (!$card) {
            return false;
        }

        $this->db->delete($card);
        return true;
    }
}
