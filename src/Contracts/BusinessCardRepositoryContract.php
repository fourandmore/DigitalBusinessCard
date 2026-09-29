<?php
namespace DigitalBusinessCard\Contracts;

use DigitalBusinessCard\Models\BusinessCard;

interface BusinessCardRepositoryContract
{
    public function all(): array;
    public function findById(int $id): ?BusinessCard;
    public function findBySlug(string $slug): ?BusinessCard;
    public function save(array $data, int $id = 0): BusinessCard;
    public function delete(int $id): bool;
}
