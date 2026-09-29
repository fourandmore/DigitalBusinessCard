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

        $fields = [
            'slug','firstName','lastName','displayName','position','company','phone','email','website',
            'street','postalCode','city','country','logoUrl','brandsJson','imprintUrl','privacyUrl','footerClaim'
        ];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $card->{$field} = trim((string)$data[$field]);
            }
        }
        if (array_key_exists('active', $data)) {
            $card->active = filter_var($data['active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? ((int)$data['active'] === 1);
        }
        $card->slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9\-_]/', '-', $card->slug), '-'));
        $card->updatedAt = time();
        $this->db->save($card);
        return $card;
    }

    public function delete(int $id): bool
    {
        $card = $this->findById($id);
        if (!$card) return false;
        $this->db->delete($card);
        return true;
    }
}
