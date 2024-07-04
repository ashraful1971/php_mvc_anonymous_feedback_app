<?php

namespace App\Core\Contracts;

interface DataStorage {
    public function getAllRecords(string $table_name): array;
    public function addNewRecord(string $table_name, array $data): self;
    public function save(): bool;
}